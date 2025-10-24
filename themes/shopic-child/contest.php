<?php



/**

 * Component Name: Contest

 * Description: Plugin per la gestione di un contest

 * Version: 1.0

 * Author: PetBuy

 * 

 * Il componente Contest permette di creare un contest in cui gli utenti possono partecipare, pubblicando immagini dei propri animali domestici. 

 * I dati utenti vengono salvati in un pseudo JSON all'interno del post, in modo da poterli recuperare facilmente.

 * Il pseudo JSON è composto da tre campi: name, description e image.

 * Sono separati tra loro da una virgola (,). 

 * Per associare ad ogni campo il suo valore, si utilizza il carattere underscore (|), poichè è un carattere non utilizzato nei campi.

 */



define('CONTEST_CATEGORY', 217);

define('SEPARATOR', "|");



define('CONTEST_DEFAULT_LIMIT', 5);



define("MAX_LENGTH_DESCRIPTION", 300);

define("MAX_LENGTH_NAME", 300);



require_once(ABSPATH . 'wp-admin/includes/image.php');

require_once(ABSPATH . 'wp-admin/includes/file.php');

require_once(ABSPATH . 'wp-admin/includes/media.php');



function get_likes($post_id)

{

  global $wpdb;



  $likes_table = $wpdb->prefix . 'likes_content_post_petbuy';

  $contest_post_table = $wpdb->prefix . 'contest_post_petbuy';

  $query = $wpdb->prepare("SELECT COUNT(*) AS num_likes FROM $likes_table INNER JOIN $contest_post_table ON post = id WHERE post_id = %d", $post_id);

  $query_check = $wpdb->get_results($query);



  return intval($query_check[0]->num_likes);

}



function get_user_id($token)

{

  global $wpdb;

  $session_table = $wpdb->prefix . 'user_session';



  $query = $wpdb->prepare("SELECT user FROM $session_table WHERE token = (%s)", $token);

  $query_check = $wpdb->get_results($query);



  if (!empty($query_check))

    return $query_check[0]->user;



  return NULL;

}



function newparticipant(WP_REST_Request $request)

{

  $contest_post_content = $request->get_body_params();

  $image_name = $_FILES["image"]["name"];

  $image_ext = end((explode(".", $image_name)));

  global $wpdb;



  $description = $contest_post_content['descr'];

  $name = $contest_post_content['name_animal'];

  $token = $contest_post_content['token'];



  $user_id = get_user_id($token);



  if ($user_id == NULL)

    return new WP_REST_RESPONSE(["status" => "Token non valido"], 400);



  if (empty($description) || empty($name) || empty($token))

    return new WP_REST_RESPONSE(["status" => "I parametri sono vuoti"], 403);



  if ($user_id == 0 || $user_id == 1)

    return new WP_REST_RESPONSE(["status" => "Azione non permessa"], 401);



  if (strlen($description) > MAX_LENGTH_DESCRIPTION || strlen($name) > MAX_LENGTH_NAME)

    return new WP_REST_Response((["status" => "Lunghezza dei campi supera il limite"]), 400);



  $is_user = get_user_by("ID", $user_id);



  if ($is_user instanceof WP_User == false)

    return new WP_REST_Response((["status" => "Non esiste nessun account"]), 404);



  if ($image_ext != "jpg" && $image_ext != "jpeg" && $image_ext != "png" && $image_ext != "webp")

    return new WP_REST_RESPONSE(["status" => "Il formato dell'immagine non è valido"], 415);



  $image_id = media_handle_upload('image', 0);



  if (filter_var($image_id, FILTER_VALIDATE_INT) == false)

    return new WP_REST_RESPONSE(["status" => "L'immagine non è stata caricata. Riprova"], 500);



  $html_image_format = wp_get_attachment_image($image_id);



  $post_data = "name" . SEPARATOR . $name . "_description" . SEPARATOR . $description . "_image" . SEPARATOR . str_replace('"', "'", $html_image_format); // Sostituisce i doppi apici con gli apici, per evitare errori in fase di decodifica (solo DIO sa cosa significhi questa merdata scritta)



  $post = array(

    'post_title' => "Post from " . $user_id,

    'post_content' => $post_data,

    'post_status' => 'pending',

    'post_author' => $user_id,

    'post_category' => array(CONTEST_CATEGORY),

  );



  $post_id = wp_insert_post($post);



  if (filter_var($post_id, FILTER_VALIDATE_INT) == false)

    return new WP_REST_RESPONSE(["status" => "C'è stato un errore nel creare il tuo post. Riprova"], 500);



  $timeSubmitted = time();

  $post_link = md5($timeSubmitted . $post_id);



  $contest_table = $wpdb->prefix . 'contest_post_petbuy';

  $query = $wpdb->prepare("INSERT INTO $contest_table (post_id, link, animal_name) VALUES ( %d, %s, %s)", $post_id, $post_link, $name);

  $query_check = $wpdb->query($query);



  if ($query_check == false)

    return new WP_REST_RESPONSE(["status" => "C'è stato un errore nel creare il tuo post. Riprova"], 500);



  return new WP_REST_RESPONSE(["status" => "Il tuo post è stato creato con successo!"], 201);

}



