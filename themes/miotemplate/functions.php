<?php

require_once WP_CONTENT_DIR . '/themes/miotemplate/api_registar_loader.php';

/**
 * Funzioni di HomepagePetBuy
 *
 * @package WordPress
 * @subpackage HomepagePetBuy
 * @since 1.0.0
 * Description: aggiungi funzioni al tema Wordpress
 */


function fn_theme_scripts(){
  /*includi gli stili*/
  wp_enqueue_style('custom-style',get_stylesheet_uri());
}


add_action('wp_enqueue_scripts','fn_theme_scripts');


//aggiungo altre funzionalità
function fn_theme_supports(){

add_theme_support('title-tag');
add_theme_support('post-thumbnails');
add_theme_support('html5',array('search-form'));
add_theme_support('custom-logo');

}

add_action('after_setup_theme','fn_theme_supports');


//menu di navigazione
function fn_nav_menu(){

	register_nav_menus(array(

		'primary-menu'=>__('Primary Menu','text_domain'),
		'footer-menu'=>__('Footer Menu', 'text_domain')

	));

}

add_action('after_setup_theme', 'fn_nav_menu');


// Aggiunge campo "Icona" alle voci di menu
function aggiungi_campo_icona_menu($item_id, $item, $depth, $args) {
    $icon_url = get_post_meta($item->ID, '_menu_item_icon', true);
    ?>
    <p class="field-icon description description-wide">
        <label for="edit-menu-item-icon-<?php echo $item_id; ?>">
            Icona (URL immagine)<br>
            <input type="text" id="edit-menu-item-icon-<?php echo $item_id; ?>" class="widefat code edit-menu-item-icon" name="menu-item-icon[<?php echo $item_id; ?>]" value="<?php echo esc_attr($icon_url); ?>">
        </label>
    </p>
    <?php
}
add_action('wp_nav_menu_item_custom_fields', 'aggiungi_campo_icona_menu', 10, 4);

// Salva il campo
add_action('wp_update_nav_menu_item', function($menu_id, $menu_item_db_id) {
    if (isset($_POST['menu-item-icon'][$menu_item_db_id])) {
        update_post_meta($menu_item_db_id, '_menu_item_icon', sanitize_text_field($_POST['menu-item-icon'][$menu_item_db_id]));
    }
}, 10, 2);


//chiama il file
require_once get_template_directory() . '/class-walker-icon-menu.php';


//dichiarazione supporto tema woocommerce
function tuo_tema_add_woocommerce_support() {
    add_theme_support( 'woocommerce' );
}
add_action( 'after_setup_theme', 'tuo_tema_add_woocommerce_support' );



//
function add_link_atts($atts){

  $atts['class']= 'nav-link';
  return $atts;
}

add_filter('nav_menu_link_attributes','add_link_atts');



//forza woocommerce a caricare la pag checkout custom
/*add_action('template_redirect', function() {
    if (is_page_template('PageCheckout.php')) {
        // Forza WooCommerce a trattare questa pagina come checkout
        add_filter('woocommerce_is_checkout', '__return_true');
        add_filter('woocommerce_checkout_is_checkout', '__return_true');

        // Rimuove eventuali redirect automatici
        remove_action('template_redirect', 'wc_template_redirect');
    }
});*/





//assegna header diverso a pagine specifiche
function my_custom_get_header() {
    if ( is_page( 'accedi' ) || is_page( 'registrati' ) ) {
        get_template_part( 'header-diverso' ); // Assicurati che header-special.php esista
    } else {
        get_header();
    }
}

//carica entrambi i footer usando js per mostrare e nascondere uno dei due footer in base a dimensione dello schermo
function my_custom_get_footer() {
    if ( is_page( 'accedi' ) || is_page( 'registrati' ) ) {
        // Carica entrambi i footer, ma avvolgili in div identificabili
        echo '<div id="footer-default-wrapper">';
        get_footer(); // Carica footer.php
        echo '</div>';

        echo '<div id="footer-diverso-wrapper" style="display:none;">'; // Inizialmente nascosto
        get_template_part( 'footer-diverso' ); // Carica footer-diverso.php
        echo '</div>';
    } else {
        get_footer(); // Carica il footer predefinito (footer.php) per le altre pagine
    }
}


//aggiungi una classe al tag body per una pagina specifica
function add_custom_body_class_by_template( $classes ) {
    if ( is_page_template( 'PageProfiloUtente.php' ) ) { // Sostituisci template-personalizzato.php
        $classes[] = 'sfondo-profilo-utente';
    }
    return $classes;
}
add_filter( 'body_class', 'add_custom_body_class_by_template' );


//aggiungere una classe al footer di una pagina specifica
function add_class_to_footer_on_specific_page() {
    if ( is_page( 'pagamento' ) ) { // Sostituisci template-personalizzato.php
        add_filter( 'body_class', 'add_footer_class_to_body' );
    }
}
add_action( 'wp_enqueue_scripts', 'add_class_to_footer_on_specific_page' );

function add_footer_class_to_body( $classes ) {
    $classes[] = 'margine'; // Questa classe verrà aggiunta al tag <body>. Potrai poi targettizzare il footer usando il CSS.
    return $classes;
}


