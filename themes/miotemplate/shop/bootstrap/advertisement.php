<?php

/**
 * Advertisement Module - Bootstrap File
 * 
 * Questo file sostituisce advertisement.php con un'architettura OOP pulita.
 * Inizializza tutte le dipendenze e registra gli endpoint REST.
 */

require_once WP_CONTENT_DIR . "/themes/miotemplate/user_session_manager/user_session_manager.php";
require_once WP_CONTENT_DIR . "/themes/miotemplate/shop/Hooks/aggregated_table_hooks.php";

// Autoload delle classi (se non usi Composer)
spl_autoload_register(function ($class) {
    $prefix = 'App\\Advertisement\\';
    $base_dir = WP_CONTENT_DIR . '/themes/miotemplate/shop/Advertisement/';
    
    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        return;
    }
    
    $relative_class = substr($class, $len);
    $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';
    
    if (file_exists($file)) {
        require $file;
    }
});

use App\Auth\UserSessionManager;
use App\Advertisement\AdvertisementRepository;
use App\Advertisement\AdvertisementValidator;
use App\Advertisement\AdvertisementService;
use App\Advertisement\AdvertisementController;

/**
 * Service Container per Dependency Injection
 */
class AdvertisementContainer
{
    private static $instances = [];

    public static function getUserManager()
    {
        if (!isset(self::$instances['user_manager'])) {
            self::$instances['user_manager'] = new UserSessionManager(
                USM_SECRET_KEY,
                "petbuy.com",
                "https://petbuy-local.ns0.it:8080"
            );
        }
        return self::$instances['user_manager'];
    }

    public static function getRepository()
    {
        if (!isset(self::$instances['repository'])) {
            self::$instances['repository'] = new AdvertisementRepository();
        }
        return self::$instances['repository'];
    }

    public static function getValidator()
    {
        if (!isset(self::$instances['validator'])) {
            self::$instances['validator'] = new AdvertisementValidator();
        }
        return self::$instances['validator'];
    }

    public static function getService()
    {
        if (!isset(self::$instances['service'])) {
            // Crea helper per hooks (funzioni esistenti)
            $hooksHelper = new class {
                public function insert_ad_or_product($hash, $type, $title, $price, $date, $category, $subcategory) {
                    return insert_ad_or_product($hash, $type, $title, $price, $date, $category, $subcategory);
                }
                
                public function update_ad_or_product($hash, $price) {
                    return update_ad_or_product($hash, $price);
                }
                
                public function delete_ad_or_product($hash) {
                    return delete_ad_or_product($hash);
                }
            };

            self::$instances['service'] = new AdvertisementService(
                self::getRepository(),
                self::getValidator(),
                self::getUserManager(),
                $hooksHelper
            );
        }
        return self::$instances['service'];
    }

    public static function getController()
    {
        if (!isset(self::$instances['controller'])) {
            self::$instances['controller'] = new AdvertisementController(
                self::getService(),
                self::getValidator()
            );
        }
        return self::$instances['controller'];
    }
}

/**
 * Registrazione REST API Endpoints
 */
/**
 * Registrazione REST API Endpoints
 * 
 * Endpoint compatibili con le vecchie route per retrocompatibilità
 */
