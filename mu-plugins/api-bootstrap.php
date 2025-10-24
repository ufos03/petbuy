<?php
/**
 * Plugin Name: API Bootstrap
 * Description: Carica automaticamente api_registar_loader.php dal tema Shopic_Shild.
 * Version: 1.0.0
 * Must Use: true
 */

if (!defined('ABSPATH')) { exit; }

// Percorso del file da includere
$api_file = WP_CONTENT_DIR . '/themes/shopic-child/api_registar_loader.php';

// Include solo se il file esiste
if (file_exists($api_file)) {
    require_once $api_file;
} else {
    error_log('[API Bootstrap] File non trovato: ' . $api_file);
}