//css aggiunto solo per pagina accedi e per pagina registrati
function add_specific_page_css() {
    if ( is_page_template( 'PageAccedi.php' ) || is_page_template( 'PageRegistrati.php' ) ) { // Sostituisci template-personalizzato.php
        echo '<style type="text/css">';
        // Incolla qui tutto il codice CSS che ti ho dato
        echo '

			/* Applica box-sizing: border-box a tutti gli elementi per un box model più intuitivo */
*, *::before, *::after {
    box-sizing: border-box;
}

@media screen and (min-width: 601px) {
    html, body {
        height: 100%;
        margin: 0;
        padding: 0; /* Rimuovi il padding qui se lo metti sul body, o lascialo a 0 */
        /* Rimuovi overflow: hidden dal body o html se vuoi che lo scroll compaia per il padding */
        /* Per questo layout specifico, dove vuoi che tutto rientri nel 100% con padding, */
        /* potresti voler mantenere overflow: hidden, ma box-sizing è la chiave. */
        overflow: hidden; /* Mantenuto per il layout a piena altezza senza scrollbars interne non volute */
    }
}

body {
    /*padding: 20px;*/ /* Qui puoi definire il padding per lintero layout */
    display: flex; /* Rendi il body un contenitore flex per il suo figlio diretto (il container-fluid) */
    flex-direction: column; /* Organizza i figli in colonna */
}

.container-fluid {
    flex-grow: 1; /* Permetti al container-fluid di espandersi per riempire lo spazio disponibile */
    /* Se avevi già d-flex e h-100, queste regole di Bootstrap dovrebbero già funzionare bene con box-sizing */
}

.row.flex-grow-1 {
    height: 100%; /* Assicurati che la riga che contiene le colonne occupi laltezza disponibile */
    display: flex;
}

.col-left, .col-right {
    height: 100%; /* Assicurati che le colonne occupino laltezza completa della riga */
    /* Assicurati che i padding interni alle colonne non causino overflow se il loro contenuto è molto grande */
    /* Potresti voler aggiungere overflow-y: auto; alle colonne se il loro contenuto può superare laltezza */
}





        /* Stile di esempio per le colonne */
        .col-left {
            /*background-color: #f8f9fa;*/ /* Grigio chiaro */
            /*border-right: 1px solid #dee2e6;*/ /* Bordo sottile per separazione visiva */
            /*padding: 20px;*/
            display: flex; /* Usa flexbox per centrare il contenuto verticalmente */
            /*align-items: center;*/ /* Centra verticalmente */
            /*justify-content: center;*/ /* Centra orizzontalmente (opzionale) */
            /*text-align: center;*/
			padding-top: 5px;
        }

        .col-right {
            background-color: #0d6efd; /* Blu di Bootstrap */
            color: white;
            /*padding: 20px;*/
            display: flex; /* Usa flexbox per centrare il contenuto verticalmente */
            align-items: center; /* Centra verticalmente */
            justify-content: center; /* Centra orizzontalmente (opzionale) */
            text-align: center;
        }

        /* Stili aggiuntivi per il contenuto delle colonne, se necessario */
        .content-wrapper {
            width: 100%; /* Limita la larghezza del contenuto */
            /*padding: 20px;*/
            /*box-shadow: 0 0 10px rgba(0,0,0,0.1);*/
            /*background-color: white;*/
            color: black;
            /*border-radius: 8px;*/
        }

        ';
        echo '</style>';
    }
}
add_action( 'wp_head', 'add_specific_page_css' );


//aggiunge una classe al body di accedi
function add_custom_body_class_for_mobile_footer( $classes ) {
    // Sostituisci con nome file pagina specifico della pagina (es. 'accedi' o 'registrati')
    if ( is_page_template( 'PageAccedi.php' ) || is_page_template( 'PageRegistrati.php' ) || is_page_template ('PageProfiloUtente.php') || is_page_template ('PageCarrelloMobile.php') || is_page_template ('PageCarrelloVuotoMobile.php') || is_page( 12 ) )/*page id 12 sarebbe carrello*/ {
        $classes[] = 'no-margin-footer-mobile'; // Aggiunge la classe 'no-margin-footer-mobile' al <body>
    }
    return $classes;
}
add_filter( 'body_class', 'add_custom_body_class_for_mobile_footer' );



//filtro feedbacks cioè reviews
function custom_filter_by_rating( $query ) {
    // Esci se non siamo nella pagina di shop o se non è la query principale
    if ( ! is_shop() || ! $query->is_main_query() ) {
        return;
    }

    // Controlla se il filtro delle stelle è stato selezionato
    if ( isset( $_GET['rating_filter'] ) && ! empty( $_GET['rating_filter'] ) ) {
        $rating = absint( $_GET['rating_filter'] );

        $meta_query = array(
            array(
                'key'     => '_wc_average_rating',
                'value'   => $rating,
                'compare' => '>=',
                'type'    => 'NUMERIC',
            ),
        );

        // Aggiungi la meta query al loop principale
        $query->set( 'meta_query', $meta_query );
    }
}
add_action( 'woocommerce_product_query', 'custom_filter_by_rating' );


//annunci regalo
//annunci regalo
// ====================================================================================================
// PARTE 1: LOGICA DEL FILTRO (MODIFICA LA QUERY PRINCIPALE DI WOOCOMMERCE)
// ====================================================================================================

/**
 * Funzione per filtrare i prodotti in base all'annuncio regalo.
 * Versione aggiornata per gestire diversi formati di salvataggio del campo.
 */
function custom_filter_by_gift_announcement( $query ) {
    // Esci se non è la pagina del negozio o se non è la query principale
    if ( ! is_shop() || ! $query->is_main_query() ) {
        return;
    }
    // Applica il filtro solo se il parametro 'gift_filter' è presente
    if ( isset( $_GET['gift_filter'] ) && 'on' === $_GET['gift_filter'] ) {
        $meta_query = array(
            'relation' => 'AND',
            array(
                'key'     => 'gift_announcement',
                'value'   => ':"1"',
                'compare' => 'LIKE',
            ),
        );
        $query->set( 'meta_query', $meta_query );
    }
}
add_action( 'woocommerce_product_query', 'custom_filter_by_gift_announcement' );

// ====================================================================================================
// PARTE 2: DEFINIZIONE DEL WIDGET PERSONALIZZATO
// ====================================================================================================

/**
 * Widget personalizzato per il filtro degli annunci regalo.
 * Incapsula tutto il codice del form per evitare conflitti.
 */
class Custom_Gift_Announcement_Widget extends WP_Widget {

    function __construct() {
        parent::__construct(
            'custom_gift_announcement_widget',
            'Filtro Annunci Regalo',
            array( 'description' => 'Filtra i prodotti che hanno un annuncio regalo.' )
        );
    }

