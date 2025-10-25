<?php

/*require_once WP_CONTENT_DIR . '/registrazione/decode.php';
require_once WP_CONTENT_DIR . '/registrazione/encode.php';
require_once WP_CONTENT_DIR . '/themes/miotemplate/email_pt_user.php';
require_once WP_CONTENT_DIR . '/themes/miotemplate/register_pt_user.php';
require_once WP_CONTENT_DIR . '/themes/miotemplate/login_pt_user.php';
require_once WP_CONTENT_DIR . '/themes/miotemplate/contest.php';
require_once WP_CONTENT_DIR . '/themes/miotemplate/captcha.php';
require_once WP_CONTENT_DIR . '/themes/miotemplate/two_fa.php';*/

// ============================================================================
// OOP MODULES - New Clean Architecture
// ============================================================================
require_once WP_CONTENT_DIR . '/themes/miotemplate/shop/bootstrap/advertisement.php';
require_once WP_CONTENT_DIR . '/themes/miotemplate/shop/bootstrap/product.php';
require_once WP_CONTENT_DIR . '/themes/miotemplate/shop/bootstrap/mixed.php';
require_once WP_CONTENT_DIR . '/themes/miotemplate/shop/Category/bootstrap.php';

// Legacy files (DEPRECATED - kept for reference only)
// require_once WP_CONTENT_DIR . '/themes/miotemplate/advertisement.php';
// require_once WP_CONTENT_DIR . '/themes/miotemplate/products.php';
// require_once WP_CONTENT_DIR . '/themes/miotemplate/advertisements_and_products/api.php';

require_once WP_CONTENT_DIR . '/themes/miotemplate/user_session_manager/cron_job_tokens.php';

//start();


/*add_action("rest_api_init", "register_api");
add_action("rest_api_init", "encode_api");
add_action("rest_api_init", "verify_email_api");
add_action("rest_api_init", "send_email_verify_api");
add_action("rest_api_init", "login_user_api");
add_action("rest_api_init", "logout_user_api");
add_action("rest_api_init", "username_exists_api");
add_action("rest_api_init", "email_exists_api");
add_action("rest_api_init", "create_new_post_contest_api");
add_action("rest_api_init", "get_contest_posts");
add_action("rest_api_init", "like_post");
add_action("rest_api_init", "search_posts");
add_action("rest_api_init", "captcha");
add_action("rest_api_init", "verify_pi");
add_action("rest_api_init", "twofa");
add_action("rest_api_init", "send_email_reset_password_api");
add_action("rest_api_init", "update_password_api"); */

// ====================================================================
// Advertisement API - Now registered in advertisement_new.php
// These old registrations are kept commented for reference
// ====================================================================
// add_action("rest_api_init", "new_advertisement_api");
// add_action("rest_api_init", "update_advertisement_api");
// add_action("rest_api_init", "delete_advertisement_api");
// add_action("rest_api_init", "get_single_advertisement_api");
// add_action("rest_api_init", "get_user_advertisement_api");
// add_action("rest_api_init", "get_advertisement_api");

add_action("rest_api_init", "get_advertisements_and_products_api");
add_action("rest_api_init", "get_products_api");


//add_action( 'session_cleaner', 'session_cleaner_func' ); //da rifare: affidarsi crono di php

// Su locale non funziona GZIP, attivare su server di produzione

/*add_filter('rest_pre_serve_request', function($served, $result, $request, $server) {

    if (strpos($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip') !== false) {

        header('Content-Encoding: gzip');

        $data = $result->get_data();
        $json = json_encode($data);
        $compressed = gzencode($json);

        header('Content-Length: ' . strlen($compressed));
        echo $compressed;
        return true;
    }
    return false;
}, 10, 4);*/

function preload_images( $hints, $relation_type ) {
    if ( 'preload' === $relation_type ) {
        // Array delle immagini da pre-caricare
        $immagini_preload = array(
            'https://petbuy-local.ns0.it:8080/wp-content/uploads/2024/11/cropped-petbuy-favicon-300x146.webp',
            'https://petbuy-local.ns0.it:8080/wp-content/uploads/2024/11/petbuy-logo-no-dog-e1732843731858.webp',
        );

        foreach ( $immagini_preload as $immagine ) {
            $hints[] = array(
                'href'        => $immagine,
                'as'          => 'image',
                'type'        => 'image/webp',
                'crossorigin' => 'anonymous', // Modifica se necessario
            );
        }
    }
    return $hints;
}
add_filter( 'wp_resource_hints', 'preload_images', 2, 2 );



