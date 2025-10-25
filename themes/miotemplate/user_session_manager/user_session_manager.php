<?php
declare(strict_types=1);

namespace App\Auth;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

require_once __DIR__ . "/vendor/autoload.php";


use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Firebase\JWT\CachedKeySet;
use Firebase\JWT\JWK;
use Firebase\JWT\JWTExceptionWithPayloadInterface;
use Firebase\JWT\BeforeValidException;
use Firebase\JWT\SignatureInvalidException;
use Firebase\JWT\ExpiredException;
use Exception;

/**
 * Class UserSessionManager
 *
 * Gestisce la generazione, validazione, aggiornamento e distruzione dei token JWT
 * utilizzando l'ora di WordPress (`current_time`) per garantire coerenza con il database.
 * La pulizia periodica dei token scaduti viene effettuata da un cron job dedicato.
 */
class UserSessionManager  // Implementa un metodo che da un token ritorni lo user ID
{
    private string $secretKey;
    private string $issuer;
    private string $audience;
    private string $algorithm;
    private string $tableName;

    /**
     * UserSessionManager
     * constructor.
     *
     * @param string $secretKey  Chiave segreta per la firma dei JWT.
     * @param string $issuer     Emittente del token.
     * @param string $audience   Destinatario del token.
     * @param string $algorithm  Algoritmo di firma (default: HS256).
     */
    public function __construct(string $secretKey, string $issuer, string $audience, string $algorithm = 'HS512')
    {
        $this->secretKey = $secretKey;
        $this->issuer = $issuer;
        $this->audience = $audience;
        $this->algorithm = $algorithm;

        global $wpdb;
        $this->tableName = $wpdb->prefix . 'user_session';
    }

    /**
     * Genera e persist e un nuovo token JWT per un utente.
     *
     * L'orario di emissione e scadenza viene calcolato tramite `current_time` per
     * mantenere allineamento con le date memorizzate nel database.
     *
     * @param int $userId ID dell'utente.
     * @param int $expiry Vita del token in secondi (default 1 ora).
     *
     * @return string Token JWT generato.
     *
     * @throws Exception Se si verifica un errore durante la generazione o il salvataggio.
     */
    public function generateToken(int $userId, int $expiry = 3600): string
    {
        $issuedAt = \current_time('timestamp');
        $expire = $issuedAt + $expiry;

        $payload = [
            'iss' => $this->issuer,
            'aud' => $this->audience,
            'iat' => $issuedAt,
            'nbf' => $issuedAt,
            'exp' => $expire,
            'sub' => $userId
        ];

        $jwt = JWT::encode($payload, $this->secretKey, $this->algorithm);

        global $wpdb;

        $result = $wpdb->insert(
            $this->tableName,
            [
                'user_id'    => $userId,
                'token'      => $jwt,
                'expires_at' => \wp_date('Y-m-d H:i:s', $expire)
            ],
            [
                '%d',
                '%s',
                '%s'
            ]
        );

        if ($result === false) {
            throw new Exception("Errore durante il salvataggio del token nel database.");
        }

        return $jwt;
    }

    /**
     * Elimina i token scaduti dal database.
     *
     * @return int Numero di righe eliminate.
     */
    public function deleteExpiredTokens(): int
    {
        global $wpdb;

        $current_time = \current_time('mysql');

        $deleted = $wpdb->query(
            $wpdb->prepare(
                "DELETE FROM {$this->tableName} WHERE expires_at < %s",
                $current_time
            )
        );

        return $deleted !== false ? (int)$deleted : 0;
    }

