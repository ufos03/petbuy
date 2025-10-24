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
use Firebase\JWT\CachedKeySet;
use Firebase\JWT\JWK;
use Firebase\JWT\JWTExceptionWithPayloadInterface;
use Firabase\JWT\BeforeValidException;
use Firebase\JWT\SignatureInvalidException;
use Firebase\JWT\ExpiredException;


//kgb
define("TOKEN_DECORE_ERROR", -100);

//define("TOKEN_EXPIRED", -90);



$key = '3e298b71c263a3adf3f0ecbebdc219e4';





function decode($packet)

{

    if (!isset($packet)) {

        return ["status" => TOKEN_DECORE_ERROR];

    }

    try {

        global $key;

        $jwt = JWT::decode($packet, new Key($key, 'HS512'));

        return ["data" => $jwt, "status" => "OK"];

    } catch (\Throwable $th) {

        return ["status" => TOKEN_DECORE_ERROR];

    }

}



?>