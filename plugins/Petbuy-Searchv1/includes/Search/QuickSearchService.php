<?php

namespace Petbuy\Search\Search;

require_once __DIR__ . '/Manticore/vendor/autoload.php';
require_once __DIR__ . '/../Wordforms/Repository.php';

use Manticoresearch\Client;
use Petbuy\Search\Cache\SmartCache;
use Petbuy\Search\Wordforms\Repository;

/**
 * QuickSearchService
 *
 * Servizio per la ricerca rapida di prodotti/annunci e negozi tramite Manticore Search.
 * Implementa cache intelligente e popolarità dei termini di ricerca.
 */
class QuickSearchService
{
    /** @var Client Istanza del client Manticore Search */
    private Client $client;

    /** @var SmartCache Gestore della cache per i risultati di ricerca */
    private SmartCache $cache;

    /** @var array Impostazioni del servizio di ricerca */
    private array $settings;

    /** @var string Indice prodotti/annunci (default: rt_ads_prods) */
    private string $idxProducts;

    /** @var string Indice negozi (default: rt_stores) */
    private string $idxStores;

    /** @var string Campi prodotti fulltext */
    private string $prodFields = 'name,category,sub_category';

    /** @var string Campo negozio fulltext */
    private string $storeField = 'store_name';

    public function __construct()
    {
        $this->client   = new Client([ 'host' => '127.0.0.1', 'port' => 9308 ]);
        $this->settings = get_option(PETBUY_QS_SETTINGS);
        $this->cache    = new SmartCache($this->settings['cache_group'] ?? 'QSEARCH');

        $this->idxProducts = $this->settings['index_products'] ?? 'rt_ads_prods';
        $this->idxStores   = $this->settings['index_stores']   ?? 'rt_dokan_stores';
    }

    /**
     * Gestisce la richiesta REST per la ricerca preview.
     * Restituisce risultati Manticore + token per l'API completa.
     * 
     * PRELOAD: Chiama /fsearch in modo asincrono per precaricare i risultati completi.
     *
     * @param \WP_REST_Request $req Richiesta REST contenente il parametro 's'
     * @return array Risultati Manticore + token
     */
    public function rest_search(\WP_REST_Request $req)
    {
        $startTime = microtime(true);
        $term  = sanitize_text_field($req['s']);
        $limit = (int) ($this->settings['limit_results'] ?? 10);

        if (mb_strlen($term) < 3) {
            return [
                'status' => 'error',
                'message' => 'Term too short (min 3 characters)'
            ];
        }

        // Elimina cache precedente per questo utente
        $this->clearUserPreviousCache();
        
        // Genera token univoco
        $token = $this->generateSearchToken();
        $cacheKey = $this->tokenToCacheKey($token);
        
        // Esegue ricerca Manticore
        $manticoreStart = microtime(true);
        $manticoreResults = $this->search($term, $limit);
        $manticoreDuration = (microtime(true) - $manticoreStart) * 1000;
        
        // Salva in cache: termine + risultati Manticore
        wp_cache_set($cacheKey, [
            'term' => $term,
            'results' => $manticoreResults,
            'timestamp' => time()
        ], 'petbuy_user_search', HOUR_IN_SECONDS);
        
        // Aggiorna indice token utente
        $this->addTokenToUserIndex($token);
        
        // **PRELOAD ASINCRONO**: Chiama /fsearch per precaricare risultati completi
        $preloadStart = microtime(true);
        $this->preloadFullSearch($term, $token);
        $preloadTriggerDuration = (microtime(true) - $preloadStart) * 1000;
        
        $totalDuration = (microtime(true) - $startTime) * 1000;
        
        error_log(sprintf(
            "[QuickSearch] 🚀 ASYNC | Term: '%s' | Token: %s | " .
            "Manticore: %.2fms | Preload: %.2fms | Total: %.2fms | Results: %d",
            $term,
            $token,
            $manticoreDuration,
            $preloadTriggerDuration,
            $totalDuration,
            count($manticoreResults)
        ));
        
        // Restituisce preview Manticore + token per API completa
        return [
            'token' => $token,
            'results' => $manticoreResults,
        ];
    }