    public function widget( $args, $instance ) {
        echo $args['before_widget'];
        if ( ! empty( $instance['title'] ) ) {
            echo $args['before_title'] . apply_filters( 'widget_title', $instance['title'] ) . $args['after_title'];
        }

        // Codice per il form del filtro
        ?>
        <form action="<?php echo esc_url( home_url( '/negozio/' ) ); ?>" method="get" class="gift-filter-form">
            <div class="annuncio-regalo-container d-flex justify-content-between align-items-center">
                <?php
                $checked = ( isset( $_GET['gift_filter'] ) && 'on' === $_GET['gift_filter'] ) ? 'checked' : '';

                // Calcola il numero di prodotti con "Annuncio regalo" (CORREZIONE QUI)
                $args_count = array(
                    'post_type'      => 'product',
                    'posts_per_page' => -1,
                    'meta_query'     => array(
                        array(
                            'key'     => 'gift_announcement',
                            'value'   => ':"1"', // Correzione del valore
                            'compare' => 'LIKE', // Correzione del tipo di comparazione
                        ),
                    ),
                );
                $gift_products_count = new WP_Query( $args_count );
                $count = $gift_products_count->found_posts;
                wp_reset_postdata();
                ?>
                <div class="form-check">
                    <input class="form-check-input me-2" type="checkbox" id="gift-announcement-<?php echo esc_attr($this->id); ?>" name="gift_filter" <?php echo $checked; ?>>
                    <label class="form-check-label" for="gift-announcement-<?php echo esc_attr($this->id); ?>">
                        Annunci regalo
                    </label>
                </div>
                <?php
                if ( 'checked' === $checked && $count > 0 ) :
                ?>
                    <span class="small float-end color-arancio filtro-numero-stelle"><?php echo $count; ?></span>
                <?php endif; ?>
            </div>
            <button type="submit" style="display:none;"></button>
        </form>
        <script>
            jQuery(document).ready(function($) {
                $('#gift-announcement-<?php echo esc_attr($this->id); ?>').change(function() {
                    $(this).closest('form').submit();
                });
            });
        </script>
        <?php
        echo $args['after_widget'];
    }

    public function form( $instance ) {
        $title = ! empty( $instance['title'] ) ? $instance['title'] : 'Filtro Annunci Regalo';
        ?>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_attr_e( 'Title:', 'text_domain' ); ?></label>
            <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
        </p>
        <?php
    }

    public function update( $new_instance, $old_instance ) {
        $instance = array();
        $instance['title'] = ( ! empty( $new_instance['title'] ) ) ? sanitize_text_field( $new_instance['title'] ) : '';
        return $instance;
    }
}

function register_custom_gift_widget() {
    register_widget( 'Custom_Gift_Announcement_Widget' );
}
add_action( 'widgets_init', 'register_custom_gift_widget' );

// ====================================================================================================
// PARTE 3: VISUALIZZAZIONE NEL TEMA
// ====================================================================================================

/**
 * Funzione per visualizzare il widget "Filtro Annunci Regalo" solo su desktop.
 */
function custom_desktop_gift_announcement_widget() {
    if ( wp_is_mobile() ) {
        return;
    }
    the_widget( 'Custom_Gift_Announcement_Widget' );
}

/**
 * Funzione per visualizzare il widget "Filtro Annunci Regalo" solo su mobile.
 */
function custom_mobile_gift_announcement_widget() {
    if ( ! wp_is_mobile() ) {
        return;
    }
    the_widget( 'Custom_Gift_Announcement_Widget' );
}




//scelta brand pag shop
function custom_filter_by_brand( $query ) {
    if ( is_admin() || ! $query->is_main_query() || ! is_shop() ) {
        return;
    }

    if ( isset( $_GET['product_brand'] ) && is_array( $_GET['product_brand'] ) ) {
        // Sanifica e filtra per rimuovere eventuali valori vuoti
        $brand_slugs = array_filter( array_map( 'sanitize_text_field', $_GET['product_brand'] ) );

        if ( ! empty( $brand_slugs ) ) {
            $tax_query = array(
                array(
                    'taxonomy' => 'product_brand',
                    'field'    => 'slug',
                    'terms'    => $brand_slugs,
                    'operator' => 'IN',
                ),
            );
            $query->set( 'tax_query', $tax_query );
        }
    }
}
add_action( 'woocommerce_product_query', 'custom_filter_by_brand' );


//mostra 8 prodotti per pagina in woocommerce solo nella versione mobile
function change_product_per_page_on_mobile( $query ) {
    // Controlla se è la pagina principale del negozio o una pagina di archivio prodotti
    // E verifica se il dispositivo è mobile
    if ( $query->is_main_query() && ( is_shop() || is_product_taxonomy() ) && wp_is_mobile() ) {
        // Imposta il numero di prodotti a 8
        $query->set( 'posts_per_page', 8 );
    }
}
add_action( 'pre_get_posts', 'change_product_per_page_on_mobile' );





//rimozione pulsante standard woocommerce
add_action( 'init', 'remove_default_checkout_button_from_cart' );

function remove_default_checkout_button_from_cart() {
    remove_action( 'woocommerce_proceed_to_checkout', 'woocommerce_button_proceed_to_checkout', 20 );
}



//personalizzazione pulsante checkout
add_action( 'woocommerce_proceed_to_checkout', 'add_custom_checkout_button', 10 );

function add_custom_checkout_button() {
    $checkout_url = esc_url( wc_get_checkout_url() );
    $template_uri = get_template_directory_uri();
    
    // Recupera il totale del carrello formattato
    $cart_total = WC()->cart->get_cart_total();
    
    echo '<a href="' . $checkout_url . '" style="width:100%;" class="btn btn-warning noacapo mt-4 fortablet pad-procedi-carrello" role="button">
        <div class="d-inline-block d-sm-none">' . $cart_total . ' -&nbsp;</div>Procedi al check out 
        <img src="' . $template_uri . '/ufficiale/impronte-pulsante-checkout.svg" alt="impronte" class="d-none d-md-inline-block" />
    </a>';
}


////filtro dei prezzi visualizzazione in alternativa su desktop o su mobile 
function custom_desktop_price_filter() {
    if ( wp_is_mobile() ) return; // Interrompe l'esecuzione se è un dispositivo mobile
    the_widget( 'WC_Widget_Price_Filter' );
}

