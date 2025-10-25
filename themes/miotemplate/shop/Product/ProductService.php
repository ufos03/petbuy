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
        
        return [
            'success' => true,
            'message' => 'Prodotto recuperato con successo',
            'data' => $this->formatProduct($product),
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
        
        $productsData = array_map([$this, 'formatProduct'], $products);

        $totalCount = $this->repository->count($sanitizedFilters);
        $totalPages = $pagination['per_page'] > 0
            ? (int) ceil($totalCount / $pagination['per_page'])
            : 1;

        return [
            'success' => true,
            'message' => 'Prodotti recuperati con successo',
            'data' => [
                'status' => 'ok',
                'page' => $pagination['page'],
                'per_page' => $pagination['per_page'],
                'total_items' => $totalCount,
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
        $image = $this->repository->getMainImage($product);
        $gallery = $this->repository->getGalleryImages($product);
        $categories = $this->repository->getCategories($productId);
        $primaryCategory = $categories[0] ?? null;
        $secondaryCategory = $categories[1] ?? null;
        $additionalCategories = array_slice($categories, 2);
        $currency = \get_woocommerce_currency();
        $regularPrice = $product->get_regular_price();
        $price = $product->get_price();
        $salePrice = $product->get_sale_price();
        $dateCreated = $product->get_date_created();
        $summarySource = $product->get_short_description() ?: $product->get_description();
        
        $summary = $summarySource
            ? \wp_trim_words(\wp_strip_all_tags($summarySource), 35, '...')
            : '';

        return [
            'type' => 'product',
            'id' => $productId,
            'slug' => $product->get_slug(),
            'title' => $product->get_name(),
            'summary' => $summary,
            'permalink' => $product->get_permalink(),
            'price' => [
                'regular' => $regularPrice !== '' ? (float) $regularPrice : (float) $price,
                'sale' => $salePrice !== '' ? (float) $salePrice : null,
                'currency' => $currency,
                'is_on_sale' => $product->is_on_sale(),
                'is_gift' => false,
            ],
            'media' => [
                'cover' => $image,
                'gallery' => $gallery,
            ],
            'taxonomy' => [
                'category' => $primaryCategory,
                'sub_category' => $secondaryCategory,
                'additional' => $additionalCategories,
            ],
            'stock' => [
                'status' => $product->get_stock_status(),
                'quantity' => $product->get_stock_quantity(),
            ],
            'location' => [
                'region' => null,
                'province' => null,
            ],
            'meta' => [
                'average_rating' => (float) $product->get_average_rating(),
                'product_type' => $product->get_type(),
                'date_published' => $dateCreated ? $dateCreated->date('c') : null,
                'hash' => null,
            ],
            'actions' => [
                'add_to_cart_url' => $product->add_to_cart_url(),
                'share_url' => $product->get_permalink(),
            ],
        ];
    }
}
