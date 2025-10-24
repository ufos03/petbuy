<?php
/**
 * Product Controller
 * 
 * REST API Controller Layer per i prodotti.
 * Gestisce solo HTTP Request/Response, delega la logica al Service.
 * 
 * @package PetBuy
 * @subpackage Product
 */

namespace App\Product;

use WP_REST_Request;
use WP_REST_Response;

class ProductController {
    
    private ProductService $service;
    
    /**
     * Constructor
     * 
     * @param ProductService $service Service per business logic
     */
    public function __construct(ProductService $service) {
        $this->service = $service;
    }
    
    /**
     * GET /products/read/all
     * Recupera tutti i prodotti con filtri e paginazione
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
        ];
        
        // Estrai parametri di ordinamento e paginazione
        $orderBy = $params['order_by'] ?? 'date';
        $order = $params['order'] ?? 'DESC';
        $page = isset($params['page']) ? intval($params['page']) : 1;
        $perPage = isset($params['per_page']) ? intval($params['per_page']) : 6;
        
        // Chiama service
        $result = $this->service->getAllProducts($filters, $orderBy, $order, $page, $perPage);
        
        // Return HTTP response
        return new WP_REST_Response($result['data'], $result['code']);
    }
    
    /**
     * GET /products/read/single
     * Recupera un singolo prodotto
     * 
     * @param WP_REST_Request $request Richiesta REST
     * @return WP_REST_Response Response HTTP
     */
    public function getSingle(WP_REST_Request $request): WP_REST_Response {
        $productId = $request->get_param('product_id');
        
        if (!$productId) {
            return new WP_REST_Response([
                'success' => false,
                'message' => 'Product ID è richiesto'
            ], 400);
        }
        
        $result = $this->service->getProduct(intval($productId));
        
        return new WP_REST_Response($result['data'], $result['code']);
    }
}