function custom_mobile_price_filter() {
    if ( ! wp_is_mobile() ) return; // Interrompe l'esecuzione se non è un dispositivo mobile
    the_widget( 'WC_Widget_Price_Filter' );
}



//classi aggiuntive img principale singolo prodotto
/**
 * Aggiungi una classe CSS all'immagine principale del prodotto.
 * @param string $html L'HTML dell'immagine
 * @param int $post_thumbnail_id L'ID dell'immagine
 * @return string L'HTML modificato
 */
function add_custom_class_to_main_image( $html, $post_thumbnail_id ) {
    $main_image_classes = 'w-100 h-auto';
    $html = str_replace( 'wp-post-image', 'wp-post-image ' . esc_attr( $main_image_classes ), $html );
    return $html;
}
add_filter( 'woocommerce_single_product_image_thumbnail_html', 'add_custom_class_to_main_image', 10, 2 );



//utile per funzioni woocommerce
add_action( 'wp_ajax_woocommerce_ajax_add_to_cart', 'custom_ajax_add_to_cart' );
add_action( 'wp_ajax_nopriv_woocommerce_ajax_add_to_cart', 'custom_ajax_add_to_cart' );

function custom_ajax_add_to_cart() {
    if ( ! isset( $_POST['product_id'] ) ) {
        wp_send_json_error( 'ID prodotto mancante' );
        return;
    }

    $product_id = absint( $_POST['product_id'] );
    $quantity   = isset( $_POST['quantity'] ) ? wc_stock_amount( $_POST['quantity'] ) : 1;

    if ( ! $product_id || $quantity < 1 ) {
        wp_send_json_error( 'Dati non validi' );
        return;
    }

    $product = wc_get_product( $product_id );
    if ( ! $product || ! $product->is_purchasable() ) {
        wp_send_json_error( 'Prodotto non acquistabile' );
        return;
    }

    $added = WC()->cart->add_to_cart( $product_id, $quantity );

    if ( $added ) {
        wp_send_json_success( array(
            'cart_hash' => WC()->cart->get_cart_hash(),
        ) );
    } else {
        wp_send_json_error( 'Impossibile aggiungere al carrello' );
    }

    wp_die();
}



//attivazione jquery
add_action( 'wp_enqueue_scripts', function() {
    wp_enqueue_script( 'jquery' );
    wp_localize_script( 'jquery', 'wc_add_to_cart_params', array(
        'ajax_url' => admin_url( 'admin-ajax.php' )
    ) );
});


//redirect al carrello
add_action( 'template_redirect', function() {
    if ( isset($_GET['add-to-cart']) && is_cart() ) {
        wc_add_notice( 'Prodotto aggiunto al carrello. Procedi al checkout.', 'success' );
        wp_safe_redirect( wc_get_checkout_url() );
        exit;
    }
});










///////////

/**
 * Personalizza il layout e il comportamento dei campi del checkout.
 */

add_filter( 'woocommerce_checkout_fields', 'personalizza_campi_checkout' );

function personalizza_campi_checkout( $fields ) {
    
    // Rimuove i campi facoltativi come richiesto in precedenza
    unset( $fields['billing']['billing_company'] );
    unset( $fields['billing']['billing_address_2'] );
    unset( $fields['shipping']['shipping_company'] );
    unset( $fields['shipping']['shipping_address_2'] );
    unset( $fields['order']['order_comments'] );
    unset( $fields['billing']['billing_email'] );
    
    // Converte tutti i label restanti in placeholder
    $campi_da_modificare = array('billing', 'shipping');
    
    foreach ($campi_da_modificare as $campo_tipo) {
        foreach ( $fields[$campo_tipo] as $campo_id => &$campo_dati ) {
            // Salta i campi che non hanno un label (come il campo del paese)
            if ( isset($campo_dati['label']) && !empty($campo_dati['label']) ) {
                $campo_dati['placeholder'] = $campo_dati['label'];
                
                // Aggiunge un asterisco ai campi obbligatori
                if ( isset( $campo_dati['required'] ) && $campo_dati['required'] ) {
                    $campo_dati['placeholder'] .= ' *';
                }
                
                // Imposta il label su vuoto
                $campo_dati['label'] = '';
            }
        }
    }

    return $fields;
}




//////
/**
 * Personalizza il pulsante "Effettua ordine" con il tag button e classi personalizzate.
 */
/*add_filter( 'woocommerce_order_button_html', 'personalizza_pulsante_ordine' );

function personalizza_pulsante_ordine() {
    $order_button_text = 'Paga ora'; // Testo del pulsante

    return '<button type="submit" class="button alt btn btn-warning noacapo" name="woocommerce_checkout_place_order" id="place_order" style="width:100%;" value="' . esc_attr( $order_button_text ) . '">' . esc_html( $order_button_text ) . '</button>';
}*/



/////////////////
/**
 * Rimuove i metodi di pagamento di default di WooCommerce per evitare conflitti.
 */
add_filter( 'woocommerce_available_payment_gateways', 'nascondi_metodi_pagamento_default', 10, 1 );

function nascondi_metodi_pagamento_default( $available_gateways ) {
    // Rimuove i metodi di pagamento che non vuoi (es. 'cod' = Contrassegno, 'bacs' = Bonifico, 'cheque' = Assegno)
    unset( $available_gateways['cod'] );
    unset( $available_gateways['bacs'] );
    unset( $available_gateways['cheque'] );
    return $available_gateways;
}

/**
 * Aggiunge una sezione di pagamento personalizzata prima di quella di default.
 */
add_action( 'woocommerce_checkout_payment', 'mostra_metodi_pagamento_personalizzati', 5 );