    /**
     * Precarica i risultati completi in modo veramente asincrono.
     * 
     * Usa wp_schedule_single_event per eseguire la ricerca in background
     * dopo che la risposta è stata inviata al client.
     *
     * @param string $term Termine di ricerca
     * @param string $token Token generato
     * @return void
     */
    private function preloadFullSearch(string $term, string $token): void
    {
        $triggerTime = microtime(true);
        
        // WordPress NON spacchetta l'array, passa l'intero array come primo parametro
        // Quindi dobbiamo passare un array associativo come unico argomento
        wp_schedule_single_event(time(), 'petbuy_async_fullsearch', [[
            'term' => $term,
            'token' => $token,
            'page' => 1,
            'per_page' => 20
        ]]);
        
        // Forza l'esecuzione immediata dello scheduled event
        // spawn_cron() triggera il cron in background
        spawn_cron();
        
        $triggerDuration = (microtime(true) - $triggerTime) * 1000;
        
        error_log(sprintf(
            "[Preload] ⚡ SCHEDULED | Token: %s | Term: '%s' | Time: %.2fms",
            $token,
            $term,
            $triggerDuration
        ));
    }

    /**
     * Genera un token univoco per questa ricerca.
     * Formato: {counter}_{user_id}_{client_id}
     * 
     * Per utenti autenticati: usa user_id
     * Per utenti guest: usa hash(IP + User-Agent) come client_id
     *
     * @return string Token nel formato counter-userid-clientid
     */
    private function generateSearchToken(): string
    {
        $userId = get_current_user_id();
        
        if (!$userId) {
            // Guest user: genera client_id basato su IP + User-Agent
            $clientId = $this->getGuestClientId();
        } else {
            // Logged user: usa 'user' come identificatore
            $clientId = 'user';
        }
        
        // Counter incrementale per questo utente/client
        $counterKey = "qsearch_counter_{$userId}_{$clientId}";
        $counter = (int) wp_cache_get($counterKey, 'petbuy_user_search');
        $counter++;
        wp_cache_set($counterKey, $counter, 'petbuy_user_search', HOUR_IN_SECONDS);
        
        // Formato: counter-userid-clientid
        return "{$counter}-{$userId}-{$clientId}";
    }
    
    /**
     * Genera un client ID univoco per utenti guest.
     * Usa hash di IP + User-Agent per identificare il client.
     * 
     * @return string Client ID (8 caratteri)
     */
    private function getGuestClientId(): string
    {
        // Recupera IP reale (anche dietro proxy/CDN)
        $ip = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
        
        // Controlla header comuni per IP reale dietro proxy
        if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR'])[0];
        } elseif (!empty($_SERVER['HTTP_X_REAL_IP'])) {
            $ip = $_SERVER['HTTP_X_REAL_IP'];
        }
        
        // User-Agent per distinguere dispositivi diversi dallo stesso IP
        $userAgent = $_SERVER['HTTP_USER_AGENT'] ?? 'unknown';
        
        // Hash corto (primi 8 caratteri MD5)
        $clientId = substr(md5($ip . '|' . $userAgent), 0, 8);
        
