<?php

require_once WP_CONTENT_DIR . "/themes/shopic-child/JWT/JWT.php";
require_once WP_CONTENT_DIR . "/themes/shopic-child/JWT/Key.php";
require_once WP_CONTENT_DIR . "/themes/shopic-child/JWT/CachedKeySet.php";
require_once WP_CONTENT_DIR . "/themes/shopic-child/JWT/JWK.php";
require_once WP_CONTENT_DIR . "/themes/shopic-child/JWT/JWTExceptionWithPayloadInterface.php";
require_once WP_CONTENT_DIR . "/themes/shopic-child/JWT/BeforeValidException.php";
require_once WP_CONTENT_DIR . "/themes/shopic-child/JWT/SignatureInvalidException.php";
require_once WP_CONTENT_DIR . "/themes/shopic-child/JWT/ExpiredException.php";


use Firebase\JWT\JWT;
use Firebase\JWT\Key;



define("TOKEN_NOT_GENERATED", -110);



$key = '3e298b71c263a3adf3f0ecbebdc219e4';



function encode($data, $duration)

{

    $issuedAt = time();

    $payload = [

        'iss' => 'https://petbuy-local.ns0.it:8080',

        'aud' => 'https://petbuy-local.ns0.it:8080',

        'iat' => $issuedAt,

        'exp' => $issuedAt + $duration,

        "dataEnc" => $data

    ];

    try {

        global $key;

        $jwt = JWT::encode($payload, $key, 'HS512');

        return $jwt;

    } catch (\Throwable $th) {

        return TOKEN_NOT_GENERATED;

    }

}





function encodedata(WP_REST_Request $request)

{

	$data = $request->get_json_params();

	if (!isset($data['packet']))

		return new WP_REST_Response((["status" => "La richiesta è arrivata senza parametri"]), 500);



	$enc_res = encode($data['packet'], 3600); // 1h



	if ($enc_res == TOKEN_NOT_GENERATED)

		return new WP_REST_Response((["status" => "C'è stato un errore nella generazione del token"]), 500);



	return new WP_REST_Response((["data" => $enc_res, "status" => "SUCCESS"]), 201);

}



?>