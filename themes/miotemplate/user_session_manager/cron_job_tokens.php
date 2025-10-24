<?php

// Verifica che il file esista prima di includerlo
$file_path = WP_CONTENT_DIR . "/themes/shopic-child/user_session_manager/user_session_manager.php";

use App\Auth\UserSessionManager;



// Funzione di pulizia dei token scaduti
function session_cleaner_func() {
    error_log("JWT Token Manager: Avvio della pulizia dei token scaduti.");

    // Assicurati che USM_SECRET_KEY sia definita
    if (!defined('USM_SECRET_KEY')) {
        error_log("JWT Token Manager: USM_SECRET_KEY non è definita.");
        return;
    }

    // Verifica che la classe esista
    if (!class_exists('App\Auth\UserSessionManager')) {
        error_log("JWT Token Manager: La classe UserSessionManager non esiste.");
        return;
    }

    try {
        $user_manager = new UserSessionManager(USM_SECRET_KEY, "petbuy.com", "https://petbuy-local.ns0.it:8080");
        error_log("JWT Token Manager: Istanza di UserSessionManager creata con successo.");
        
        $deletedCount = $user_manager->deleteExpiredTokens();
        error_log("JWT Token Manager: deleteExpiredTokens eseguita. Token eliminati: {$deletedCount}.");

        if ($deletedCount > 0) {
            error_log("JWT Token Manager: Eliminati {$deletedCount} token scaduti.");
        } else {
            error_log("JWT Token Manager: Nessun token scaduto da eliminare.");
        }
    } catch (Exception $e) {
        error_log("JWT Token Manager: Errore durante la pulizia dei token: " . $e->getMessage());
    }
}


function start()
{
    if ( ! wp_next_scheduled( 'session_cleaner' ) ) {
        wp_schedule_event( time(), 'five_seconds', 'session_cleaner' );
    }
}
