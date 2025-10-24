<?php
/**
 * Wishlist API per Petbuy
 *
 * Questo file contiene tutte le funzioni helper e le funzioni principali per la gestione della wishlist
 * tramite API REST WordPress.
 *
 * Funzionalità:
 * - Aggiunta e rimozione di elementi dalla wishlist
 * - Verifica esistenza elemento
 * - Recupero wishlist utente
 * - Supporto sia per prodotti WooCommerce che annunci custom
 *
 * @author Max
 */

require_once WP_CONTENT_DIR . "/themes/miotemplate/user_session_manager/user_session_manager.php";
//require_once WP_CONTENT_DIR . "/themes/miotemplate/advertisement.php";

use App\Auth\UserSessionManager;
$user_manager = new UserSessionManager(USM_SECRET_KEY, "petbuy.com", "https://petbuy-local.ns0.it:8080");

//// HELPER ////

/**
 * Determina se l'elemento è un annuncio (hash) o un prodotto (ID numerico).
 *
 * @param mixed $element_id
 * @return bool
 */
function is_advertisement($element_id)
{
    return preg_match('/[a-zA-Z]/', $element_id) ? true : false;
}

/**
 * Verifica se un elemento è già presente nella wishlist dell'utente.
 *
 * @param string $element_id
 * @param int $user_id
 * @return bool
 */
function already_exist_in_wishlist($element_id, $user_id)
{
    global $wpdb;
    $wishlist_table = $wpdb->prefix . 'petbuy_wishlist';

    $query = $wpdb->prepare("SELECT id FROM $wishlist_table WHERE item_id = %s AND user_id = %d", $element_id, $user_id);
    $query_check = $wpdb->get_results($query);

    return !empty($query_check);
}

/**
 * Verifica se l'hash dell'annuncio esiste ed è APPROVED.
 *
 * @param string $element_id
 * @return bool
 */
function verify_advertisement_id_helper($element_id)
{
    global $wpdb;
    $advertisement_table = $wpdb->prefix . 'advertisements_view';

    $ad_status = "APPROVED";
    $query = $wpdb->prepare("SELECT id FROM $advertisement_table WHERE advertisement_hash = %s AND ad_status = %s", $element_id, $ad_status);
    $query_check = $wpdb->get_results($query);

    return !empty($query_check);
}

/**
 * Verifica se l'ID passato corrisponde a un prodotto pubblicato WooCommerce.
 *
 * @param int|string $element_id
 * @return bool
 */
function verify_product_id_helper($element_id)
{
    return (get_post_status($element_id) === 'publish' && get_post_type($element_id) === 'product');
}

/**
 * Aggiunge un elemento alla wishlist dell'utente.
 *
 * @param int $user_id
 * @param string $element_id
 * @return bool
 */
function add_element($user_id, $element_id)
{
    global $wpdb;
    $wishlist_table = $wpdb->prefix . 'petbuy_wishlist';

    $query = $wpdb->prepare("INSERT INTO $wishlist_table (item_id, user_id) VALUES (%s, %d)", $element_id, $user_id);
    $result = $wpdb->query($query);

    return $result !== false;
}

/**
 * Rimuove un elemento dalla wishlist dell'utente.
 *
 * @param int $user_id
 * @param string $element_id
 * @return bool
 */
function remove_element($user_id, $element_id)
{
    global $wpdb;
    $wishlist_table = $wpdb->prefix . 'petbuy_wishlist';

    $query = $wpdb->prepare("DELETE FROM $wishlist_table WHERE item_id = %s AND user_id = %d", (string)$element_id, $user_id);
    $result = $wpdb->query($query);

    return ($result !== false && $result !== 0);
}

/**
 * Recupera tutti gli elementi della wishlist di un utente.
 *
 * @param int $user_id
 * @return array|false
 */
function get_elements($user_id)
{
    global $wpdb;
    $wishlist_table = $wpdb->prefix . 'petbuy_wishlist';

    $query = $wpdb->prepare("SELECT item_id FROM $wishlist_table WHERE user_id = %d", $user_id);
    $query_check = $wpdb->get_results($query);

    if($query_check === false)
        return false;
    return $query_check;
}

/**
 * Restituisce i dati essenziali di un prodotto per la wishlist.
 *
 * @param int $product_id
 * @return array|false
 */
function get_single_product_wishlist($product_id)
{
    $product = wc_get_product($product_id);

    if (!$product)
        return false;

    $main_image_id  = $product->get_image_id();
    $main_image_url = $main_image_id ? wp_get_attachment_url($main_image_id) : "https://petbuy-local.ns0.it:8080/wp-content/uploads/woocommerce-placeholder-300x300.png.webp";

    return [
        'main_image'      => $main_image_url,
        'product_id'      => $product->get_id(),
        'name'            => $product->get_name(),
        'price'           => (float)$product->get_price(),
        'is_on_sale'      => $product->is_on_sale(),
        'sale_price'      => (float)$product->get_sale_price(),
        'add_to_cart_url' => $product->add_to_cart_url(),
    ];
}

/**
 * Restituisce i dati essenziali di un annuncio per la wishlist.
 *
 * @param string $ad_hash
 * @return array|false
 */
