<?php
/**
 * Product Module Bootstrap
 * 
 * Dependency Injection Container e registrazione route REST API per Products.
 * 
 * @package PetBuy
 * @subpackage Product
 */

// Autoload delle classi Product
require_once __DIR__ . '/../shop/Product/ProductRepository.php';
require_once __DIR__ . '/../shop/Product/ProductValidator.php';
require_once __DIR__ . '/../shop/Product/ProductService.php';
require_once __DIR__ . '/../shop/Product/ProductController.php';

use App\Product\ProductRepository;
use App\Product\ProductValidator;
use App\Product\ProductService;
use App\Product\ProductController;

/**
 * Product Container - Dependency Injection Container
 * Pattern: Singleton
 */
class ProductContainer {
    
    private static ?ProductContainer $instance = null;
    private ?ProductRepository $repository = null;
    private ?ProductValidator $validator = null;
    private ?ProductService $service = null;
    private ?ProductController $controller = null;
    
    /**
     * Singleton instance
     */
    public static function getInstance(): ProductContainer {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    /**
     * Get Repository (lazy loading)
     */
    public static function getRepository(): ProductRepository {
        $instance = self::getInstance();
        if ($instance->repository === null) {
            $instance->repository = new ProductRepository();
        }
        return $instance->repository;
    }
    
    /**
     * Get Validator (lazy loading)
     */
    public static function getValidator(): ProductValidator {
        $instance = self::getInstance();
        if ($instance->validator === null) {
            $instance->validator = new ProductValidator();
        }
        return $instance->validator;
    }
    
    /**
     * Get Service (lazy loading)
     */
    public static function getService(): ProductService {
        $instance = self::getInstance();
        if ($instance->service === null) {
            $instance->service = new ProductService(
                self::getRepository(),
                self::getValidator()
            );
        }
        return $instance->service;
    }
    
    /**
     * Get Controller (lazy loading)
     */
    public static function getController(): ProductController {
        $instance = self::getInstance();
        if ($instance->controller === null) {
            $instance->controller = new ProductController(
                self::getService()
            );
        }
        return $instance->controller;
    }
    
    private function __construct() {}
    private function __clone() {}
}

// ============================================================================
// REST API ROUTES REGISTRATION
// ============================================================================

/**
 * Registra le rotte REST API per i prodotti
 */
function register_product_rest_routes() {
    $controller = ProductContainer::getController();
    
    // GET /api/v1/products/read/all
    register_rest_route('api/v1', '/products/read/all', [
        'methods'  => 'GET',
        'callback' => [$controller, 'getAll'],
        'permission_callback' => '__return_true',
    ]);
    
    // GET /api/v1/products/read/single
    register_rest_route('api/v1', '/products/read/single', [
        'methods'  => 'GET',
        'callback' => [$controller, 'getSingle'],
        'permission_callback' => '__return_true',
        'args' => [
            'product_id' => [
                'required' => true,
                'validate_callback' => function($param) {
                    return is_numeric($param);
                }
            ]
        ]
    ]);
}

add_action('rest_api_init', 'register_product_rest_routes');

// ============================================================================
// LEGACY COMPATIBILITY FUNCTIONS
// ============================================================================

/**
 * Funzione legacy per retrocompatibilità
 * Mantiene la stessa firma della vecchia get_products()
 */
if (!function_exists('get_products')) {
    function get_products($filters = [], $order = 'DESC', $order_by = 'date', $page = 1, $per_page = 3) {
        $service = ProductContainer::getService();
        $result = $service->getAllProducts($filters, $order_by, $order, $page, $per_page);
        
        if ($result['success']) {
            return $result['data'];
        }
        
        return [
            'page' => $page,
            'per_page' => $per_page,
            'total_products' => 0,
            'total_pages' => 0,
            'content' => []
        ];
    }
}

/**
 * Funzione legacy per retrocompatibilità
 */
if (!function_exists('get_single_product')) {
    function get_single_product($product_id) {
        if (!isset($product_id)) {
            return [];
        }
        
        $service = ProductContainer::getService();
        $result = $service->getProduct($product_id);
        
        return $result['success'] ? $result['data'] : [];
    }
}

/**
 * Funzione legacy per retrocompatibilità
 */
if (!function_exists('build_product_structure')) {
    function build_product_structure($product_object, $product_id = false) {
        if ($product_id == false) {
            $product_id = $product_object->get_id();
        }
        
        $service = ProductContainer::getService();
        $result = $service->getProduct($product_id);
        
        return $result['success'] ? $result['data'] : [];
    }
}