/*function register_api()
{
	register_rest_route("api/v1", "createuserpt", array(
		'methods' => "GET",
		'callback' => 'createuserpt',
	));
}

function encode_api()
{
	register_rest_route("api/v1", "encodedata", array(
		'methods' => WP_REST_Server::CREATABLE,
		'callback' => 'encodedata',
	));
}

function verify_email_api()
{
	register_rest_route("api/v1", "verifyemail", array(
		'methods' => WP_REST_Server::READABLE,
		'callback' => 'verifyemail',
	));
}

function send_email_verify_api()
{
	register_rest_route("api/v1", "sendemailapi", array(
		'methods' => WP_REST_Server::READABLE,
		'callback' => 'sendemailapi',
	));
}

function login_user_api()
{
	register_rest_route("api/v1", "loginuserpt", array(
		'methods' => WP_REST_Server::CREATABLE,
		'callback' => 'loginuserpt',
	));
}

function logout_user_api()
{
	register_rest_route("api/v1", "logoutuserpt", array(
		'methods' => WP_REST_Server::CREATABLE,
		'callback' => 'logoutuserpt',
	));
}

function username_exists_api()
{
	register_rest_route("api/v1", "usernamexists", array(
		'methods' => WP_REST_Server::CREATABLE,
		'callback' => 'usernamexists',
	));
}

function email_exists_api()
{
	register_rest_route("api/v1", "emailexist", array(
		'methods' => WP_REST_Server::CREATABLE,
		'callback' => 'emailexist',
	));
}

function create_new_post_contest_api()
{
	register_rest_route("api/v1", "newparticipant", array(
		'methods' => WP_REST_Server::CREATABLE,
		'callback' => 'newparticipant',
	));
}

function get_contest_posts()
{
	register_rest_route("api/v1", "getcontestposts", array(
		'methods' => WP_REST_Server::READABLE,
		'callback' => 'getcontestposts',
	));
}

function like_post()
{
	register_rest_route("api/v1", "likepost", array(
		'methods' => WP_REST_Server::CREATABLE,
		'callback' => 'likepost',
	));
}

function search_posts()
{
	register_rest_route("api/v1", "contestsearch", array(
		'methods' => WP_REST_Server::READABLE,
		'callback' => 'contestsearch',
	));
}

function captcha()
{
	register_rest_route("api/v1", "verifyhuman", array(
		'methods' => WP_REST_Server::CREATABLE,
		'callback' => 'verifyhuman',
	));
}

function verify_pi()
{
	register_rest_route("api/v1", "verify_p_iva", array(
		'methods' => WP_REST_Server::READABLE,  
		'callback' => "verify_p_iva",
	));
}

function twofa()
{
	register_rest_route("api/v1", "verify_two_fa", array(
		'methods' => WP_REST_Server::CREATABLE,  
		'callback' => "verify_two_fa",
	));
}

function send_email_reset_password_api()
{
	register_rest_route("api/v1", "send_email_reset_password", array(
		'methods' => WP_REST_Server::CREATABLE,  
		'callback' => "send_email_reset_password",
	));
}

function update_password_api()
{
	register_rest_route("api/v1", "update_password", array(
		'methods' => WP_REST_Server::CREATABLE,  
		'callback' => "update_password",
	));
}

// ====================================================================
// OLD Advertisement API Registration Functions (DEPRECATED)
// These are now handled by AdvertisementController in advertisement_new.php
// Kept here for reference only
// ====================================================================

/*
function new_advertisement_api()
{
	register_rest_route("api/v1", "advertisements/create", array(
		'methods' => WP_REST_Server::CREATABLE,  
		'callback' => "create_ad",
	));
}

function update_advertisement_api()
{
	register_rest_route("api/v1", "advertisements/update", array(
		'methods' => WP_REST_Server::EDITABLE,  
		'callback' => "update_ad",
	));
}

function delete_advertisement_api()
{
	register_rest_route("api/v1", "advertisements/delete", array(
		'methods' => WP_REST_Server::DELETABLE,  
		'callback' => "delete_ad",
	));
}

function get_single_advertisement_api()
{
	register_rest_route("api/v1", "advertisements/read/single", array(
		'methods' => WP_REST_Server::READABLE,  
		'callback' => "get_single_ad",
	));
}

function get_user_advertisement_api()
{
	register_rest_route("api/v1", "advertisements/read/user", array(
		'methods' => WP_REST_Server::READABLE,  
		'callback' => "get_user_ads",
	));
}

function get_advertisement_api()
{
	register_rest_route("api/v1", "advertisements/read/all", array(
		'methods' => WP_REST_Server::READABLE,  
		'callback' => "get_all_ads",
	));
}  */

// ====================================================================
// Mixed API (Products + Advertisements) - Still Active
// ====================================================================

function get_advertisements_and_products_api()
{
	register_rest_route("api/v1", "mixed/read/all", array(
		'methods' => WP_REST_Server::READABLE,  
		'callback' => "get_advertisements_and_products",
	));
}

function get_products_api()
{
	register_rest_route("api/v1", "products/read/all", array(
		'methods' => WP_REST_Server::READABLE,  
		'callback' => "get_all_products",
	));
}
