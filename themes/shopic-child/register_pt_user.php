<?php



require_once WP_CONTENT_DIR . "/themes/shopic-child/register_vendor.php";



define("MAX_DIM_USERNAME", 20);



function createuserpt(WP_REST_Request $request)  // controllare che ogni packetto step abbia tutti i campi

{

	$first_packet_encoded = $request->get_param('step1');

	$second_packet_encoded = $request->get_param('step2');

	$third_packet_encoded = $request->get_param('step3');

	$role =  $request->get_param('role');  // OK



	if (($role != "customer" && $role != "vendor"))

		return new WP_REST_Response((["status" => "Tu vuoi inculare me? Non penso proprio " . $role]), 500); // OK



	if (!isset($first_packet_encoded) || !isset($second_packet_encoded) || !isset($third_packet_encoded))

		return new WP_REST_Response((["status" => "Gli step inviati sono nulli"]), 500);



	if (decode($first_packet_encoded)['status'] == TOKEN_DECORE_ERROR || decode($second_packet_encoded)['status'] == TOKEN_DECORE_ERROR || decode($third_packet_encoded)['status'] == TOKEN_DECORE_ERROR)

		return new WP_REST_Response((["status" => "Il tuo token non è più valido. Devi ricominciare il processo di registrazione"]), 500);





	$first_packet_decoded = json_decode(decode($first_packet_encoded)['data']->dataEnc);

	$second_packet_decoded = json_decode(decode($second_packet_encoded)['data']->dataEnc);

	$third_packet_decoded = json_decode(decode($third_packet_encoded)['data']->dataEnc);



	$check_email = email_exists($first_packet_decoded->mail);



	if ($check_email != false)

		return new WP_REST_RESPONSE(["status" => "Esiste già un account con questa email!"], 500);



	if ($role == 'customer')

	{

		$check_username = username_exists($first_packet_decoded->nickname);

    

		if ($check_username != false)

			return new WP_REST_RESPONSE(["status" => "Esiste già un account con questo username!"], 500);



		$user_id = wp_insert_user(array(

			'user_login' => $first_packet_decoded->nickname,

			'user_pass' => $second_packet_decoded->password,

			'user_email' => $first_packet_decoded->mail,

			'first_name' => $first_packet_decoded->name,

			'last_name' => $first_packet_decoded->surname,

			'role' => "customer"

		));

	

		if (filter_var($user_id, FILTER_VALIDATE_INT) == false)

			return new WP_REST_RESPONSE(["status" => "C'è stato un errore! Riprova tra qualche minuto."], 500); 

	

		$customer = new WC_Customer($user_id);

		$customer->set_billing_phone($first_packet_decoded->phone);

		$customer->set_billing_address($third_packet_decoded->address);

		$customer->set_billing_country('IT'); // default from 03-14-2024

		$customer->set_billing_state($third_packet_decoded->state);

		$customer->set_billing_city($third_packet_decoded->city);

		$customer->set_billing_postcode($third_packet_decoded->zip);

		$customer->save();

	}



	else if ($role == 'vendor')

	{

		$check_username = username_exists($first_packet_decoded->azienda);

    

		if ($check_username != false)

			return new WP_REST_RESPONSE(["status" => "Esiste già un account con questo username!"], 500);



		$user_id = register_vendor(

			$first_packet_decoded->pi, 

			$first_packet_decoded->azienda, 

			$first_packet_decoded->mail,

			$first_packet_decoded->username, 

			$second_packet_decoded->password, 

			array('address_1' => $third_packet_decoded->address, 'city' => $third_packet_decoded->city, 'postcode'=> $third_packet_decoded->zip, 'state' => $third_packet_decoded->state, 'country' => 'IT')

		);



		if ($user_id == VENDOR_REG_FAILED)

			return new WP_REST_RESPONSE(["status" => "C'è stato un errore! Riprova tra qualche minuto."], 500); 

	}

	

	if(send_email_to_verify($first_packet_decoded->mail, $user_id))

		return new WP_REST_Response((["status" => "Registrazione effettuata!", "sent_email" => "yes"]), 201);

	

	return new WP_REST_Response((["status" => "Registrazione effettuata! L'invio dell'email e' fallito", "sent_email" => "no"]), 201);

}



function send_email_reset_password(WP_REST_Request $request)

{

	$data = $request->get_json_params();

	$email = $data['email'];



	if (!isset($email))

		return new WP_REST_Response((["status" => "Parametro vuoto"]), 400);

	

	$check_email = email_exists($email);

	if ($check_email == false)

		return new WP_REST_Response((["status" => "Non esiste nessun account asscoiato a questa email.", "sent_email" => "no"]), 404);





	$content = ["user" => $check_email];

	$payload = encode($content, 600); // 10 minutes



	$link = "https://petbuy-local.ns0.it:8080/reset-password?payload=" . $payload;

	$html_code_for_email = "<div>

								<h1>Benvenuto su PetBuy!</h1>

								<p style='margin-bottom: 2rem;'>Clicca sul pulsante sottostante per cambiare la tua password:</p>

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

								text-decoration: none;'>Recupera password</a>



								<p style='margin-top: 2rem;'>Se il pulsante non funziona, clicca sul seguente link:</p>

								<p>$link</p>

							</div>";





    add_filter( 'wp_mail_content_type', 'wpdocs_set_html_mail_content_type' );

	wp_mail($email, "Reimposta la password", $html_code_for_email);

	remove_filter( 'wp_mail_content_type', 'wpdocs_set_html_mail_content_type' );



	return new WP_REST_Response((["status" => "Email di reset password inviata", "sent_email" => "yes"]), 200);



}



function update_password(WP_REST_Request $request)

{

	$data = $request->get_json_params();

	$token_encoded = $data['token'];

	$new_password = $data['psw'];



	if (!isset($token_encoded) || !isset($new_password))

		return new WP_REST_Response((["status" => "Parametri vuoti"]), 400);



	$token_decoded = decode($token_encoded);

	

	if ($token_decoded['status'] == TOKEN_DECORE_ERROR)

		return new WP_REST_Response((["status" => "Il tuo link non è valido."]), 500);



	$user_id = $token_decoded['data']->dataEnc->user;

	$object_user = get_user_by('id', $user_id);



	reset_password($object_user, $new_password);

	

	return new WP_REST_Response((["status" => "Password aggiornata"]), 200);

}



function usernamexists(WP_REST_Request $request)

{

	$username = $request->get_param('nickname');



	if(!isset($username) || empty($username))

		return new WP_REST_RESPONSE(["status" => "Username vuoto"], 500);



	if(strlen($username) >= MAX_DIM_USERNAME)

		return new WP_REST_RESPONSE(["status" => "Dimensione massima superata"], 500);



	$check_username = username_exists($username);

    

	if ($check_username != false)

		return new WP_REST_RESPONSE(["status" => "Username giá in uso"], 500);



	return new WP_REST_RESPONSE(["status" => "OK"], 200);

}



function emailexist(WP_REST_Request $request)

{

	$email = $request->get_param('email');



	if(!isset($email) || empty($email))

		return new WP_REST_RESPONSE(["status" => "Email vuota"], 500);



	if(!is_email($email))

		return new WP_REST_RESPONSE(["status" => "Email non valida"], 500);





	$check_email = email_exists($email);



	if ($check_email != false)

		return new WP_REST_RESPONSE(["status" => "Email giá in uso"], 500);



	return new WP_REST_RESPONSE(["status" => "OK"], 200); 

}



?>