<?php

define("TWOFA_FAILED", -10);
define("TWOFA_INIT", 10);

require_once WP_CONTENT_DIR . "/themes/shopic-child/user_session_manager/user_session_manager.php";

use App\Auth\UserSessionManager;
$user_manager = new UserSessionManager(USM_SECRET_KEY, "petbuy.com", "https://petbuy-local.ns0.it:8080");


function start_2fa($user_id)
{
    if ($user_id <= 0 || $user_id == NULL)
        return TWOFA_FAILED;

    $otp = random_int(100000,999999);

    delete_user_meta($user_id, 'OTP_CODE');
    $is_otp_saved = add_user_meta($user_id, 'OTP_CODE', $otp);
    if ($is_otp_saved == false)
        return TWOFA_FAILED;

    $user_object = get_user_by('ID', $user_id);
    if ($user_object == false)
        return TWOFA_FAILED;

    $html_code_for_email = "Ciao, <br> ecco il tuo codice: <strong>$otp</strong>. <br> Il team Petbuy.";

    add_filter( 'wp_mail_content_type', 'wpdocs_set_html_mail_content_type' );
	wp_mail($user_object->data->user_email, "Autorizza l'accesso", $html_code_for_email);
	remove_filter( 'wp_mail_content_type', 'wpdocs_set_html_mail_content_type' );

    return TWOFA_INIT;
}


function verify_two_fa(WP_REST_Request $request)
{
    $data = $request->get_json_params();
    $sended_otp = $data['otp'];
    $token = $data['token'];
    global $user_manager;

    if (!isset($sended_otp) || !isset($token))
        return new WP_REST_Response(["status" => "Parametri vuoti"], 500);

    $user_id = $user_manager->getUserIdFromToken( $token, 0 );
    if ($user_id == NULL)
        return new WP_REST_Response(["status" => "Account inesistente"], 500);

    $saved_otp = get_user_meta($user_id, 'OTP_CODE');
    if ($saved_otp == false)
        return new WP_REST_Response(["status" => "OTP non valido"], 500);

    if ($saved_otp[0] == $sended_otp)
    {
        delete_user_meta($user_id, 'OTP_CODE');
        $twofa_challenge = $user_manager->markTwofaChallengeAsCompleted( $token );
        if ($twofa_challenge == true)
            return new WP_REST_Response(["status" => "Accesso autorizzato"], 200);

        return new WP_REST_Response(["status" => "Accesso negato"], 500);

    }
    else
        return new WP_REST_Response(["status" => "Accesso negato"], 500);
}

?>