function mostra_metodi_pagamento_personalizzati() {
    ?>
    <h3 id="payment_heading">Metodo di pagamento</h3>
    <div class="wc_payment_methods payment_methods methods">
        <div class="wc_payment_method payment_method_order_test bg-white">
            <div class="payment-method mt-4 mb-0">
                <div class="form-check py-2 carte-spunta d-flex align-items-center justify-content-between" style="border: 1px solid #ccc;border-radius: 5px;">
                    <div class="d-flex align-items-center">
                        <input id="payment_method_order_test" type="radio" class="input-radio form-check-input me-2 mb-1" name="payment_method" value="order_test" checked="checked" />
                        <label class="form-check-label mb-0 fw-normal small" for="payment_method_order_test">
                            Carta di credito
                        </label>
                    </div>
                    <span class="float-end">
                        <img src="<?php echo esc_url( get_template_directory_uri() . '/ufficiale/carte-di-credito-metodo-di-pagamento.svg' ); ?>" alt="carte di credito"/>
                    </span>
                </div>
            </div>
            <div id="payment_fields_order_test" class="payment_box payment_method_order_test carte" style="margin-top:30px!important;">
                <input type="hidden" name="woocommerce-order-test-result" value="success" />
                <div class="mb-3">
                    <div class="input-group">
                        <input type="text" class="form-control edit-form-header" style="background-color: #faf9f8;" id="cardNumber" placeholder="Numero carta" name="order-test-card-number">
                        <span class="input-group-text" style="background-color:#faf9f8;border:0;"><img src="<?php echo esc_url( get_template_directory_uri() . '/ufficiale/lucchetto.svg' ); ?>" alt="lucchetto"/></span>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <input type="text" class="form-control edit-form-header m-input" style="background-color: #faf9f8;" id="expiryDate" placeholder="Data di scadenza (MM / YY)" name="order-test-expiry-date">
                    </div>
                    <div class="col-md-6">
                        <div class="input-group">
                            <input type="text" class="form-control edit-form-header" style="background-color: #faf9f8;" id="cvv" placeholder="Codice di sicurezza" name="order-test-cvv">
                            <span class="input-group-text" style="background-color:#faf9f8;border:0;"><img src="<?php echo esc_url( get_template_directory_uri() . '/ufficiale/cvv.svg' ); ?>" alt="cvv"/></span>
                        </div>
                    </div>
                </div>
                <div class="mb-3">
                    <input type="text" class="form-control edit-form-header" style="background-color: #faf9f8;" id="cardHolderName" placeholder="Nome del proprietario" name="order-test-card-holder-name">
                </div>
                <div class="pb-4 form-check">
                    <input type="checkbox" class="woocommerce-form__input woocommerce-form__input-checkbox input-checkbox form-check-input" name="same_as_shipping" id="same_as_shipping" checked />
                    <label for="same_as_shipping" class="woocommerce-form__label woocommerce-form__label-for-checkbox checkbox form-check-label mb-0 fw-normal small">
                        <span class="woocommerce-terms-and-conditions-checkbox-text">Utilizza l'indirizzo di spedizione come fattura</span>
                    </label>
                </div>
            </div>
        </div>
        <div class="wc_payment_method payment_method_cod bg-white">
            <div class="payment-method mb-5" style="margin-top:-2px!important;">
                <div class="form-check py-2 carte-spunta spazio-link-img-top" style="border: 1px solid #ccc;border-radius: 5px;">
                    <input id="payment_method_cod" type="radio" class="input-radio form-check-input me-2 mb-1" name="payment_method" value="cod" />
                    <label class="form-check-label mb-0 fw-normal small mt-1" for="payment_method_cod">
                        Altri metodi di pagamento
                    </label>
                </div>
            </div>
            <div id="payment_fields_cod" class="payment_box payment_method_cod" style="display: none; margin-top:30px!important;padding:0px 35px 0px 35px!important;">
                <div class="mb-3 pb-4">
                    <textarea class="form-control" id="otherDetails" rows="3" placeholder="Inserisci i dettagli per altri metodi di pagamento" name="cod_other_details"></textarea>
                </div>
            </div>
        </div>
    </div>

    <div class="form-row place-order">
        <noscript>
            <?php esc_html_e( 'Since your browser does not support JavaScript, or it is disabled, please ensure you click the **Update Totals** button before placing your order. You may be charged more than the amount stated above if you fail to do so.', 'woocommerce' ); ?>
            <br/><button type="submit" class="button alt" name="woocommerce_checkout_update_totals" value="<?php esc_attr_e( 'Update totals', 'woocommerce' ); ?>"><?php esc_html_e( 'Update totals', 'woocommerce' ); ?></button>
        </noscript>
        <div class="form-row woocommerce-terms-and-conditions border-0 shadow-none bg-transparent">
            <div class="pb-4 form-check ps-0">
                <input type="checkbox" class="woocommerce-form__input woocommerce-form__input-checkbox input-checkbox mt-2" name="terms" id="terms" required />
                <label for="terms" class="woocommerce-form__label woocommerce-form__label-for-checkbox checkbox">
                    <span class="woocommerce-terms-and-conditions-checkbox-text">Ho letto e accetto i termini e le condizioni del sito web</span>
                </label>
            </div>
        </div>
        <button type="submit" class="button alt btn btn-warning noacapo" style="width:100%;" name="woocommerce_checkout_place_order" id="place_order" value="<?php esc_attr_e( 'Paga ora', 'your-text-domain' ); ?>"><?php esc_html_e( 'Paga ora', 'your-text-domain' ); ?></button>
    </div>
    <?php
}

add_action( 'wp_footer', 'aggiungi_script_custom_pagamento_finale' );

function aggiungi_script_custom_pagamento_finale() {
    if ( ! is_checkout() ) {
        return;
    }
    ?>
    <script type="text/javascript">
        jQuery(document).ready(function($) {
            $('.payment_box').hide();
            $('li.wc_payment_method input[name="payment_method"]:checked').closest('li').find('.payment_box').show();

            $('body').on('change', 'input[name="payment_method"]', function() {
                $('.payment_box').slideUp(200);
                $('li.wc_payment_method input[name="payment_method"]:checked').closest('li').find('.payment_box').slideDown(200);
                $('body').trigger('update_checkout');
            });
        });
    </script>
    <?php
}


//
/**
 * Rende obbligatoria l'accettazione dei termini e condizioni.
 */
add_action( 'woocommerce_checkout_process', 'verifica_termini_e_condizioni' );

