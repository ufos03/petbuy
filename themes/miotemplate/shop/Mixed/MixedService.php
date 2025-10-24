<?php
/**
 * Mixed Service
 * 
 * Business Logic Layer per l'API aggregata (advertisements + products).
 * Coordina tra MixedRepository, AdvertisementService e ProductService.
 * 
 * @package PetBuy
 * @subpackage Mixed
 */

namespace App\Mixed;

use App\Advertisement\AdvertisementService;
use App\Product\ProductService;

class MixedService {
    
    private MixedRepository $repository;
    private AdvertisementService $advertisementService;
    private ProductService $productService;
    private MixedSearchCache $cache;
    
    private const ALLOWED_ORDER_BY = ['price', 'creation_date', 'category', 'sub_category'];
    private const ALLOWED_ORDER = ['ASC', 'DESC'];
    
    /**
     * Constructor
     * 
     * @param MixedRepository $repository Repository per tabella aggregata
     * @param AdvertisementService $advertisementService Service per annunci
     * @param ProductService $productService Service per prodotti
     * @param MixedSearchCache $cache Cache per ricerche
     */
    public function __construct(
        MixedRepository $repository,
        AdvertisementService $advertisementService,
        ProductService $productService,
        MixedSearchCache $cache
    ) {
        $this->repository = $repository;
        $this->advertisementService = $advertisementService;
        $this->productService = $productService;
        $this->cache = $cache;
    }
    
    /**
     * Recupera items misti (advertisements + products) con filtri e paginazione
     * 
     * @param array $filters Filtri da applicare
     * @param string $orderBy Campo ordinamento
     * @param string $order Direzione ordinamento
     * @param int $page Numero pagina
     * @param int $perPage Elementi per pagina
     * @return array Response standardizzato
     */
    public function getMixedItems(
        array $filters = [],
        string $orderBy = 'creation_date',
        string $order = 'DESC',
        int $page = 1,
        int $perPage = 6
    ): array {
        // Validazione e sanitizzazione
        $sanitizedFilters = $this->sanitizeFilters($filters);
        $validatedOrdering = $this->validateOrdering($orderBy, $order);
        $validatedPagination = $this->validatePagination($page, $perPage);
        
        // Calcola offset
        $offset = ($validatedPagination['page'] - 1) * $validatedPagination['per_page'];
        
        // Recupera items dalla tabella aggregata
        $items = $this->repository->findAll(
            $sanitizedFilters,
            $validatedOrdering['order_by'],
            $validatedOrdering['order'],
            $offset,
            $validatedPagination['per_page']
        );
        
        // Recupera dettagli completi per ogni item
        $combined = [];
        foreach ($items as $item) {
            if ($item->type === 'advertisement') {
                $detailResult = $this->advertisementService->getAdvertisement($item->item_id);
                if ($detailResult['success']) {
                    $itemData = $detailResult['data'];
                    $itemData['type'] = 'advertisement';
                    $combined[] = $itemData;
                }
            } elseif ($item->type === 'product') {
                $detailResult = $this->productService->getProduct((int)$item->item_id);
                if ($detailResult['success']) {
                    $itemData = $detailResult['data'];
                    $itemData['type'] = 'product';
                    $combined[] = $itemData;
                }
            }
        }
        
        // Conta totale
        $totalCount = $this->repository->count($sanitizedFilters);
        $totalPages = $validatedPagination['per_page'] > 0 
            ? ceil($totalCount / $validatedPagination['per_page']) 
            : 1;
        
        return [
            'success' => true,
            'message' => 'Items recuperati con successo',
            'data' => [
                'page' => $validatedPagination['page'],
                'per_page' => $validatedPagination['per_page'],
                'total_items' => $totalCount,
                'total_pages' => $totalPages,
                'content' => $combined
            ],
            'code' => 200
        ];
    }
    
    /**
     * Sanitizza i filtri
     * 
     * @param array $filters Filtri da sanitizzare
     * @return array Filtri sanitizzati
     */
    private function sanitizeFilters(array $filters): array {
        $sanitized = [];
        
        if (isset($filters['min_price'])) {
            $sanitized['min_price'] = floatval($filters['min_price']);
        }
        if (isset($filters['max_price'])) {
            $sanitized['max_price'] = floatval($filters['max_price']);
        }
        if (isset($filters['category'])) {
            $sanitized['category'] = sanitize_text_field($filters['category']);
        }
        if (isset($filters['sub_category'])) {
            $sanitized['sub_category'] = sanitize_text_field($filters['sub_category']);
        }
        if (isset($filters['creation_date'])) {
            $sanitized['creation_date'] = sanitize_text_field($filters['creation_date']);
        }
        
        return $sanitized;
    }
    
