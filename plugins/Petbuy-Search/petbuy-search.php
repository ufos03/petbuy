<?php
/*
Plugin Name: Petbuy Search System
Description: Ricerca veloce con Manticore Search, gestione parole canoniche e caching intelligente.
Version:     2.2.0
Author:      Petbuy Dev Team
Text Domain: petbuy-search
*/

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

define( 'PETBUY_SEARCH_VERSION', '2.2.1' );
define( 'PETBUY_SEARCH_PLUGIN_FILE', __FILE__ );
define( 'PETBUY_SEARCH_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'PETBUY_SEARCH_PLUGIN_URL', plugin_dir_url( __FILE__ ) );

require_once PETBUY_SEARCH_PLUGIN_DIR . 'includes/autoloader.php';
Petbuy\Search\Plugin::init();