function verifica_termini_e_condizioni() {
    // Controlla se la casella "terms" non è stata spuntata
    if ( ! isset( $_POST['terms'] ) ) {
        // Se non è spuntata, aggiunge un avviso di errore
        wc_add_notice( 'Per favore, leggi e accetta i termini e le condizioni per procedere con l’ordine.', 'error' );
    }
}


//conbina una classe preesistente a termini e condizioni
function aggiungi_classe_checkbox_termini_condizioni() {
    // Controlla che siamo nella pagina di checkout
    if ( ! is_checkout() ) {
        return;
    }
    ?>
    <script type="text/javascript">
        jQuery(document).ready(function($) {
            // Seleziona il checkbox dei termini e condizioni e aggiunge la classe
            var checkboxTerms = $('input#terms');
            if (checkboxTerms.length > 0) {
                checkboxTerms.addClass('form-check-input');
            }
        });
    </script>
    <?php
}
add_action( 'wp_footer', 'aggiungi_classe_checkbox_termini_condizioni' );



///////
/**
 * Abilita l'azione AJAX personalizzata per l'aggiornamento del carrello nel checkout.
 */
function attiva_aggiornamento_ajax_checkout() {
    add_action( 'wp_ajax_custom_update_checkout_cart', 'gestisci_aggiornamento_ajax_checkout' );
    add_action( 'wp_ajax_nopriv_custom_update_checkout_cart', 'gestisci_aggiornamento_ajax_checkout' );
}
add_action( 'init', 'attiva_aggiornamento_ajax_checkout' );

/**
 * Gestisce l'aggiornamento del carrello e la risposta AJAX.
 */
function gestisci_aggiornamento_ajax_checkout() {
    if ( ! isset( $_POST['cart_item_key'] ) || ! isset( $_POST['new_qty'] ) ) {
        wp_send_json_error( 'Dati mancanti.' );
    }

    $cart_item_key = sanitize_text_field( $_POST['cart_item_key'] );
    $new_qty = absint( $_POST['new_qty'] );

    if ( WC()->cart->set_quantity( $cart_item_key, $new_qty, false ) ) {
        // Ricalcola i totali del carrello.
        WC()->cart->calculate_totals();

        // Ottieni il riepilogo dell'ordine aggiornato.
        ob_start();
        woocommerce_order_review();
        $order_review_html = ob_get_clean();

        wp_send_json_success( array(
            'order_review_html' => $order_review_html,
            'cart_total_html'   => WC()->cart->get_cart_total(),
        ) );
    } else {
        wp_send_json_error( 'Aggiornamento fallito.' );
    }
}

/**
 * Aggiunge lo script JavaScript personalizzato nel footer del sito.
 */
function aggiungi_script_aggiorna_quantita_checkout() {
    if ( ! is_checkout() ) {
        return;
    }
    ?>
    <script type="text/javascript">
        jQuery(document).ready(function($) {
            $(document).on('click', '.quantity-button.plus, .quantity-button.minus', function(e) {
                e.preventDefault();
                
                var button = $(this);
                var container = button.closest('.cart-item-custom');
                var quantityInput = container.find('input.qty');
                
                // Rileva la chiave del prodotto dal nome dell'input
                var nameAttr = quantityInput.attr('name');
                var cartItemKey = nameAttr.match(/cart\[([^\]]*)\]/)[1];
                
                var currentQuantity = parseInt(quantityInput.val(), 10);
                var newQuantity;

                if (button.hasClass('plus')) {
                    newQuantity = currentQuantity + 1;
                } else {
                    newQuantity = currentQuantity - 1;
                }

                if (newQuantity < 1) {
                    return;
                }

                // Disabilita i pulsanti per evitare click multipli durante l'aggiornamento
                $('.quantity-button').prop('disabled', true);
                
                $('body').addClass('updating-checkout');

                // Invia la richiesta AJAX diretta
                $.ajax({
                    type: 'POST',
                    url: '<?php echo admin_url( 'admin-ajax.php' ); ?>',
                    data: {
                        action: 'woocommerce_update_cart_quantity',
                        cart_item_key: cartItemKey,
                        quantity: newQuantity,
                        security: wc_checkout_params.update_order_review_nonce
                    },
                    success: function(response) {
                        if (response.success) {
                            // Ricarica la pagina per garantire che tutti gli elementi si aggiornino
                            location.reload(); 
                        } else {
                            console.error('Errore nell\'aggiornamento del carrello:', response.data);
                        }
                    },
                    error: function() {
                        console.error('Errore di connessione o del server.');
                    },
                    complete: function() {
                        $('.quantity-button').prop('disabled', false);
                        $('body').removeClass('updating-checkout');
                    }
                });
            });
        });
    </script>
    <?php
}
add_action( 'wp_footer', 'aggiungi_script_aggiorna_quantita_checkout' );

// Aggiungi l'azione AJAX nel tuo file functions.php se non è già presente
add_action( 'wp_ajax_woocommerce_update_cart_quantity', 'woocommerce_ajax_update_cart_quantity' );
add_action( 'wp_ajax_nopriv_woocommerce_update_cart_quantity', 'woocommerce_ajax_update_cart_quantity' );

function woocommerce_ajax_update_cart_quantity() {
    $cart_item_key = $_POST['cart_item_key'];
    $quantity = $_POST['quantity'];

    WC()->cart->set_quantity( $cart_item_key, $quantity );
    
    wp_send_json_success();
}



//attivare pulsante aggiunta a carrello api icona borsa in shop
add_action('wp_ajax_woocommerce_json_api_add_to_cart', 'handle_add_to_cart_ajax');
add_action('wp_ajax_nopriv_woocommerce_json_api_add_to_cart', 'handle_add_to_cart_ajax');