    /**
     * Valida parametri di ordinamento
     * 
     * @param string $orderBy Campo ordinamento
     * @param string $order Direzione ordinamento
     * @return array Parametri validati
     */
    private function validateOrdering(string $orderBy, string $order): array {
        $orderBy = strtolower(trim($orderBy));
        $order = strtoupper(trim($order));
        
        if (!in_array($orderBy, self::ALLOWED_ORDER_BY)) {
            $orderBy = 'creation_date';
        }
        
        if (!in_array($order, self::ALLOWED_ORDER)) {
            $order = 'DESC';
        }
        
        return [
            'order_by' => $orderBy,
            'order' => $order
        ];
    }
    
    /**
     * Valida parametri di paginazione
     * 
     * @param int $page Numero pagina
     * @param int $perPage Elementi per pagina
     * @return array Parametri validati
     */
    private function validatePagination(int $page, int $perPage): array {
        $page = max(1, $page);
        $perPage = max(1, min($perPage, 100));
        
        if ($perPage <= 0) {
            $perPage = 6;
        }
        
        return [
            'page' => $page,
            'per_page' => $perPage
        ];
    }
    
    /**
     * Full Search - Ricerca completa con cache
     * 
     * Chiamata quando l'utente preme INVIO nella Petbuy Search o dal preload.
     * 
     * Logica:
     * - Se token = "0" + searchTerm: Esegue query immediata
     * - Se token valido: Recupera searchTerm dalla cache qsearch e restituisce risultati
     * 
     * @param string|null $searchTerm Termine di ricerca (null se da recuperare da cache)
     * @param string $token Token cache (generato da qsearch) o "0" per immediato
     * @param array $filters Filtri aggiuntivi
     * @param string $orderBy Campo ordinamento
     * @param string $order Direzione ordinamento
     * @param int $page Numero pagina
     * @param int $perPage Elementi per pagina
     * @return array Response standardizzato
     */
    public function fullSearch(
        ?string $searchTerm,
        string $token,
        array $filters = [],
        string $orderBy = 'creation_date',
        string $order = 'DESC',
        int $page = 1,
        int $perPage = 20
    ): array {
        $startTime = microtime(true);
        
        // Token "0" richiede searchTerm esplicito
        if ($token === '0') {
            if (empty($searchTerm)) {
                error_log("[FullSearch] ❌ VALIDATION | Token: 0 | No search term provided");
                return [
                    'success' => false,
                    'message' => 'Termine di ricerca richiesto per token=0',
                    'data' => null,
                    'code' => 400
                ];
            }
            
            error_log(sprintf(
                "[FullSearch] 🔍 IMMEDIATE | Token: 0 | Term: '%s' | Page: %d",
                $searchTerm,
                $page
            ));
            
            return $this->performSearch(trim($searchTerm), $filters, $orderBy, $order, $page, $perPage);
        }
        
        // Token valido: recupera searchTerm dalla cache qsearch
        $qsearchCacheKey = "qsearch_token_{$token}";
        $cacheCheckStart = microtime(true);
        $qsearchData = wp_cache_get($qsearchCacheKey, 'petbuy_user_search');
        $cacheCheckDuration = (microtime(true) - $cacheCheckStart) * 1000;
        
        if ($qsearchData && isset($qsearchData['term'])) {
            $searchTerm = $qsearchData['term'];
            error_log(sprintf(
                "[FullSearch] ✅ TERM_FOUND | Token: %s | Term: '%s' | Cache Check: %.2fms",
                $token,
                $searchTerm,
                $cacheCheckDuration
            ));
        } elseif (empty($searchTerm)) {
            error_log(sprintf(
                "[FullSearch] ⚠️ TERM_MISSING | Token: %s | Cache Check: %.2fms",
                $token,
                $cacheCheckDuration
            ));
            
            return [
                'success' => false,
                'message' => 'Token non trovato o scaduto. Search term richiesto.',
                'data' => null,
                'code' => 404
            ];
        }
        
        $searchTerm = trim($searchTerm);
        
        // Verifica se risultati già in cache (salvati dal preload)
        $cacheHitCheckStart = microtime(true);
        $hasCache = $this->cache->has($token);
        $cacheHitCheckDuration = (microtime(true) - $cacheHitCheckStart) * 1000;
        
        if ($hasCache) {
            $cacheGetStart = microtime(true);
            $cachedData = $this->cache->get($token);
            $cacheGetDuration = (microtime(true) - $cacheGetStart) * 1000;
            
            $totalDuration = (microtime(true) - $startTime) * 1000;
            
            error_log(sprintf(
                "[FullSearch] 💾 CACHE_HIT | Token: %s | Term: '%s' | " .
                "Check: %.2fms | Get: %.2fms | Total: %.2fms",
                $token,
                $searchTerm,
                $cacheHitCheckDuration,
                $cacheGetDuration,
                $totalDuration
            ));
            
            return [
                'success' => true,
                'message' => 'Risultati recuperati da cache',
                'data' => $cachedData,
                'cached' => true,
                'token' => $token,
                'code' => 200,
                'debug' => [
                    'total_ms' => round($totalDuration, 2),
                    'cache_hit' => true
                ]
            ];
        }
        
        // Cache miss - Non dovrebbe succedere se qsearch ha funzionato correttamente
        error_log(sprintf(
            "[FullSearch] ⚠️ CACHE_MISS | Token: %s | Term: '%s' | Check: %.2fms | Performing query...",
            $token,
            $searchTerm,
            $cacheHitCheckDuration
        ));
        
        $queryStart = microtime(true);
        $result = $this->performSearch($searchTerm, $filters, $orderBy, $order, $page, $perPage);
        $queryDuration = (microtime(true) - $queryStart) * 1000;
        
        $totalDuration = (microtime(true) - $startTime) * 1000;
        
        if ($result['success']) {
            // Salva in cache per prossime richieste
            $cacheSaveStart = microtime(true);
            $this->cache->set($token, $result['data']);
            $cacheSaveDuration = (microtime(true) - $cacheSaveStart) * 1000;
            
            $result['cached'] = false;
            $result['token'] = $token;
            $result['message'] = 'Cache miss - risultati calcolati e salvati in cache';
            $result['debug'] = [
                'total_ms' => round($totalDuration, 2),
                'query_ms' => round($queryDuration, 2),
                'cache_save_ms' => round($cacheSaveDuration, 2),
                'cache_hit' => false
            ];
            
            error_log(sprintf(
                "[FullSearch] 🔍 QUERY_DONE | Token: %s | Query: %.2fms | CacheSave: %.2fms | Total: %.2fms | Results: %d",
                $token,
                $queryDuration,
                $cacheSaveDuration,
                $totalDuration,
                $result['data']['total_items'] ?? 0
            ));
        }
        
        return $result;
    }
    
