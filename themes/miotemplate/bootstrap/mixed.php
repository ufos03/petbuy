<?php
/**
 * Mixed Module Bootstrap
 * 
 * Dependency Injection Container e registrazione route REST API per Mixed (Ads + Products).
 * 
 * @package PetBuy
 * @subpackage Mixed
 */

// Autoload delle classi Mixed
require_once __DIR__ . '/../shop/Mixed/MixedRepository.php';
require_once __DIR__ . '/../shop/Mixed/MixedService.php';
require_once __DIR__ . '/../shop/Mixed/MixedController.php';
require_once __DIR__ . '/../shop/Mixed/MixedSearchCache.php';

use App\Mixed\MixedRepository;
use App\Mixed\MixedService;
use App\Mixed\MixedController;
use App\Mixed\MixedSearchCache;

/**
 * Mixed Container - Dependency Injection Container
 * Pattern: Singleton
 */
class MixedContainer {
    
    private static ?MixedContainer $instance = null;
    private ?MixedRepository $repository = null;
    private ?MixedSearchCache $cache = null;
    private ?MixedService $service = null;
    private ?MixedController $controller = null;
    
    /**
     * Singleton instance
     */
    public static function getInstance(): MixedContainer {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    /**
     * Get Repository (lazy loading)
     */
    public static function getRepository(): MixedRepository {
        $instance = self::getInstance();
        if ($instance->repository === null) {
            $instance->repository = new MixedRepository();
        }
        return $instance->repository;
    }
    
    /**
     * Get Cache (lazy loading)
     */
    public static function getCache(): MixedSearchCache {
        $instance = self::getInstance();
        if ($instance->cache === null) {
            $instance->cache = new MixedSearchCache();
        }
        return $instance->cache;
    }
    
    /**
     * Get Service (lazy loading)
     * Dipende da AdvertisementService, ProductService e MixedSearchCache
     */
    public static function getService(): MixedService {
        $instance = self::getInstance();
        if ($instance->service === null) {
            // Recupera services delle dipendenze
            $advertisementService = AdvertisementContainer::getService();
            $productService = ProductContainer::getService();
            
            $instance->service = new MixedService(
                self::getRepository(),
                $advertisementService,
                $productService,
                self::getCache()
            );
        }
        return $instance->service;
    }
    
    /**
     * Get Controller (lazy loading)
     */
    public static function getController(): MixedController {
        $instance = self::getInstance();
        if ($instance->controller === null) {
            $instance->controller = new MixedController(
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
 * Registra le rotte REST API per il mixed (ads + products)
 */
function register_mixed_rest_routes() {
    $controller = MixedContainer::getController();
    
    // GET /api/v1/mixed/read/all
    register_rest_route('api/v1', '/mixed/read/all', [
        'methods'  => 'GET',
        'callback' => [$controller, 'getAll'],
        'permission_callback' => '__return_true',
    ]);
    
    // GET /api/v1/mixed/fsearch - Full Search con cache
    // Chiamata quando l'utente preme INVIO nella Petbuy Search o dal preload
    register_rest_route('api/v1', '/mixed/fsearch', [
        'methods'  => 'GET',
        'callback' => [$controller, 'fullSearch'],
        'permission_callback' => '__return_true',
        'args' => [
            'token' => [
                'required' => true,
                'type' => 'string',
                'description' => 'Token cache (da qsearch) o "0" per query immediata',
            ],
            'search_term' => [
                'required' => false,
                'type' => 'string',
                'description' => 'Termine di ricerca (obbligatorio solo se token=0)',
            ],
            'category' => [
                'required' => false,
                'type' => 'string',
                'description' => 'Filtro categoria',
            ],
            'sub_category' => [
                'required' => false,
                'type' => 'string',
                'description' => 'Filtro sottocategoria',
            ],
            'min_price' => [
                'required' => false,
                'type' => 'number',
                'description' => 'Prezzo minimo',
            ],
            'max_price' => [
                'required' => false,
                'type' => 'number',
                'description' => 'Prezzo massimo',
            ],
            'order_by' => [
                'required' => false,
                'type' => 'string',
                'default' => 'creation_date',
                'description' => 'Campo ordinamento',
            ],
            'order' => [
                'required' => false,
                'type' => 'string',
                'default' => 'DESC',
                'enum' => ['ASC', 'DESC'],
                'description' => 'Direzione ordinamento',
            ],
            'page' => [
                'required' => false,
                'type' => 'integer',
                'default' => 1,
                'minimum' => 1,
                'description' => 'Numero pagina',
            ],
            'per_page' => [
                'required' => false,
                'type' => 'integer',
                'default' => 20,
                'minimum' => 1,
                'maximum' => 100,
                'description' => 'Elementi per pagina',
            ],
        ],
    ]);
}

add_action('rest_api_init', 'register_mixed_rest_routes');

// ============================================================================
// LEGACY COMPATIBILITY FUNCTIONS
// ============================================================================

/**
 * Funzione legacy per retrocompatibilità
 * Mantiene la stessa firma della vecchia get_advertisements_and_products()
 */
if (!function_exists('get_advertisements_and_products')) {
    function get_advertisements_and_products(WP_REST_Request $request) {
        $controller = MixedContainer::getController();
        return $controller->getAll($request);
    }
}
