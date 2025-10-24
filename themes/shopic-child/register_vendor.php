<?php

define ('MVX_REGISTER_ROUTE', 'https://petbuy-local.ns0.it:8080/wp-json/mvx/v1/vendors');
define('VENDOR_REG_FAILED', -10);
define('VENDOR_REG_OK', 10);

function save_user_on_multivendor_db($business_name, $email, $login, $psw, $address)
{
    $data = array(
        'login' => $login,
        'password' => $psw,
        'display_name' => $business_name,
        'email' => $email,
        'address' => $address,
        'roles' => 'dc_vendor'
    );

    $json_encoded_data = json_encode($data);

    $curl = curl_init(MVX_REGISTER_ROUTE);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_POST, true);
    curl_setopt($curl, CURLOPT_POSTFIELDS, $json_encoded_data);
    curl_setopt($curl, CURLOPT_HTTPHEADER, array(
        'Content-Type: application/json',
        'Content-Length: ' . strlen($json_encoded_data)
    ));

    $response = curl_exec($curl);
    if (curl_errno($curl))
        return VENDOR_REG_FAILED;

    $response_decoded = json_decode($response);

    return $response_decoded->id;
}

function p_iva_exist($tax_code)
{
    global $wpdb;
    $user_meta_table = $wpdb->prefix . 'usermeta';

    $query = $wpdb->prepare("SELECT COUNT(*) AS p_iva_num FROM $user_meta_table WHERE meta_key = 'TAX_CODE' AND meta_value = (%s)", $tax_code);
    $query_check = $wpdb->get_results($query);

    if(intval($query_check[0]->p_iva_num) == 0)
        return false;
    return true;
}

function call_api_p_iva($tax_code, $business_name)
{
    $url = "https://ec.europa.eu/taxation_customs/vies/rest-api/ms/IT/vat/";
    $curl = curl_init();

    curl_setopt($curl, CURLOPT_URL, $url . $tax_code);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

    $response = curl_exec($curl);
    
    if(curl_errno($curl)) {
        curl_close($curl);
        return false;
    } 

    curl_close($curl);

    $data = json_decode($response, true);

    if(isset($data['name']) && $data['name'] == $business_name && $data['isValid'] == true)
        return true;
    else
        return false;
}

function verify_p_iva(WP_REST_Request $request)
{
    $p_iva = $request->get_param('tax_code');
    $business_name = $request->get_param('business_name');

    if(empty($p_iva) || empty($business_name))
        return new WP_REST_Response(["status" => "Parametri vuoti."], 400);

    if(p_iva_exist($p_iva) == true)
        return new WP_REST_Response(["status" => "Partita IVA gia' esistente"], 500);

    $response_api_p_iva = call_api_p_iva($p_iva, $business_name);

    if($response_api_p_iva == true)
        return new WP_REST_Response(["status" => "Partita IVA valida."], 200);
    else
        return new WP_REST_Response(["status" => "Partita IVA non valida oppure il nome non corrisponde."], 400);
    
}


function register_vendor($tax_code, $business_name, $email, $login, $psw, $address)
{
    if(p_iva_exist($tax_code) == true)
        return VENDOR_REG_FAILED;
    
    if(call_api_p_iva($tax_code, $business_name) == false)
        return VENDOR_REG_FAILED;

    $saved_on_mvx = save_user_on_multivendor_db($business_name, $email, $login, $psw, $address);

    if ($saved_on_mvx == VENDOR_REG_FAILED)
        return VENDOR_REG_FAILED;

    $tax_code_saved_state = add_user_meta($saved_on_mvx, 'TAX_CODE', $tax_code);
    if ($tax_code_saved_state == false)
    {
        delete_user_meta($saved_on_mvx, 'REA');
        return VENDOR_REG_FAILED;
    }

    return VENDOR_REG_OK;
}
