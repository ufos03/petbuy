<?php

define("HUMAN_FACTOR", 0.5);

function verifyhuman(WP_REST_Request $request)
{
    $data = $request->get_json_params();
    $token = $data["token"];

    $post_data = http_build_query(
        array(
            'secret' => "6LeXcQoqAAAAAMwU7fQOPpm1_RfTx-igP444FIrM",
            'response' => $token,
        )
    );

    $opts = array('http' =>
        array(
            'method'  => 'POST',
            'header'  => 'Content-type: application/x-www-form-urlencoded',
            'content' => $post_data
        )
    );
    
    $context  = stream_context_create($opts);
    $response = file_get_contents('https://www.google.com/recaptcha/api/siteverify', false, $context);
    $result = json_decode($response);


    if ($result->success && $result->score >= HUMAN_FACTOR)
        return new WP_REST_Response((["action" => "CONTINUE"]), 200);

    return new WP_REST_Response((["action" => "STOP"]), 404);
}