function dislike_post($user_id, $post_id)

{

  global $wpdb;

  $likes_table = $wpdb->prefix . 'likes_content_post_petbuy';

  $contest_post_table = $wpdb->prefix . 'contest_post_petbuy';

  $query = $wpdb->prepare("DELETE FROM $likes_table WHERE user_id = $user_id AND post = (SELECT id FROM $contest_post_table WHERE post_id = %d)", $post_id);

  $query_check = $wpdb->query($query);



  if ($query_check == false)

    return new WP_REST_RESPONSE(["status" => "C'è stato un errore nel rimuovere il like. Riprova"], 500);



  return new WP_REST_RESPONSE(["status" => "Like rimosso!", "action" => "SUB"], 200);

}



function likepost(WP_REST_Request $request)

{

  $like_post_data = $request->get_body_params();



  $post_id = $like_post_data['post_id'];

  $token = $like_post_data['token'];



  if ($token == -1)

    return new WP_REST_RESPONSE(["status" => "Non sei loggato"], 403);



  $user_id = get_user_id($token);



  global $wpdb;



  if (empty($post_id) || empty($user_id))

    return new WP_REST_RESPONSE(["status" => "I parametri sono vuoti"], 500);



  if ($user_id == 0 || $user_id == 1)

    return new WP_REST_RESPONSE(["status" => "Azione non permessa"], 500);



  $is_user = get_user_by("ID", $user_id);



  if ($is_user instanceof WP_User == false)

    return new WP_REST_Response((["status" => "Non esiste nessun account"]), 500);



  $post = get_posts(

    array(

      'post_status' => 'publish',

      'include' => array($post_id),

      'category' => CONTEST_CATEGORY,

    )

  );



  if ($post == null)

    return new WP_REST_RESPONSE(["status" => "Il post non esiste"], 500);



  $likes_table = $wpdb->prefix . 'likes_content_post_petbuy';

  $contest_post_table = $wpdb->prefix . 'contest_post_petbuy';



  $query = $wpdb->prepare("SELECT likes_id FROM $likes_table INNER JOIN $contest_post_table ON post = id WHERE user_id = $user_id AND post_id = $post_id");

  $query_check = $wpdb->get_results($query);



  if (!empty($query_check))

    return dislike_post($user_id, $post_id);



  $query = $wpdb->prepare("INSERT INTO $likes_table (user_id, post) VALUES ( %d, (SELECT id FROM $contest_post_table WHERE post_id = %d))", $user_id, $post_id);

  $query_check = $wpdb->query($query);



  if ($query_check == false)

    return new WP_REST_RESPONSE(["status" => "C'è stato un errore nel registrare il like. Riprova"], 500);



  return new WP_REST_RESPONSE(["status" => "Like registrato!", "action" => "ADD"], 200);

}



function has_user_liked($post, $user)

{

  if ($user == -1)

    return false;



  global $wpdb;

  $likes_table = $wpdb->prefix . 'likes_content_post_petbuy';

  $contest_post_table = $wpdb->prefix . 'contest_post_petbuy';

  $query = $wpdb->prepare("SELECT COUNT(*) AS num_likes FROM $likes_table INNER JOIN $contest_post_table ON post = id WHERE post_id = %d AND user_id = %d", $post, $user);

  $query_check = $wpdb->get_results($query);



  if (intval($query_check[0]->num_likes) > 0)

    return true;



  return false;

}



function create_json($content)

{

  $infos = explode("_", $content);

  $result = array();



  foreach ($infos as $info) {

    $info = explode("|", $info);

    $words[$info[0]] = $info[1];

    $result[] = $words;

  }



  return $result[2];

}



function get_user_post($user_id)

{

  $contest_posts = get_posts(array(

    'category' => CONTEST_CATEGORY,

    'post_status' => 'publish',

    'author' => $user_id,

  ));



  return $contest_posts;

}



function get_most_liked_post($limit)

{

  global $wpdb;

  $likes_table = $wpdb->prefix . 'likes2';

  $query = $wpdb->prepare("SELECT post_id FROM $likes_table ORDER BY num_likes DESC LIMIT " . $limit);

  $query_check = $wpdb->get_results($query);



  if (empty($query_check))

    return NULL;



  foreach ($query_check as $post)

    $ids[] = $post->post_id;



  return $ids;

}



function get_post_link($post_id)

{

  global $wpdb;

  $contest_post_table = $wpdb->prefix . 'contest_post_petbuy';

  $query = $wpdb->prepare("SELECT link FROM $contest_post_table WHERE post_id = %d", $post_id);

  $query_check = $wpdb->get_results($query);



  if (empty($query_check))

    return NULL;



  return esc_url(add_query_arg('l', $query_check[0]->link, "https://petbuy-local.ns0.it:8080/contest/"));

}