function handle_add_to_cart_ajax() {
    // Sicurezza: verifica il nonce (omesso per semplicità, ma raccomandato)
    
    $product_id = isset($_POST['product_id']) ? intval($_POST['product_id']) : 0;
    $quantity   = isset($_POST['quantity']) ? intval($_POST['quantity']) : 1;
    
    if ($product_id > 0) {
        $added = WC()->cart->add_to_cart( $product_id, $quantity );
        
        if ($added) {
            wp_send_json_success(array('message' => 'Prodotto aggiunto con successo!'));
        } else {
            wp_send_json_error(array('message' => 'Impossibile aggiungere il prodotto al carrello.'));
        }
    } else {
        wp_send_json_error(array('message' => 'ID Prodotto non valido.'));
    }
    
    wp_die(); // Termina la richiesta AJAX
}


/////////////////////////////////
////////////////////////////////
/**
 * ==============================================================================
 * INTEGRAZIONE API ESTERNA PER FILTRI E LISTA PRODOTTI (DECOUPLED)
 * URL API: https://petbuy-local.ns0.it:8080/wp-json/api/v1/advertisements/read/all
 * Obiettivo: Sostituire la query standard di WooCommerce con una chiamata all'API.
 * ==============================================================================
 */

// ==============================================================================
// 1. CONFIGURAZIONE E UTILITY API
// ==============================================================================

// Definisce l'URL base dell'API
define( 'MY_API_ENDPOINT', 'https://petbuy-local.ns0.it:8080/wp-json/api/v1/advertisements/read/all' );

/**
 * Funzione centralizzata per recuperare i dati (advertisements) dall'API esterna.
 *
 * @param array $filters Array di parametri per la query API (es. ['category' => 'cani']).
 * @return array|WP_Error Array dei dati decodificati o oggetto WP_Error.
 */
function my_api_fetch_data( $filters = array() ) {
    
    // *** ATTENZIONE: Questa funzione ORA RESTITUISCE DATI MOCK E NON CHIAMA L'API ESTERNA ***
    // Questo serve per bypassare il problema di Timeout del tuo server.
    
    $mock_data = [
        'advertisements' => [
            // Inserisci l'ID di un prodotto REALE nel tuo WooCommerce.
            // Puoi trovare l'ID nella sezione Prodotti > Tutti i prodotti.
            [ 'wc_post_id' => 11277, 'title' => 'Prodotto Mock 1' ], 
            
            // Inserisci l'ID di un altro prodotto REALE.
            [ 'wc_post_id' => 11273, 'title' => 'Prodotto Mock 2' ],
            
            // Aggiungi qui altri prodotti mock
        ],
        // Lasciamo la chiave filter_data vuota, sarà gestita da JS
        'filter_data' => [] 
    ];

    // Restituisci i dati finti immediatamente (senza ritardo o errore)
    return $mock_data; 
}

// ==============================================================================
// 2. BYPASS WOOCOMMERCE QUERY E INIEZIONE DATI
// ==============================================================================

/**
 * Blocca la query standard di WooCommerce sulla pagina archivio prodotti ('/negozio/').
 * Evita interrogazioni inutili al database locale.
 */
function my_api_disable_wc_query( $query ) {
    // Esegue solo per la query principale e sull'archivio prodotti
    if ( is_admin() || ! $query->is_main_query() || ! is_post_type_archive( 'product' ) ) {
        return;
    }

    // Forza la query a non restituire nulla dal database locale
    $query->set( 'posts_per_page', 0 ); 
    $query->set( 'post__in', array( 0 ) ); 
}
add_action( 'pre_get_posts', 'my_api_disable_wc_query', 999 );

/**
 * Sostituisce il loop di WooCommerce con i dati ottenuti dall'API.
 * Gestisce il caricamento INIZIALE della pagina (/negozio/).
 */
function my_api_render_products() {
    if ( ! is_post_type_archive( 'product' ) ) {
        return;
    }
    
    // Il PHP non deve più renderizzare i prodotti al caricamento.
    // Il caricamento iniziale e tutti i filtri saranno gestiti dal JavaScript.
    
    // Lascia qui una struttura vuota per i filtri se l'hai definita nel template,
    // altrimenti il JS non saprà dove scrivere le opzioni di filtro.
    
    // Se hai bisogno di debug:
    // echo '';
    
    // NON CHIAMARE wc_no_products_found() qui, altrimenti il messaggio appare.
    return;
}
// Lascia l'hook inalterato: add_action( 'woocommerce_after_main_content', 'my_api_render_products', 10 );
// NON toccare l'add_action qui sotto
// Rimuovi tutti gli altri add_action per my_api_render_products
add_action( 'woocommerce_after_main_content', 'my_api_render_products', 10 );
// add_action( 'woocommerce_before_shop_loop', 'my_api_render_products', 1 );

// Aggancia la funzione all'head, ma metti l'output in un div nascosto
//add_action( 'wp_head', 'my_api_render_products', 1 );

// ==============================================================================
// 3. PREPARAZIONE JAVASCRIPT PER FILTRI DINAMICI (AJAX)
// ==============================================================================

/**
 * Accoda lo script JavaScript che gestirà il filtraggio dinamico.
 */
/**
 * Funzione per accodare gli script JS nel tema child.
 */
function my_api_enqueue_scripts() {
    // 1. Definisce l'URI base del tema child (e quindi di miotemplate)
    $base_uri = get_stylesheet_directory_uri();
    
    if ( is_admin() || ! is_post_type_archive( 'product' ) ) {
        return;
    }

    // ---------------------------------------------------------------------
    // 1. CLASSE BASE: wishlistElement.js (dipendenza di main.js)
    // PERCORSO: /wishlist_frontend/wishlistElement.js
    // ---------------------------------------------------------------------
    wp_enqueue_script(
        'my-wishlist-element-js',
        $base_uri . '/wishlist_frontend/wishlistElement.js', // Percorso corretto
        array(), 
        '1.0',
        true 
    );

    // ---------------------------------------------------------------------
    // 2. LOGICA PRINCIPALE: main.js (dipendenza di api-filters.js)
    // PERCORSO: /wishlist_frontend/main.js
    // ---------------------------------------------------------------------
    wp_enqueue_script(
        'my-wishlist-main-js',
        $base_uri . '/wishlist_frontend/main.js', // Percorso corretto
        array('jquery', 'my-wishlist-element-js'), // Dipende da jQuery e dalla classe base
        '1.0',
        true 
    );

    // ---------------------------------------------------------------------
    // 3. LOGICA FILTRI: api-filters.js (usa le funzioni globali Wishlist)
    // PERCORSO: /js/api-filters.js
    // ---------------------------------------------------------------------
    wp_enqueue_script( 
        'my-api-filters-js', 
        $base_uri . '/js/api-filters.js', 
        array('jquery', 'my-wishlist-main-js'), // Dipende da main.js
        '1.0', 
        true 
    );
    
    // Passa le variabili PHP necessarie
    wp_localize_script( 'my-api-filters-js', 'MyApiSettings', array(
        'apiUrl' => MY_API_ENDPOINT,
        'productContainerSelector' => '.products', 
        'filterContainerId' => 'desktop-sidebar-col', 
        'apiFiltersWrapper' => '#api-filters-wrapper', 
        'templateUrl' => $base_uri,
    ));
}
add_action( 'wp_enqueue_scripts', 'my_api_enqueue_scripts' );


