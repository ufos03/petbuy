<?php
/**
 * Mixed Controller
 * 
 * REST API Controller Layer per l'API aggregata.
 * Gestisce solo HTTP Request/Response, delega la logica al Service.
 * 
 * @package PetBuy
 * @subpackage Mixed
 */

namespace App\Mixed;

use WP_REST_Request;
use WP_REST_Response;

class MixedController {
    
    private MixedService $service;
    
    /**
     * Constructor
     * 
     * @param MixedService $service Service per business logic
     */
    public function __construct(MixedService $service) {
        $this->service = $service;
    }
    
    /**
     * GET /mixed/read/all
     * Recupera tutti gli items (advertisements + products) con filtri e paginazione
     * 
     * @param WP_REST_Request $request Richiesta REST
     * @return WP_REST_Response Response HTTP
     */
    public function getAll(WP_REST_Request $request): WP_REST_Response {
        $params = $request->get_params();
        
        // Estrai filtri
        $filters = [
            'min_price' => $params['min_price'] ?? null,
            'max_price' => $params['max_price'] ?? null,
            'category' => $params['category'] ?? null,
            'sub_category' => $params['sub_category'] ?? null,
            'creation_date' => $params['creation_date'] ?? null,
        ];
        
        // Estrai parametri di ordinamento e paginazione
        $orderBy = $params['order_by'] ?? 'creation_date';
        $order = $params['order'] ?? 'DESC';
        $page = isset($params['page']) ? intval($params['page']) : 1;
        $perPage = isset($params['per_page']) ? intval($params['per_page']) : 6;
        
        // Chiama service
        $result = $this->service->getMixedItems($filters, $orderBy, $order, $page, $perPage);
        
        // Return HTTP response
        return new WP_REST_Response($result['data'], $result['code']);
    }
    
    /**
     * GET /mixed/fsearch - Full Search
     * 
     * Ricerca completa chiamata quando l'utente preme INVIO o dal preload.
     * 
     * Comportamento:
     * - Se token valido: Recupera search_term dalla cache qsearch e restituisce risultati
     * - Se token = "0" + search_term: Esegue query immediata
     * 
     * Query Parameters:
     * - token (string, required): Token da qsearch o "0" per query immediata
     * - search_term (string, optional): Richiesto solo se token=0
     * - category (string, optional): Filtro categoria
     * - sub_category (string, optional): Filtro sottocategoria
     * - min_price (float, optional): Prezzo minimo
     * - max_price (float, optional): Prezzo massimo
     * - order_by (string, optional): Campo ordinamento (default: creation_date)
     * - order (string, optional): Direzione (ASC/DESC, default: DESC)
     * - page (int, optional): Numero pagina (default: 1)
     * - per_page (int, optional): Elementi per pagina (default: 20)
     * 
     * @param WP_REST_Request $request Richiesta REST
     * @return WP_REST_Response Response HTTP
     */
    public function fullSearch(WP_REST_Request $request): WP_REST_Response {
        $params = $request->get_params();
        
        // Token è sempre obbligatorio
        $token = $params['token'] ?? '';
        
        if ($token === '') {
            return new WP_REST_Response([
                'success' => false,
                'message' => 'Parametro token richiesto',
                'data' => null
            ], 400);
        }
        
        // Se token=0, search_term è obbligatorio
        if ($token === '0') {
            $searchTerm = $params['search_term'] ?? '';
            if (empty($searchTerm)) {
                return new WP_REST_Response([
                    'success' => false,
                    'message' => 'Parametro search_term richiesto quando token=0',
                    'data' => null
                ], 400);
            }
        } else {
            // Token valido: search_term opzionale (verrà recuperato dalla cache)
            $searchTerm = $params['search_term'] ?? null;
        }
        
        // Estrai filtri
        $filters = [
            'min_price' => $params['min_price'] ?? null,
            'max_price' => $params['max_price'] ?? null,
            'category' => $params['category'] ?? null,
            'sub_category' => $params['sub_category'] ?? null,
        ];
        
        // Parametri di ordinamento e paginazione
        $orderBy = $params['order_by'] ?? 'creation_date';
        $order = $params['order'] ?? 'DESC';
        $page = isset($params['page']) ? intval($params['page']) : 1;
        $perPage = isset($params['per_page']) ? intval($params['per_page']) : 20;
        
        // Chiama service per full search
        $result = $this->service->fullSearch(
            $searchTerm,
            $token,
            $filters,
            $orderBy,
            $order,
            $page,
            $perPage
        );
        
        // Return HTTP response
        return new WP_REST_Response($result['data'], $result['code']);
    }
}
