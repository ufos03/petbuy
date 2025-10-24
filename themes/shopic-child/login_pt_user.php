<?php

define("SESSION_TIME_SEC", 3600);
define("SESSION_TIME_MILLISEC", SESSION_TIME_SEC * 1000);

require_once WP_CONTENT_DIR . '/themes/shopic-child/two_fa.php';
require_once WP_CONTENT_DIR . "/themes/shopic-child/user_session_manager/user_session_manager.php";

use App\Auth\UserSessionManager;
$user_manager = new UserSessionManager(USM_SECRET_KEY, "petbuy.com", "https://petbuy-local.ns0.it:8080");

function create_user_session($user_id)
{
    global $user_manager;
    try 
    {
        return $user_manager->generateToken($user_id, SESSION_TIME_SEC);

    } catch (\Throwable $th) {
        return NULL;
    }
}

function delete_user_session($token)
{
    global $user_manager;
    try
    {
        $user_manager->destroyToken($token);
        return true;
    } catch (\Throwable $th) 
    {
        return false;
    }
}

function is_user_registered($user)
{
    if (is_email($user) != false)
        $eval_of_user_info = email_exists($user);
    else
        $eval_of_user_info = username_exists($user);

    if ($eval_of_user_info != false)
        return true;

    return false;
}

function is_user_verified($user)
{
    $user_data_type = '';
    if (is_email($user) != false)
        $user_data_type = 'email';
    else
        $user_data_type = 'login';  //username

    $user_object_data = get_user_by($user_data_type, $user);
    
    if($user_object_data == false)
        return false;

    $user_id = $user_object_data->ID;
    $user_verified = get_user_meta($user_id, VERIFIED_FIELD);

    if ($user_verified == "1");
        return true;
    return false;
}



function loginuserpt(WP_REST_Request $request)
{
    $data_from_request = $request->get_json_params();
    $user = $data_from_request['user'];
    $pass = $data_from_request['pass'];
    $user_data_type = '';

    if (!isset($user) || !isset($pass))
        return new WP_REST_Response((["status" => "Non sono stati inviati correttamenti i parametri"]), 404);

    if (!is_user_registered($user))
        return new WP_REST_Response((["status" => "Non esiste nessun account con queste informazioni"]), 404);

    if (!is_user_verified($user))
        return new WP_REST_Response((["status" => "L'account non è ancora verificato. Controlla la tua email"]), 403);

    if (is_email($user) != false)
        $user_data_type = 'email';
    else
        $user_data_type = 'login';  //username

    $user_object_data = get_user_by($user_data_type, $user);

    if($user_object_data == false)
        return new WP_REST_Response((["status" => "Si è verificato un errore inaspettato. Riprova tra qualche istante"]), 500);

    $user_id = $user_object_data->ID;
    $login_state = wp_signon(array('user_login' => $user, 'user_password' => $pass, 'secure' => is_ssl()));

    if ($login_state instanceof WP_User)
    {
        wp_set_current_user($user_id);

        $session = create_user_session($user_id, false);

        if ($session == NULL)
            return new WP_REST_Response((["status" => "Si è verificato un errore inaspettato. Riprova tra qualche istante 1"]), 500);

        if (start_2fa($user_id) == TWOFA_FAILED)
            return new WP_REST_Response((["status" => "Si è verificato un errore inaspettato. Riprova tra qualche istante"]), 500);

        return new WP_REST_Response((["status" => "Success", "user" => $session, "session_time" => SESSION_TIME_MILLISEC]), 200);
    }

    else if ($login_state->get_error_code() == 'ip_blocked')
    {
        $response_error = $login_state->errors['ip_blocked'][0];
        preg_match_all('!\d+!', $response_error, $matches);
        return new WP_REST_Response((["status" => "Hai superato il numero massimo di tentivi. Riprova tra " . $matches[0][0] . " minuti"]), 403);
    }
    else
        return new WP_REST_Response((["status" => "Le credenziali non sono corrette"]), 400);
}



function logoutuserpt(WP_REST_Request $request)
{
    $data_from_request = $request->get_json_params();
    $token = $data_from_request['token'];

    if ($token == -1)
        return new WP_REST_RESPONSE(["status" => "Non sei loggato"], 403);
    wp_logout();

    if (delete_user_session($token) == false)
        return new WP_REST_Response((["status" => "Si è verificato un errore inaspettato. Riprova tra qualche istante"]), 500);

    return new WP_REST_Response((["status" => "Logout effettuato con successo"]), 200);
}

?>