<?php
/**
 * Configuration file for Elite BBS Rims
 * 
 * Update these values for your local/remote environment
 */

// Database Configuration
define('DB_HOST', 'localhost');
define('DB_NAME', 'elitebbs_db');
define('DB_USER', 'root');
define('DB_PASS', '');  // Default XAMPP has no password - change for production

// Site Configuration
define('SITE_NAME', 'Elite BBS Rims');
define('SITE_URL', 'http://localhost/elitebbs');
define('SITE_EMAIL', 'info@elitebbswheelsus.shop');

// Email Configuration (for order notifications)
define('EMAIL_FROM', 'orders@elitebbswheelsus.shop');
define('EMAIL_TO', 'info@elitebbswheelsus.shop');  // Where to send order notifications

// Currency & Locale
define('CURRENCY_SYMBOL', '$');
define('CURRENCY_CODE', 'USD');

// Timezone
date_default_timezone_set('America/New_York');

// Error Reporting (turn off in production)
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Start session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Base path for includes
define('BASE_PATH', __DIR__);
define('INCLUDES_PATH', BASE_PATH . '/includes');
define('ASSETS_PATH', BASE_PATH . '/assets');

// Helper function to get asset URL
function asset_url($path = '') {
    return SITE_URL . '/assets/' . ltrim($path, '/');
}

// Helper function to get site URL
function site_url($path = '') {
    return SITE_URL . '/' . ltrim($path, '/');
}