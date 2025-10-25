<?php

namespace App\Auth;

if (!defined('ABSPATH')) {
    exit;
}

require_once __DIR__ . '/user_session_manager.php';

const PETBUY_SESSION_CRON_HOOK = 'petbuy_session_cleanup';
const PETBUY_SESSION_CRON_INTERVAL = 'petbuy_session_fifteen_min';

/**
 * Registra un intervallo custom di 15 minuti per la pulizia dei token.
 */
function petbuy_register_session_interval(array $schedules): array
{
    if (!isset($schedules[PETBUY_SESSION_CRON_INTERVAL])) {
        $schedules[PETBUY_SESSION_CRON_INTERVAL] = [
            'interval' => 15 * \MINUTE_IN_SECONDS,
            'display'  => __('Every 15 Minutes (Petbuy Sessions)', 'petbuy'),
        ];
    }

    return $schedules;
}
\add_filter('cron_schedules', __NAMESPACE__ . '\\petbuy_register_session_interval');

/**
 * Pianifica il cron se non è già presente.
 */
function petbuy_schedule_session_cleanup(): void
{
    if (!\wp_next_scheduled(PETBUY_SESSION_CRON_HOOK)) {
        \wp_schedule_event(time(), PETBUY_SESSION_CRON_INTERVAL, PETBUY_SESSION_CRON_HOOK);
    }
}
\add_action('init', __NAMESPACE__ . '\\petbuy_schedule_session_cleanup');

/**
 * Annulla il cron quando il tema viene disattivato/cambiato.
 */
function petbuy_unschedule_session_cleanup(): void
{
    $timestamp = \wp_next_scheduled(PETBUY_SESSION_CRON_HOOK);
    if ($timestamp) {
        \wp_unschedule_event($timestamp, PETBUY_SESSION_CRON_HOOK);
    }
}
\add_action('switch_theme', __NAMESPACE__ . '\\petbuy_unschedule_session_cleanup');

/**
 * Handler che elimina i token scaduti.
 */
function petbuy_session_cleanup_handler(): void
{
    if (!defined('USM_SECRET_KEY')) {
        return;
    }

    if (!class_exists(UserSessionManager::class)) {
        return;
    }

    $issuer = \wp_parse_url(\home_url(), PHP_URL_HOST) ?: 'petbuy.local';
    $audience = \home_url('/');

    $manager = new UserSessionManager(USM_SECRET_KEY, $issuer, $audience);
    $manager->deleteExpiredTokens();
}
\add_action(PETBUY_SESSION_CRON_HOOK, __NAMESPACE__ . '\\petbuy_session_cleanup_handler');