    /**
     * Esegue la ricerca effettiva
     * 
     * @param string $searchTerm Termine di ricerca
     * @param array $filters Filtri
     * @param string $orderBy Campo ordinamento
     * @param string $order Direzione
     * @param int $page Pagina
     * @param int $perPage Per pagina
     * @return array Risultati
     */
    private function performSearch(
        string $searchTerm,
        array $filters,
        string $orderBy,
        string $order,
        int $page,
        int $perPage
    ): array {
        // Sanitizza e valida
        $sanitizedFilters = $this->sanitizeFilters($filters);
        $validatedOrdering = $this->validateOrdering($orderBy, $order);
        $validatedPagination = $this->validatePagination($page, $perPage);
        
        $offset = ($validatedPagination['page'] - 1) * $validatedPagination['per_page'];
        
        // Ricerca nella tabella aggregata
        $items = $this->repository->search(
            $searchTerm,
            $sanitizedFilters,
            $validatedOrdering['order_by'],
            $validatedOrdering['order'],
            $offset,
            $validatedPagination['per_page']
        );
        
        // Recupera dettagli completi per ogni item
        $combined = [];
        foreach ($items as $item) {
            if ($item->type === 'advertisement') {
                $detailResult = $this->advertisementService->getAdvertisement($item->item_id);
                if ($detailResult['success']) {
                    $itemData = $detailResult['data'];
                    $itemData['type'] = 'advertisement';
                    $itemData['relevance_score'] = $this->calculateRelevance($searchTerm, $item->name);
                    $combined[] = $itemData;
                }
            } elseif ($item->type === 'product') {
                $detailResult = $this->productService->getProduct((int)$item->item_id);
                if ($detailResult['success']) {
                    $itemData = $detailResult['data'];
                    $itemData['type'] = 'product';
                    $itemData['relevance_score'] = $this->calculateRelevance($searchTerm, $item->name);
                    $combined[] = $itemData;
                }
            }
        }
        
        // Conta totale
        $totalCount = $this->repository->countSearch($searchTerm, $sanitizedFilters);
        $totalPages = $validatedPagination['per_page'] > 0 
            ? ceil($totalCount / $validatedPagination['per_page']) 
            : 1;
        
        return [
            'success' => true,
            'message' => 'Ricerca completata',
            'data' => [
                'search_term' => $searchTerm,
                'page' => $validatedPagination['page'],
                'per_page' => $validatedPagination['per_page'],
                'total_items' => $totalCount,
                'total_pages' => $totalPages,
                'content' => $combined
            ],
            'code' => 200
        ];
    }
    
    /**
     * Calcola punteggio di rilevanza (0-100)
     * 
     * @param string $searchTerm Termine cercato
     * @param string $itemName Nome item
     * @return int Score 0-100
     */
    private function calculateRelevance(string $searchTerm, string $itemName): int {
        $searchLower = strtolower($searchTerm);
        $nameLower = strtolower($itemName);
        
        // Corrispondenza esatta = 100
        if ($nameLower === $searchLower) {
            return 100;
        }
        
        // Inizia con il termine = 90
        if (strpos($nameLower, $searchLower) === 0) {
            return 90;
        }
        
        // Contiene il termine = 70
        if (strpos($nameLower, $searchLower) !== false) {
            return 70;
        }
        
        // Similar text (Levenshtein-like)
        similar_text($searchLower, $nameLower, $percent);
        return (int)$percent;
    }
}