    /**
     * Aggiorna un token esistente prima che scada.
     *
     * @param string $token             Token JWT da aggiornare.
     * @param int    $additionalTime    Tempo aggiuntivo in secondi per la nuova scadenza.
     *
     * @return string|null              Nuovo token JWT o null se il token non è valido.
     *
     * @throws Exception                Se si verifica un errore durante l'aggiornamento.
     */
    public function updateToken(string $token, int $additionalTime = 3600): ?string
    {
        $decoded = $this->validateToken($token);

        if (!$decoded) {
            return null;
        }

        $userId = (int)$decoded->sub;

        global $wpdb;

        // Inizia una transazione per garantire la coerenza
        $wpdb->query('START TRANSACTION');

        try {
            // Genera un nuovo token
            $newToken = $this->generateToken($userId, $additionalTime);

            // Elimina il vecchio token
            $destroyed = $this->destroyToken($token);

            if (!$destroyed) {
                throw new Exception("Impossibile eliminare il vecchio token.");
            }

            // Commit della transazione
            $wpdb->query('COMMIT');

            return $newToken;
        } catch (Exception $e) {
            // Rollback in caso di errore
            $wpdb->query('ROLLBACK');
            throw new Exception("Errore durante l'aggiornamento del token: " . $e->getMessage());
        }
    }

    /**
     * Distrugge (revoca) un token specifico.
     *
     * @param string $token   Token JWT da distruggere.
     *
     * @return bool           True se il token è stato eliminato, false altrimenti.
     */
    public function destroyToken(string $token): bool
    {
        global $wpdb;

        $deleted = $wpdb->delete(
            $this->tableName,
            ['token' => $token],
            ['%s']
        );

        return $deleted > 0;
    }

    /**
     * Verifica e decodifica un token JWT.
     *
     * Esegue tre controlli:
     *  1. Decodifica la firma tramite la secret Key.
     *  2. Verifica che il token esista ancora nel database e che il challenge 2FA combaci.
     *  3. Confronta la data di scadenza salvata nel DB con l'orario corrente. Se scaduto, il token viene distrutto.
     *
     * @param string $token Token JWT da verificare.
     * @param int    $two_fa_challenge Stato del challenge 2FA richiesto (default 1).
     *
     * @return object|false Payload decodificato se valido, false altrimenti.
     */
    public function validateToken(string $token, int $two_fa_challenge = 1)
    {
        try {
            $decoded = JWT::decode($token, new Key($this->secretKey, $this->algorithm));

            global $wpdb;

            $row = $wpdb->get_row(
                $wpdb->prepare(
                    "SELECT expires_at FROM {$this->tableName} WHERE token = %s AND twofa_challenge = %d LIMIT 1",
                    $token,
                    $two_fa_challenge
                ),
                ARRAY_A
            );

            if (!$row) {
                return false;
            }

            $expiresAt = isset($row['expires_at']) ? strtotime($row['expires_at']) : false;
            if ($expiresAt !== false && $expiresAt <= \current_time('timestamp')) {
                $this->destroyToken($token);
                return false;
            }

            return $decoded;
        } catch (ExpiredException $e) {
            $this->destroyToken($token);
            return false;
        } catch (Exception $e) {
            return false;
        }
    }

        /**
     * Ottiene l'ID dell'utente associato a un token JWT.
     *
     * @param string $token Token JWT da analizzare.
     *
     * @return int|null      ID dell'utente se il token è valido, null altrimenti.
     */
    public function getUserIdFromToken(string $token, $two_fa_challenge = 1): ?int
    {
        try {
            // Decodifica e valida il token
            $decoded = $this->validateToken($token, $two_fa_challenge);

            if (!$decoded) {
                return NULL;
            }

            // Estrarre l'ID utente dal payload
            if (isset($decoded->sub)) {
                return (int)$decoded->sub;
            }

            return NULL;
        } catch (Exception $e) {

            return NULL;
        }
    }


        /**
     * Imposta il valore di twofa_challenge a 1 (completato) per un token specifico.
     *
     * @param string $token Token JWT per il quale si vuole aggiornare il campo twofa_challenge.
     *
     * @return bool  True se l'aggiornamento è riuscito (almeno una riga modificata), false altrimenti.
     */
    public function markTwofaChallengeAsCompleted(string $token): bool
    {
        global $wpdb;

        // Aggiorna il campo twofa_challenge a 1
        $updated = $wpdb->update(
            $this->tableName,
            ['twofa_challenge' => 1],  // nuovo valore
            ['token' => $token],       // condizione di aggiornamento
            ['%d'],                    // formato del nuovo valore (intero)
            ['%s']                     // formato della condizione (stringa)
        );

        // Se $updated > 0, significa che almeno una riga è stata aggiornata con successo
        return $updated > 0;
    }

}
