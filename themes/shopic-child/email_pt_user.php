<?php



define("AUTH_LINK", "https://petbuy-local.ns0.it:8080/verifica-email-endpoint/?t=");

define("CODE_FIELD", "code");

define("VERIFIED_FIELD", "verified");

define("CHALLENGE_FIELD", "challenge");

define("MAX_CHALLENGE", 3);

define("CHALLENGE_OVERCOME", 100);



function wpdocs_set_html_mail_content_type() {

	return 'text/html';

}



function get_random_code()

{

	return mt_rand(1000, mt_getrandmax());

}



function encode_data_of_verification($plain_code, $user_id)

{

	$data = ['code' => $plain_code, "user" => $user_id];

	$jwt = encode($data, 600); // 10 minutes

	return $jwt;

}



function send_email_to_verify($user_mail, $user_id)

{

	$pl_code = get_random_code();

	$code = encode_data_of_verification($pl_code, $user_id);

	if ($code == TOKEN_NOT_GENERATED)

		return false;



	if (metadata_exists('user', $user_id, CODE_FIELD) == false)

	{

		$resCode = add_user_meta($user_id, CODE_FIELD, $code);

		$resChallenge = add_user_meta($user_id, CHALLENGE_FIELD, 0);

	}	

	else

	{

		(int)$user_challenge_count = get_user_meta($user_id, CHALLENGE_FIELD)[0];

		$resCode = update_user_meta($user_id, CODE_FIELD, $code);

		$resChallenge = update_user_meta($user_id, CHALLENGE_FIELD, $user_challenge_count + 1);

	}



	if ($resCode == false || $resChallenge == false)

		return false;



	$link = AUTH_LINK . $code;

	$html_code_for_email = "<div>

								<h1>Benvenuto su PetBuy!</h1>

								<p style='margin-bottom: 2rem;'>Clicca sul pulsante sottostante per attivare il tuo account:</p>

								<a href='$link' style='

								font-weight: normal;

								text-transform: none;

								color: #fff;

								background-color: #f8bb39;

								margin: 0;

								padding: 0.72rem;

								text-decoration: none;

								display: block;

								border-radius: 14px;

								text-decoration: none;'>Attiva Account</a>



								<p style='margin-top: 2rem;'>Se il pulsante non funziona, clicca sul seguente link:</p>

								<p>$link</p>

							</div>";

	

	add_filter( 'wp_mail_content_type', 'wpdocs_set_html_mail_content_type' );

	

	wp_mail($user_mail, "Identificati", $html_code_for_email);



	remove_filter( 'wp_mail_content_type', 'wpdocs_set_html_mail_content_type' );

	return true;

}





function verifyemail(WP_REST_Request $request)

{

	$token_enc_email = $request->get_param('t');

	$token_dec_email = decode($token_enc_email);



	if ($token_dec_email['status'] == TOKEN_DECORE_ERROR)

		return new WP_REST_Response((["status" => "Il tuo link non è valido. Richiedine un'altro", "action" => "NEW_LINK"]), 500);

		



	$packet_on_email = $token_dec_email['data']->dataEnc;

	$code_email = $packet_on_email->code;

	$user_id = $packet_on_email->user;



	$exist_email_check = get_user_by("id", $user_id);

	

	if ($exist_email_check instanceof WP_User == false)

		return new WP_REST_Response((["status" => "Non esiste nessun account associato a quest'email", "action" => "NONE"]), 500);

	

	if (get_user_meta($user_id, VERIFIED_FIELD)[0] == 1)

		return new WP_REST_Response((["status" => "Account già verificato.", "action" => "NONE"]), 500);



	$packet_on_db = get_user_meta($user_id, 'code')[0];

	$code_db = decode($packet_on_db)['data']->dataEnc->code;

	

	if ($code_db['status'] == TOKEN_DECORE_ERROR)

		return new WP_REST_Response((["status" => "Il token è corrotto. Richiedine un'altro", "action" => "NEW_LINK"]), 500);



	$user_challenge_count = get_user_meta($user_id, CHALLENGE_FIELD)[0];

	$challenge_count = intval($user_challenge_count);





	if ($challenge_count >= MAX_CHALLENGE)

	{

		wp_delete_user($user_id);

		return new WP_REST_Response((["status" => "Hai superato il massimo di richieste. (" . $challenge_count . "/" . MAX_CHALLENGE . ")", "action" => "NONE"]), 500);

	}



		

	update_user_meta($user_id, CHALLENGE_FIELD, $user_challenge_count[0] + 1);



	if ($user_challenge_count == CHALLENGE_OVERCOME)

		return new WP_REST_Response((["status" => "SUCCESS"]), 201);

	

	if ($code_db == $code_email)

	{

		delete_user_meta($user_id, CODE_FIELD);

		update_user_meta($user_id, CHALLENGE_FIELD, CHALLENGE_OVERCOME);

		$verified = add_user_meta($user_id, VERIFIED_FIELD, true);

		if(!$verified)

			return new WP_REST_Response((["status" => "Ci sono problemi con il server. Riprova tra qualche istante", "action" => "NONE"]), 500);

		

		return new WP_REST_Response((["status" => "Account verificato! Tra qualche istante verrai reindirizzo al login!", "action" => "TO_LOGIN"]), 200);

	}



	return new WP_REST_Response((["status" => "Account non verificato. Riprova", "action" => "NEW_LINK"]), 200);

}





function sendemailapi(WP_REST_Request $request)  // link: ...?e=email_address

{

	$data = $request->get_params();



	$user_email = $data['e'];

	$user_info_wp = get_user_by('email', $user_email);

	

	if ($user_info_wp == false)

		return new WP_REST_Response((["status" => "Non esiste nessun account associato a quest'email"]), 500);



	$user_id = $user_info_wp->ID;



	$isVerified = get_user_meta($user_id, VERIFIED_FIELD);

	if($isVerified == true)

		return new WP_REST_Response((["status" => "Il tuo account è già verificato"]), 500);



	$user_challenge_count = get_user_meta($user_id, CHALLENGE_FIELD)[0];

	$challenge_count = intval($user_challenge_count);



	if ($challenge_count >= MAX_CHALLENGE)

	{

		wp_delete_user($user_id);

		return new WP_REST_Response((["status" => "Hai superato il massimo di richieste. (" . $challenge_count . "/" . MAX_CHALLENGE . ")"]), 500);

	}

	

	if(send_email_to_verify($user_email, $user_id))

		return new WP_REST_Response((["status" => "Email reinviata", "sent_email" => "yes"]), 201);

	

	return new WP_REST_Response((["status" => "Email non inviata, riprova tra qualche istante", "sent_email" => "no"]), 200);

}



?>