add_action('rest_api_init', function () {
    $controller = AdvertisementContainer::getController();

    // POST /wp-json/api/v1/advertisements/create
    // Compatibile con: create_ad()
    register_rest_route('api/v1', '/advertisements/create', [
        'methods' => 'POST',
        'callback' => [$controller, 'create'],
        'permission_callback' => '__return_true'
    ]);

    // PUT /wp-json/api/v1/advertisements/update
    // Compatibile con: update_ad()
    register_rest_route('api/v1', '/advertisements/update', [
        'methods' => ['PUT', 'POST'], // POST per compatibilità
        'callback' => [$controller, 'update'],
        'permission_callback' => '__return_true'
    ]);

    // DELETE /wp-json/api/v1/advertisements/delete
    // Compatibile con: delete_ad()
    register_rest_route('api/v1', '/advertisements/delete', [
        'methods' => ['DELETE', 'POST'], // POST per compatibilità
        'callback' => [$controller, 'delete'],
        'permission_callback' => '__return_true'
    ]);

    // GET /wp-json/api/v1/advertisements/read/all
    // Compatibile con: get_all_ads()
    register_rest_route('api/v1', '/advertisements/read/all', [
        'methods' => 'GET',
        'callback' => [$controller, 'getAll'],
        'permission_callback' => '__return_true',
        'args' => [
            'category' => ['required' => false, 'type' => 'string'],
            'sub_category' => ['required' => false, 'type' => 'string'],
            'min_price' => ['required' => false, 'type' => 'number'],
            'max_price' => ['required' => false, 'type' => 'number'],
            'sex' => ['required' => false, 'type' => 'string'],
            'gift' => ['required' => false, 'type' => 'string'],
            'order' => ['required' => false, 'type' => 'string', 'default' => 'DESC'],
            'order_by' => ['required' => false, 'type' => 'string', 'default' => 'date'],
            'page' => ['required' => false, 'type' => 'integer', 'default' => 1],
            'per_page' => ['required' => false, 'type' => 'integer', 'default' => 20]
        ]
    ]);

    // GET /wp-json/api/v1/advertisements/read/single
    // Compatibile con: get_single_ad()
    register_rest_route('api/v1', '/advertisements/read/single', [
        'methods' => 'GET',
        'callback' => [$controller, 'getSingle'],
        'permission_callback' => '__return_true',
        'args' => [
            'ad_hash' => ['required' => true, 'type' => 'string']
        ]
    ]);

    // GET /wp-json/api/v1/advertisements/read/user
    // Compatibile con: get_user_ads()
    register_rest_route('api/v1', '/advertisements/read/user', [
        'methods' => 'GET',
        'callback' => [$controller, 'getUserAds'],
        'permission_callback' => '__return_true',
        'args' => [
            'token' => ['required' => true, 'type' => 'string']
        ]
    ]);
});

/**
 * Funzioni di compatibilità legacy (se necessario)
 * 
 * Queste funzioni mantengono la retrocompatibilità con codice esistente
 * che potrebbe chiamare direttamente le vecchie funzioni.
 */

function create_ad(WP_REST_Request $request) {
    return AdvertisementContainer::getController()->create($request);
}

function update_ad(WP_REST_Request $request) {
    return AdvertisementContainer::getController()->update($request);
}

function delete_ad(WP_REST_Request $request) {
    return AdvertisementContainer::getController()->delete($request);
}

function get_all_ads(WP_REST_Request $request) {
    return AdvertisementContainer::getController()->getAll($request);
}

function get_single_ad(WP_REST_Request $request) {
    return AdvertisementContainer::getController()->getSingle($request);
}

function get_user_ads(WP_REST_Request $request) {
    return AdvertisementContainer::getController()->getUserAds($request);
}

/**
 * Helper functions legacy (se usate altrove nel codice)
 */

function get_advertisement_id_helper($hash_record) {
    $repo = AdvertisementContainer::getRepository();
    return $repo->findIdByHash($hash_record);
}

function get_user_advertisement_helper($user_id, $ad_hash) {
    $repo = AdvertisementContainer::getRepository();
    return $repo->findByUserAndHash($user_id, $ad_hash);
}

function build_advertisement_response_helper($raw_data) {
    // Questa funzione è stata deprecata in favore del metodo formatAdvertisement del Service
    // Mantenuta per compatibilità
    if (count($raw_data) < 1) return null;

    $ad = $raw_data[0];
    $images = isset($raw_data[1]) && is_array($raw_data[1]) ? $raw_data[1] : [];
    
    $imageLinks = array_map(function($img) {
        return $img->link ?? '';
    }, $images);

    return [
        'title' => $ad->ad_name ?? '',
        'region' => $ad->ad_state ?? '',
        'province' => $ad->province ?? '',
        'description' => $ad->ad_description ?? '',
        'health' => $ad->health ?? '',
        'has_cites' => $ad->cites ?? 'F',
        'price' => isset($ad->price) ? floatval($ad->price) : 0,
        'sale_price' => isset($ad->sale_price) ? floatval($ad->sale_price) : 0,
        'is_gift' => $ad->gift ?? 'F',
        'on_sale' => $ad->on_sale ?? 'F',
        'category' => $ad->category ?? '',
        'sub_category' => $ad->sub_category ?? '',
        'contact' => $ad->contact ?? '',
        'birth' => $ad->birth ?? '',
        'weight' => isset($ad->animal_weight) ? floatval($ad->animal_weight) : 0,
        'sex' => $ad->sex ?? '',
        'cover' => $ad->link_cover ?? '',
        'date' => $ad->creation_date ?? '',
        'hash' => $ad->advertisement_hash ?? '',
        'images' => $imageLinks
    ];
}
