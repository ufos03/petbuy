<?php
/**
 * Product Service
 * 
 * Business Logic Layer per i prodotti.
 * Orchestrazione tra Repository e Validator, formattazione output.
 * Indipendente da WordPress REST API.
 * 
 * @package PetBuy
 * @subpackage Product
 */

namespace App\Product;

use WC_Product;

class ProductService {
    
    private ProductRepository $repository;
    private ProductValidator $validator;
    
    /**
     * Constructor
     * 
     * @param ProductRepository $repository Repository per data access
     * @param ProductValidator $validator Validator per validazione input
     */
    public function __construct(ProductRepository $repository, ProductValidator $validator) {
        $this->repository = $repository;
        $this->validator = $validator;
    }
    
    /**
     * Recupera un singolo prodotto
     * 
     * @param int $productId ID del prodotto
     * @return array Response standardizzato
     */
    public function getProduct(int $productId): array {
        // Validazione ID
        $validation = $this->validator->validateProductId($productId);
        if (!$validation['valid']) {
            return [
                'success' => false,
                'message' => 'Validazione fallita',
                'errors' => $validation['errors'],
                'data' => null,
                'code' => 400
            ];
        }
        
        // Recupera prodotto
        $product = $this->repository->findById($productId);
        
        if (!$product) {
            return [
                'success' => false,
                'message' => 'Prodotto non trovato',
                'data' => null,
                'code' => 404
            ];
        }
        
        // Formatta output
        $productData = $this->formatProduct($product);
        
        return [
            'success' => true,
            'message' => 'Prodotto recuperato con successo',
            'data' => $productData,
            'code' => 200
        ];
    }
    
    /**
     * Recupera tutti i prodotti con filtri, ordinamento e paginazione
     * 
     * @param array $filters Filtri da applicare
     * @param string $orderBy Campo ordinamento
     * @param string $order Direzione ordinamento
     * @param int $page Numero pagina
     * @param int $perPage Elementi per pagina
     * @return array Response standardizzato con paginazione
     */
    public function getAllProducts(
        array $filters = [], 
        string $orderBy = 'date', 
        string $order = 'DESC', 
        int $page = 1, 
        int $perPage = 6
    ): array {
        // Validazione filtri
        $filterValidation = $this->validator->validateFilters($filters);
        if (!$filterValidation['valid']) {
            return [
                'success' => false,
                'message' => 'Filtri non validi',
                'errors' => $filterValidation['errors'],
                'data' => null,
                'code' => 400
            ];
        }
        
        $sanitizedFilters = $filterValidation['sanitized'];
        
        // Validazione ordinamento
        $ordering = $this->validator->validateOrdering($orderBy, $order, 'date', 'DESC');
        
        // Validazione paginazione
        $pagination = $this->validator->validatePagination($page, $perPage, 6);
        
        // Recupera prodotti
        $products = $this->repository->findAll(
            $sanitizedFilters, 
            $ordering['order_by'], 
            $ordering['order'], 
            $pagination['page'], 
            $pagination['per_page']
        );
        
        // Formatta prodotti
        $productsData = [];
        foreach ($products as $product) {
            $productsData[] = $this->formatProduct($product);
        }
        
        // Conta totale
        $totalCount = $this->repository->count($sanitizedFilters);
        $totalPages = $pagination['per_page'] > 0 
            ? ceil($totalCount / $pagination['per_page']) 
            : 1;
        
        return [
            'success' => true,
            'message' => 'Prodotti recuperati con successo',
            'data' => [
                'page' => $pagination['page'],
                'per_page' => $pagination['per_page'],
                'total_products' => $totalCount,
                'total_pages' => $totalPages,
                'content' => $productsData
            ],
            'code' => 200
        ];
    }
    
    /**
     * Formatta un prodotto WooCommerce in array strutturato
     * 
     * @param WC_Product $product Oggetto prodotto WooCommerce
     * @return array Prodotto formattato
     */
    private function formatProduct(WC_Product $product): array {
        $productId = $product->get_id();
        
        return [
            'id' => $productId,
            'name' => $product->get_name(),
            'price' => (float)$product->get_price(),
            'sale_price' => (float)$product->get_sale_price(),
            'is_on_sale' => $product->is_on_sale(),
            'permalink' => $product->get_permalink(),
            'image' => $this->repository->getMainImage($product),
            'categories' => $this->repository->getCategories($productId),
            'average_rating' => $product->get_average_rating(),
            'stock_status' => $product->is_in_stock() ? 'instock' : 'outofstock',
            'product_type' => $product->get_type(),
            'add_to_cart_url' => $product->add_to_cart_url(),
        ];
    }
}