function get_post_from_link($link)

{

  global $wpdb;

  $contest_post_table = $wpdb->prefix . 'contest_post_petbuy';

  $query = $wpdb->prepare("SELECT post_id FROM $contest_post_table WHERE link = %s", $link);

  $query_check = $wpdb->get_results($query);



  if (empty($query_check))

    return NULL;



  return $query_check[0]->post_id;

}



function get_single_post($link)

{

  $post_link = get_post_from_link($link);



  if ($post_link == NULL)

    return -1;



  $contest_posts = get_posts(array(

    'category' => CONTEST_CATEGORY,

    'include' => array($post_link),

  ));



  return $contest_posts;

}



function getcontestposts(WP_REST_Request $request)  // PRENDERE L'ID DEL POST E RESTITUIRE LE SUE INFORMAZIONI

{

  $limit = $request->get_param('limit');

  $token = $request->get_param('token');

  $mode = $request->get_param('mode');

  $link = $request->get_param('link');



  if (!isset($limit) || !isset($token) || !isset($mode))

    return new WP_REST_RESPONSE(["status" => "Parametri vuoti"], 500);



  if ($token == -1)

    $user_id = -1;

  else

    $user_id = get_user_id($token);



  if (empty($limit) && $limit <= 0)

    $limit = CONTEST_DEFAULT_LIMIT;



  if ($user_id != -1 && $mode == "user")

    $contest_posts = get_user_post($user_id);



  if (!empty($link) && isset($link) && $mode == "single")

  {

    $contest_posts = get_single_post($link);

    if ($contest_posts == -1)

      return new WP_REST_Response(["status" => 'Nessun post corrisponde a quel link'], 404);

  }



  else if ($mode == "carousel") {

    $contest_posts = get_posts(array(

      'category' => CONTEST_CATEGORY,

      'include' => get_most_liked_post($limit),

      'post_status' => 'publish',

    ));

  } else if ($mode == "all") {

    $contest_posts = get_posts(array(

      'category' => CONTEST_CATEGORY,

      'post_status' => 'publish',

    ));

  }



  if (empty($contest_posts))

    return new WP_REST_RESPONSE(["status" => "Non ci sono post"], 500);



  $posts = array();



  foreach ($contest_posts as $post) {

    $post_content = create_json($post->post_content);

    $post_author = $post->post_author;

    $post_date = $post->post_date;

    $post_id = $post->ID;



    $post_author_name = get_the_author_meta('nickname', $post_author);



    $posts[] = array(

      'post_author' => $post_author_name,

      'post_date' => substr($post_date, 0, 10),

      'post_content' => $post_content,

      'post_id' => $post_id,

      'likes' => get_likes($post_id),

      'has_liked_user' => has_user_liked($post_id, $user_id),

      'link' => get_post_link($post_id),

    );

  }



  return new WP_REST_RESPONSE(array_reverse($posts), 200);

}



function get_ids_of_searched_post($search)

{

  global $wpdb;

  $contest_post_table = $wpdb->prefix . 'contest_post_petbuy';

  $search_target = "%" . $search . "%";

  $query = $wpdb->prepare("SELECT post_id FROM $contest_post_table WHERE animal_name LIKE %s", $search_target);

  $query_check = $wpdb->get_results($query);



  if (empty($query_check))

    return NULL;



  foreach ($query_check as $post)

    $ids[] = $post->post_id;



  return $ids;

}



function contestsearch(WP_REST_Request $request)

{

  $search = $request->get_param('search');

  $token = $request->get_param('token');



  if (!isset($search) || !isset($token))

    return new WP_REST_RESPONSE(["status" => "Parametri vuoti"], 404);



  if ($token == -1)

    $user_id = -1;

  else

    $user_id = get_user_id($token);



  $ids_array = get_ids_of_searched_post($search);



  if ($ids_array == NULL)

    return new WP_REST_RESPONSE(["status" => "Nessun post corrisponde alla tua ricerca"], 404);



  $contest_posts = $contest_posts = get_posts(array(

    'category' => CONTEST_CATEGORY,

    'include' => $ids_array,

    'post_status' => 'publish',

  ));



  if (empty($contest_posts))

    return new WP_REST_RESPONSE(["status" => "Nessun post corrisponde alla tua ricerca"], 404);



  $posts = array();



  foreach ($contest_posts as $post) {

    $post_content = create_json($post->post_content);

    $post_author = $post->post_author;

    $post_date = $post->post_date;

    $post_id = $post->ID;



    $post_author_name = get_the_author_meta('nickname', $post_author);



    $posts[] = array(

      'post_author' => $post_author_name,

      'post_date' => substr($post_date, 0, 10),

      'post_content' => $post_content,

      'post_id' => $post_id,

      'likes' => get_likes($post_id),

      'has_liked_user' => has_user_liked($post_id, $user_id),

      'link' => get_post_link($post_id),

    );

  }

  return new WP_REST_RESPONSE($posts, 200);

}

