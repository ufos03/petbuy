<?php
/**
 * Mixed Search Cache
 * 
 * Gestisce la cache per le ricerche asincrone.
 * Usa WordPress transients API per memorizzazione temporanea.
 * 
 * @package PetBuy
 * @subpackage Mixed
 */

namespace App\Mixed;

class MixedSearchCache {
    
    private const CACHE_PREFIX = 'mixed_search_';
    private const CACHE_EXPIRATION = 300; // 5 minuti
    
    /**
     * Salva risultati di ricerca in cache
     * 
     * @param string $token Token univoco per la ricerca
     * @param array $data Dati da salvare
     * @param int $expiration Tempo di scadenza in secondi (default 5 minuti)
     * @return bool True se salvato con successo
     */
    public function set(string $token, array $data, int $expiration = self::CACHE_EXPIRATION): bool {
        $cacheKey = $this->getCacheKey($token);
        return set_transient($cacheKey, $data, $expiration);
    }
    
    /**
     * Recupera risultati di ricerca dalla cache
     * 
     * @param string $token Token univoco per la ricerca
     * @return array|false Dati salvati o false se non trovati/scaduti
     */
    public function get(string $token) {
        $cacheKey = $this->getCacheKey($token);
        return get_transient($cacheKey);
    }
    
    /**
     * Verifica se esiste cache valida per un token
     * 
     * @param string $token Token univoco
     * @return bool True se cache esiste e non è scaduta
     */
    public function has(string $token): bool {
        return $this->get($token) !== false;
    }
    
    /**
     * Elimina cache per un token
     * 
     * @param string $token Token univoco
     * @return bool True se eliminata con successo
     */
    public function delete(string $token): bool {
        $cacheKey = $this->getCacheKey($token);
        return delete_transient($cacheKey);
    }
    
    /**
     * Pulisce tutte le cache scadute
     * 
     * @return int Numero di cache eliminate
     */
    public function cleanup(): int {
        global $wpdb;
        
        // Elimina transients scaduti con prefisso mixed_search_
        $sql = "DELETE FROM {$wpdb->options} 
                WHERE option_name LIKE '_transient_timeout_" . self::CACHE_PREFIX . "%' 
                AND option_value < %d";
        
        $deleted = $wpdb->query($wpdb->prepare($sql, time()));
        
        return $deleted ? $deleted : 0;
    }
    
    /**
     * Genera chiave cache univoca
     * 
     * @param string $token Token ricerca
     * @return string Chiave cache
     */
    private function getCacheKey(string $token): string {
        return self::CACHE_PREFIX . $token;
    }
    
    /**
     * Genera token univoco per una ricerca
     * 
     * @param string $searchTerm Termine di ricerca
     * @param array $filters Filtri
     * @param int $page Pagina
     * @param int $perPage Items per pagina
     * @return string Token MD5
     */
    public function generateToken(string $searchTerm, array $filters, int $page, int $perPage): string {
        $data = [
            'term' => $searchTerm,
            'filters' => $filters,
            'page' => $page,
            'per_page' => $perPage,
            'timestamp' => time()
        ];
        
        return md5(json_encode($data));
    }
}
