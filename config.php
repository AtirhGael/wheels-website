<?php
/**
 * Configuration file for Elite BBS Rims
 * 
 * Update these values for your local/remote environment
 */

// Database Configuration
define('DB_HOST', '127.0.0.1');
define('DB_SOCKET', '/opt/lampp/var/mysql/mysql.sock');
define('DB_NAME', 'elitebbs_db');
define('DB_USER', 'root');
define('DB_PASS', '');  // Default XAMPP has no password - change for production

// Site Configuration
define('SITE_NAME', 'Elite BBS Rims');
define('SITE_URL', 'http://localhost/elitebbs');
// define('SITE_URL', 'https://www.elitebbswheels.store');
define('SITE_EMAIL', 'info@elitebbswheels.store');

// Email Configuration (for order notifications)
define('EMAIL_FROM', 'info@elitebbswheels.store');
define('EMAIL_TO', 'info@elitebbswheels.store');  // Where to send order notifications

// Currency & Locale
define('CURRENCY_SYMBOL', '$');
define('CURRENCY_CODE', 'USD');

// Timezone
date_default_timezone_set('America/New_York');

// Error Reporting
ini_set('display_errors', 0);
ini_set('log_errors', 1);
error_reporting(0);

// Start session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Base path for includes
define('BASE_PATH', __DIR__);
define('INCLUDES_PATH', BASE_PATH . '/includes');
define('ASSETS_PATH', BASE_PATH . '/assets');

// SMTP (Hostinger)
define('SMTP_HOST',      'smtp.hostinger.com');
define('SMTP_PORT',      465);
define('SMTP_SECURE',    'ssl');
define('SMTP_USER',      'info@elitebbswheels.store');
define('SMTP_PASS',      'Bamenda@2026');
define('SMTP_FROM_NAME', SITE_NAME);

// Helper function to get asset URL
function asset_url($path = '') {
    return SITE_URL . '/assets/' . ltrim($path, '/');
}

// Helper function to get site URL
function site_url($path = '') {
    return SITE_URL . '/' . ltrim($path, '/');
}