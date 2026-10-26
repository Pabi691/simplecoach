<?php
/**
 * Simple Coach Hire — website configuration.
 * Reads from ENVIRONMENT VARIABLES (set these in Render), with local fallbacks.
 * On cPanel you can just edit the fallback values below instead of using env vars.
 *
 * SECURITY: never commit real DB passwords / Stripe keys to GitHub.
 * On Render, set them as Environment Variables in the service settings.
 */
function env(string $key, string $default = ''): string
{
    $v = getenv($key);
    return ($v === false || $v === '') ? $default : $v;
}

// ---- Database (set as Render env vars; fallbacks are for local XAMPP) ----
define('DB_HOST',    env('DB_HOST', 'localhost'));
define('DB_PORT',    env('DB_PORT', '3306'));        // cloud MySQL often uses a custom port
define('DB_NAME',    env('DB_NAME', 'simplecoachhire_new'));
define('DB_USER',    env('DB_USER', 'root'));
define('DB_PASS',    env('DB_PASS', ''));
define('DB_CHARSET', 'utf8mb4');
// TLS: cloud MySQL (Aiven/TiDB) requires it. Put the CA file at app/ca.pem (auto-detected),
// or set DB_SSL=1 to encrypt without a CA file. Leave off for a plain localhost MySQL.
define('DB_SSL',     env('DB_SSL', '') === '1');
define('DB_SSL_CA',  env('DB_SSL_CA', ''));

// ---- Site ----
define('SITE_URL',      env('SITE_URL', 'https://simplecoachhire.com'));
define('SITE_NAME',     env('SITE_NAME', 'Simple Coach Hire'));
define('SITE_PHONE',    env('SITE_PHONE', '+44 20 3953 0760'));
define('SITE_EMAIL',    env('SITE_EMAIL', 'info@simplecoachhire.com'));
define('SITE_WHATSAPP', env('SITE_WHATSAPP', '442039530760'));
define('TIMEZONE',      env('TIMEZONE', 'Europe/London'));

// ---- transport-io CRM API (keeps live quotes + Stripe payment working) ----
define('API_BASE_URL',     env('API_BASE_URL', 'https://connect.transport-io.com/api'));
define('API_KEY',          env('API_KEY', ''));                  // set the ROTATED token
define('BOOKING_BASE_URL', env('BOOKING_BASE_URL', 'https://booking.simplecoachhire.com'));
define('FORWARD_TO_TRANSPORT_IO', env('FORWARD_TO_TRANSPORT_IO', '1') === '1');

// ---- Stripe & Google Maps (set the ROTATED keys) ----
define('STRIPE_SECRET_KEY', env('STRIPE_SECRET_KEY', ''));
define('GOOGLE_MAPS_KEY',   env('GOOGLE_MAPS_KEY', ''));

define('CRM_SESSION_NAME', 'sch_crm');

date_default_timezone_set(TIMEZONE);