function get_single_ad_wishlist($ad_hash)
{
    global $wpdb;
    $advertisement_id = verify_advertisement_id_helper($ad_hash);

    if($advertisement_id == false)
        return false;

    $ads_table = $wpdb->prefix . "advertisements_view";
    $query = $wpdb->prepare(
        "SELECT ad_name, link_cover, price, sale_price, on_sale, gift 
         FROM $ads_table 
         WHERE advertisement_hash = %s AND ad_status = %s",
        $ad_hash, "APPROVED"
    );
    $query_result = $wpdb->get_row($query);

    if($query_result == false)
        return false;

    return [
        'ad_name'    => $query_result->ad_name,
        'link_cover' => $query_result->link_cover,
        'price'      => (float)$query_result->price,
        'sale_price' => (float)$query_result->sale_price,
        'on_sale'    => $query_result->on_sale,
        'gift'       => $query_result->gift,
    ];
}

/**
 * Aggiunge un elemento alla wishlist tramite API REST.
 *
 * @param WP_REST_Request $request
 * @return WP_REST_Response
 */
function add_to_wishlist(WP_REST_Request $request) 
{
    $data = $request->get_json_params();
    $user_token = $data['user_token'] ?? null;
    $element_id = $data['element_id'] ?? null;

    if($user_token === null || $element_id === null)
        return new WP_REST_Response(["status" => "I parametri sono inesistenti"], 400);

    if(empty($user_token) || empty($element_id))
        return new WP_REST_Response(["status" => "I parametri sono vuoti"], 422);

    global $user_manager;
    $user_id = $user_manager->getUserIdFromToken($user_token);

    if($user_id == NULL)
        return new WP_REST_Response(["status" => "ID utente non valido"], 401);

    if(already_exist_in_wishlist($element_id, $user_id))
        return new WP_REST_Response(["status" => "L'elemento è già presente nella wishlist"], 409);

    $is_advertisement = is_advertisement($element_id);

    if($is_advertisement && verify_advertisement_id_helper($element_id)) {
        if(add_element($user_id, $element_id))
            return new WP_REST_Response(["status" => "Annuncio aggiunto alla wishlist"], 201);
    }

    if(!$is_advertisement && verify_product_id_helper($element_id)) {
        if(add_element($user_id, $element_id))
            return new WP_REST_Response(["status" => "Prodotto aggiunto alla wishlist"], 201);
    }

    return new WP_REST_Response(["status" => "Si è verificato un errore"], 500);
}

/**
 * Rimuove un elemento dalla wishlist tramite API REST.
 *
 * @param WP_REST_Request $request
 * @return WP_REST_Response
 */
function remove_from_wishlist(WP_REST_Request $request)
{
    $data = $request->get_json_params();
    $user_token = $data['user_token'] ?? null;
    $element_id = $data['element_id'] ?? null;

    if($user_token === null || $element_id === null)
        return new WP_REST_Response(["status" => "I parametri sono inesistenti"], 400);

    if(empty($user_token) || empty($element_id))
        return new WP_REST_Response(["status" => "I parametri sono vuoti"], 422);

    global $user_manager;
    $user_id = $user_manager->getUserIdFromToken($user_token);

    if($user_id == NULL)
        return new WP_REST_Response(["status" => "ID utente non valido"], 401);

    if(remove_element($user_id, $element_id))
        return new WP_REST_Response(["status" => "Elemento rimosso dalla wishlist"], 200);

    return new WP_REST_Response(["status" => "L'elemento non è presente nella wishlist"], 404);
}

/**
 * Recupera la wishlist dell'utente tramite API REST.
 *
 * @param WP_REST_Request $request
 * @return WP_REST_Response
 */
function get_user_wishlist(WP_REST_Request $request)
{
    $data = $request->get_params();
    $user_token = $data['user_token'] ?? null;

    if($user_token === null)
        return new WP_REST_Response(["status" => "I parametri sono inesistenti"], 400);

    if(empty($user_token))
        return new WP_REST_Response(["status" => "I parametri sono vuoti"], 422);

    global $user_manager;
    $user_id = $user_manager->getUserIdFromToken($user_token);

    if($user_id == NULL)
        return new WP_REST_Response(["status" => "ID utente non valido"], 401);

    $query_result = get_elements($user_id);

    if($query_result === false)
        return new WP_REST_Response(["status" => "Si è verificato un errore"], 500);
    if(empty($query_result))
        return new WP_REST_Response(["status" => "La wishlist è vuota"], 404);

    $wishlist_elements = [];
    foreach ($query_result as $element) {
        if(is_advertisement($element->item_id) && verify_advertisement_id_helper($element->item_id)) {
            $advertisement = get_single_ad_wishlist($element->item_id);
            if($advertisement != false && !empty($advertisement))
                $wishlist_elements[] = [
                    'type' => 'advertisement',
                    'element' => $advertisement,
                ];
        }
        elseif (verify_product_id_helper($element->item_id)) {
            $product = get_single_product_wishlist($element->item_id);
            if($product != false)
                $wishlist_elements[] = [
                    'type' => 'product',
                    'element' => $product,
                ];
        }
    }
    return new WP_REST_Response($wishlist_elements, 200);
}