        return $clientId;
    }

    /**
     * Converte un token in chiave cache.
     *
     * @param string $token Token nel formato counter-userid-session
     * @return string Chiave cache
     */
    private function tokenToCacheKey(string $token): string
    {
        return "qsearch_token_{$token}";
    }

    /**
     * Aggiunge un token all'indice dell'utente corrente.
     */
    private function addTokenToUserIndex(string $token): void
    {
        $userId = get_current_user_id();
        $clientId = $userId ? 'user' : $this->getGuestClientId();
        $userKeysIndex = "qsearch_keys_{$userId}_{$clientId}";
        
        $previousTokens = wp_cache_get($userKeysIndex, 'petbuy_user_search') ?: [];
        $previousTokens[] = $token;
        wp_cache_set($userKeysIndex, $previousTokens, 'petbuy_user_search', HOUR_IN_SECONDS);
    }

    /**
     * Genera la chiave cache univoca per utente/sessione.
     * Formato: {term_number}-{user_id}-{session_id}
     *
     * @param string $term Termine di ricerca
     * @return string Chiave cache personalizzata
     * @deprecated Usa generateSearchToken() invece
     */
    private function getUserCacheKey(string $term): string
    {
        $userId = get_current_user_id();
        
        // Gestione sessione per utenti non loggati
        if (!$userId) {
            if (!session_id()) {
                session_start();
            }
            $sessionId = session_id();
        } else {
            $sessionId = 'user';
        }
        
        // Numero sequenziale del termine (basato su hash per consistenza)
        $termNumber = abs(crc32($term));
        
        return "qsearch_{$termNumber}_{$userId}_{$sessionId}";
    }

    /**
     * Elimina le cache precedenti per questo utente/client.
     * Mantiene solo la ricerca più recente.
     */
    private function clearUserPreviousCache(): void
    {
        $userId = get_current_user_id();
        $clientId = $userId ? 'user' : $this->getGuestClientId();
        
        // Pattern per trovare tutte le cache di questo utente/client
        $pattern = "qsearch_*_{$userId}_{$clientId}";
        
        // WordPress non ha un metodo nativo per flush by pattern,
        // quindi manteniamo una lista delle chiavi per utente
        $userKeysIndex = "qsearch_keys_{$userId}_{$clientId}";
        $previousKeys = wp_cache_get($userKeysIndex, 'petbuy_user_search');
        
        if (is_array($previousKeys)) {
            foreach ($previousKeys as $key) {
                wp_cache_delete($key, 'petbuy_user_search');
            }
        }
        
        // Resetta l'indice
        wp_cache_set($userKeysIndex, [], 'petbuy_user_search', HOUR_IN_SECONDS);
    }

    /**
     * Pipeline di ricerca (metodo originale, ora usato internamente):
     * 1) Cache
     * 2) Parola canonica → risposta diretta
     * 3) Fulltext: prodotti/annunci + negozi
      * 4) Se vuoto, Autocomplete: prodotti + negozi
     * 5) Aggiorna popolarità e cache
     *
     * @param string $term
     * @param int    $limit
     * @return array Lista [{term, type}] ordinata
     */
    public function search(string $term, int $limit): array
    {
        if (mb_strlen($term) < 3) return [];

        // Versiona la chiave cache per evitare collisioni con vecchio formato risultati
        $key = md5('v2|' . $term);
        if ($cached = $this->cache->get($key)) return $cached;

        // Se "term" è una canonica, restituisco direttamente (come prodotto)
        $repo = new Repository();
        if (in_array(mb_strtolower($term), array_keys($repo->get_assoc()), true)) {
            $direct = [[ 'term' => $term, 'type' => 'pr' ]];
            $this->cache->set($key, $term, $direct, $this->settings['ttl_results'] ?? HOUR_IN_SECONDS);
            return $direct;
        }

        // 3) Fulltext combinato (prodotti/annunci + negozi)
        $prodFast  = $this->fastSearchAdsProds($term, $limit);   // con type ad|pr
        $storeFast = $this->fastSearchStores($term, $limit);     // con type st
        $combined  = $this->mergeAndRank($term, $prodFast, $storeFast, $limit);

        // 4) Fallback: autocomplete combinato
        if (empty($combined)) {
            $prodAuto  = array_map(fn($q) => ['term' => $q, 'type' => 'pr'], $this->autocomplete($term, $limit));
            $storeAuto = array_map(fn($q) => ['term' => $q, 'type' => 'st'], $this->autocompleteStores($term, $limit));
            $combined  = $this->mergeAndRank($term, $prodAuto, $storeAuto, $limit);
        }

        // Popolarità → TTL dinamico
        $popKey = 'qs_pop_' . $key;
        $count  = (int) get_transient($popKey);
        $count++;
        set_transient($popKey, $count, DAY_IN_SECONDS * 30);

        $ttl = ($count >= (int) ($this->settings['popularity_threshold'] ?? 20))
            ? (int) ($this->settings['ttl_popular'] ?? DAY_IN_SECONDS)
            : (int) ($this->settings['ttl_results'] ?? HOUR_IN_SECONDS);

        $this->cache->set($key, $term, $combined, $ttl);
        return $combined;
    }

    /** AUTOCOMPLETE Prodotti (ritorna array di stringhe) */
    private function autocomplete(string $term, int $limit): array
    {
        $response = $this->client->autocomplete([
            'body' => [
                'table'   => $this->idxProducts,
                'query'   => $term,
                'options' => [
                    'fuzziness'     => (int) ($this->settings['fuzziness'] ?? 1),
                    'layouts'       => ['it','us'],
                    'append'        => 1,
                    'prepend'       => 1,
                    'expansion_len' => (int) ($this->settings['exp_limit'] ?? 10),
                ],
            ],
        ]);

        $results = [];
        if (!empty($response[0]['data'])) {
            foreach (array_slice($response[0]['data'], 0, $limit) as $row) {
                $results[] = $row['query'];
            }
        }
        return $results;
    }

    /** AUTOCOMPLETE Negozi (ritorna array di stringhe) */
    private function autocompleteStores(string $term, int $limit): array
    {
        $response = $this->client->autocomplete([
            'body' => [
                'table'   => $this->idxStores,
                'query'   => $term,
                'options' => [
                    'fuzziness'     => (int) ($this->settings['fuzziness'] ?? 1),
                    'layouts'       => ['it','us'],
                    'append'        => 1,
                    'prepend'       => 1,
                    'expansion_len' => (int) ($this->settings['exp_limit'] ?? 10),
                ],
            ],
        ]);

        $results = [];
        if (!empty($response[0]['data'])) {
            foreach (array_slice($response[0]['data'], 0, $limit) as $row) {
                $results[] = $row['query'];
            }
        }
        return $results;
    }

    /**
     * FULLTEXT Prodotti/Annunci.
     * Restituisce array di item con ['term' => <name>, 'type' => 'ad'|'pr'].
     */
    private function fastSearchAdsProds(string $term, int $limit): array
    {
        $body = [
            'index' => $this->idxProducts,
            'query' => [
                'bool' => [
                    'must' => [[
                        'match' => [
                            '*' => [
                                'query'    => $term,
                                'operator' => 'and',
                                'expand'   => true
                            ]
                        ]
                    ]]
                ]
            ],
            'limit'   => $limit,
            '_source' => ['name','category','sub_category','type']
        ];

        $result = $this->client->search(['body' => $body]);

        $out = [];
        if (isset($result['hits']['hits'])) {
            foreach ($result['hits']['hits'] as $hit) {
                $name = $hit['_source']['name'] ?? '';
                if ($name === '') continue;

                // 'type' vuoto → fallback 'pr'
                $t = $this->normalizeProductType($hit['_source']['type'] ?? null);

                $out[] = ['term' => $name, 'type' => $t];
            }
        }

        // deduplica (term,type)
        $uniq = [];
        foreach ($out as $it) {
            $k = strtolower($it['type'].'|'.$it['term']);
            $uniq[$k] = $it;
        }
        return array_values($uniq);
    }

    /**
     * FULLTEXT Negozi.
     * Restituisce array di item con ['term' => <store_name>, 'type' => 'st'].
     */
    private function fastSearchStores(string $term, int $limit): array
    {
        $body = [
            'index' => $this->idxStores,
            'query' => [
                'bool' => [
                    'must' => [[
                        'match' => [
                            $this->storeField => [
                                'query'    => $term,
                                'operator' => 'and',
                                'expand'   => true
                            ]
                        ]
                    ]]
                ]
            ],
            'limit'   => $limit,
            '_source' => [$this->storeField]
        ];

        $result = $this->client->search(['body' => $body]);

        $out = [];
        if (isset($result['hits']['hits'])) {
            foreach ($result['hits']['hits'] as $hit) {
                $name = $hit['_source'][$this->storeField] ?? '';
                if ($name !== '') $out[] = ['term' => $name, 'type' => 'st'];
            }
        }

        // deduplica (term,type)
        $uniq = [];
        foreach ($out as $it) {
            $k = strtolower($it['type'].'|'.$it['term']);
            $uniq[$k] = $it;
        }
        return array_values($uniq);
    }

    /**
     * Unisce risultati prodotti/annunci e negozi, li ordina per similarità,
     * e restituisce SOLO {term, type} (senza pct).
     *
     * @return array [{term, type}] ordinati
     */
    private function mergeAndRank(string $term, array $prod, array $store, int $limit): array
    {
        $items = [];

        // normalizza prodotti/annunci
        foreach ($prod as $p) {
            if (is_string($p)) {
                $items[] = ['term' => $p, 'type' => 'pr', '_pct' => $this->computePercent($term, $p)];
            } else {
                $candidate = $p['term'] ?? '';
                $type      = $p['type'] ?? 'pr';
                if ($candidate === '') continue;
                $items[] = [
                    'term' => $candidate,
                    'type' => in_array($type, ['ad','pr'], true) ? $type : 'pr',
                    '_pct' => $this->computePercent($term, $candidate),
                ];
            }
        }

        // normalizza negozi
        foreach ($store as $s) {
            if (is_string($s)) {
                $items[] = ['term' => $s, 'type' => 'st', '_pct' => $this->computePercent($term, $s)];
            } else {
                $candidate = $s['term'] ?? '';
                if ($candidate === '') continue;
                $items[] = [
                    'term' => $candidate,
                    'type' => 'st',
                    '_pct' => $this->computePercent($term, $candidate),
                ];
            }
        }

        // Deduplica per (term,type) conservando _pct massimo
        $uniq = [];
        foreach ($items as $it) {
            $k = strtolower($it['type'] . '|' . $it['term']);
            if (!isset($uniq[$k]) || $it['_pct'] > $uniq[$k]['_pct']) {
                $uniq[$k] = $it;
            }
        }
        $items = array_values($uniq);

        // Ordina per _pct desc, poi alfabetico
        usort($items, function ($a, $b) {
            if ($a['_pct'] === $b['_pct']) {
                return strcasecmp($a['term'], $b['term']);
            }
            return ($a['_pct'] > $b['_pct']) ? -1 : 1;
        });

        // Taglia e rimuove il campo interno _pct
        $items = array_slice($items, 0, $limit);
        return array_map(fn($x) => ['term' => $x['term'], 'type' => $x['type']], $items);
    }

    /** Normalizza i type del docstore in ad|pr (fallback pr) */
    private function normalizeProductType(?string $raw): string
    {
        $v = strtolower((string)$raw);
        return match ($v) {
            'advertisement', 'ad', 'ads', 'annuncio', 'annunci' => 'ad',
            'product', 'prod', 'prodotto', 'prodotti'            => 'pr',
            default                                              => 'pr',
        };
    }

    private function norm(string $s): string
    {
        $s = mb_strtolower(trim($s), 'UTF-8');
        $t = @iconv('UTF-8','ASCII//TRANSLIT//IGNORE',$s);
        return $t !== false ? preg_replace('/\s+/', ' ', trim($t)) : $s;
    }

    /**
     * Calcola una percentuale di similarità robusta ai typo brevi (solo per l'ordinamento interno).
     * Usa Levenshtein normalizzato su max(lenA,lenB) e boost se $term è prefisso/sottostringa.
     */
    private function computePercent(string $term, string $candidate): int
    {
        $a = $this->norm($term);
        $b = $this->norm($candidate);
        if ($a === '' || $b === '') return 0;

        if (str_starts_with($b, $a)) return 100;
        if ($a === $b) return 100;

        $lenA = strlen($a);
        $lenB = strlen($b);
        $max  = max($lenA, $lenB);
        $dist = levenshtein($a, $b);
        $pct  = (int) round((1 - ($dist / $max)) * 100);

        if (strpos($b, $a) !== false) $pct = min(100, $pct + 5);
        return max(0, min(100, $pct));
    }
    
    /**
     * Esegue la ricerca completa in modo asincrono (chiamata da WP Cron).
     * Questo metodo viene eseguito DOPO che la risposta è stata inviata al client.
     * 
     * WordPress passa l'array completo come primo parametro.
     *
     * @param array $args Array con: term, token, page, per_page
     * @return void
     */
    public static function executeAsyncFullSearch(array $args = []): void
    {
        $startTime = microtime(true);
        
        // Estrai parametri dall'array
        $term = $args['term'] ?? '';
        $token = $args['token'] ?? '';
        $page = $args['page'] ?? 1;
        $perPage = $args['per_page'] ?? 20;
        
        if (empty($term) || empty($token)) {
            error_log(sprintf(
                "[AsyncFullSearch] ❌ INVALID_ARGS | Term: '%s' | Token: '%s'",
                $term,
                $token
            ));
            return;
        }
        
        // Chiama l'endpoint /fsearch internamente
        $url = rest_url('api/v1/mixed/fsearch');
        $fullUrl = add_query_arg([
            'token' => $token,
            'page' => $page,
            'per_page' => $perPage
        ], $url);
        
        // Questa chiamata avviene in un processo separato (WP Cron)
        // quindi NON rallenta la risposta al client
        $response = wp_remote_get($fullUrl, [
            'timeout' => 30,
            'sslverify' => false,
        ]);
        
        $duration = (microtime(true) - $startTime) * 1000;
        
        if (is_wp_error($response)) {
            error_log(sprintf(
                "[AsyncFullSearch] ❌ FAILED | Token: %s | Error: %s | Time: %.2fms",
                $token,
                $response->get_error_message(),
                $duration
            ));
        } else {
            $statusCode = wp_remote_retrieve_response_code($response);
            $body = wp_remote_retrieve_body($response);
            $dataSize = strlen($body);
            
            error_log(sprintf(
                "[AsyncFullSearch] ✅ COMPLETED | Token: %s | Term: '%s' | " .
                "Status: %d | Time: %.2fms | Size: %d bytes",
                $token,
                $term,
                $statusCode,
                $duration,
                $dataSize
            ));
        }
    }
}