/**
 * Filtro per aggiungere type="module" agli script che usano import/export.
 * Senza questo, i moduli ES6 causano l'errore MIME type se non sono riconosciuti.
 */
function add_type_module_to_wishlist_scripts( $tag, $handle, $src ) {
    // Gli script che usano import/export (wishlistElement.js e main.js)
    $module_scripts = array( 'my-wishlist-element-js', 'my-wishlist-main-js' );

    if ( in_array( $handle, $module_scripts, true ) ) {
        // Sostituisce il tag <script> standard con l'attributo type="module"
        $tag = '<script type="module" src="' . esc_url( $src ) . '"></script>';
    }

    return $tag;
}
add_filter( 'script_loader_tag', 'add_type_module_to_wishlist_scripts', 10, 3 );

/**
 * Funzione per nascondere o rimuovere i filtri nativi di WooCommerce,
 * se il tuo tema li inietta nella sidebar.
 */
function my_api_disable_wc_native_widgets() {
    // Esempio: rimuove l'azione standard che visualizza la sidebar WC
    // Potrebbe non funzionare per tutti i temi, potresti aver bisogno di agire a livello di tema
    // remove_action( 'woocommerce_sidebar', 'woocommerce_get_sidebar', 10 );
    
    // Altrimenti, considera di nascondere i widget nativi tramite CSS o rimuovendoli
    // manualmente dalla configurazione dei widget di WordPress.
}
add_action( 'widgets_init', 'my_api_disable_wc_native_widgets', 99 );




// ABILITA CORS PER LE CHIAMATE ALL'API DI TERZE PARTI

function add_cors_headers_to_api_response() {
    // Controlla se la richiesta è per il tuo endpoint specifico (non strettamente necessario, ma buona pratica)
    if ( strpos( $_SERVER['REQUEST_URI'], '/wp-json/api/v1/advertisements/read/all' ) !== false ) {
        header( 'Access-Control-Allow-Origin: *' );
        // Puoi limitare l'accesso al tuo dominio specifico per maggiore sicurezza:
        // header( 'Access-Control-Allow-Origin: https://petbuy-local.ns0.it:8080' );
        
        header( 'Access-Control-Allow-Methods: GET, POST, OPTIONS' );
        header( 'Access-Control-Allow-Headers: Content-Type, Authorization' );
    }
}
add_action( 'rest_api_init', 'add_cors_headers_to_api_response' );




///////
// =================================================================
// FUNZIONE PER AGGIUNGERE IL PRODOTTO AL CARRELLO TRAMITE AJAX
// =================================================================

/**
 * Gestisce la richiesta AJAX per aggiungere un prodotto al carrello
 * utilizzando l'azione 'custom_api_add_to_cart'.
 */
function handle_custom_api_add_to_cart() {
    // La funzione wp_die() è fondamentale in una chiamata AJAX di WordPress.

    // 1. Controlla che l'ID del prodotto sia stato inviato
    if ( empty( $_POST['product_id'] ) ) {
        wp_send_json_error( array( 
            'message' => 'ID prodotto mancante nella richiesta POST.'
        ) );
        wp_die();
    }

    $raw_id = sanitize_text_field( $_POST['product_id'] );
    $product_id = intval( $raw_id );
    $quantity = 1; // Quantità fissa, modificala se necessario

    // 2. Tenta l'aggiunta al carrello di WooCommerce
    // Questa funzione fallisce se l'ID non è un prodotto WC acquistabile (es. annuncio CPT, esaurito, in bozza).
    $cart_item_key = WC()->cart->add_to_cart( $product_id, $quantity );

    if ( $cart_item_key ) {
        // SUCCESS: Aggiunta al carrello riuscita
        
        // OPTIONAL: Aggiorna l'icona del carrello nel frontend
        // Questa parte dipende dal tuo tema, ma è il modo standard per rinfrescare l'interfaccia.
        ob_start();
        do_action( 'woocommerce_ajax_added_to_cart', $product_id );
        $fragments = apply_filters( 'woocommerce_add_to_cart_fragments', array() );
        $fragments['.widget_shopping_cart_content'] = ob_get_clean(); 
        
        wp_send_json_success( array( 
            'message' => 'Prodotto aggiunto con successo!',
            'fragments' => $fragments, // Invia i frammenti aggiornati del carrello
            'product_id' => $product_id
        ) );

    } else {
        // FAILURE: WooCommerce ha rifiutato l'ID
        wp_send_json_error( array( 
            'message' => 'Impossibile aggiungere il prodotto al carrello (WC fallito o ID non valido).',
            'failed_id_received' => $product_id, // Utile per il debug in console!
            'raw_id' => $raw_id
        ) );
    }

    wp_die();
}

// =================================================================
// REGISTRAZIONE DEGLI HOOK AJAX (Necessari!)
// =================================================================

// Hook per utenti loggati
add_action( 'wp_ajax_custom_api_add_to_cart', 'handle_custom_api_add_to_cart' );

// Hook per utenti non loggati
add_action( 'wp_ajax_nopriv_custom_api_add_to_cart', 'handle_custom_api_add_to_cart' );





 ?>
