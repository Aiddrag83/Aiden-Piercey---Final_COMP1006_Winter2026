<?php
// Database Configuration
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'gallery_db');

// Application Configuration
define('APP_NAME', 'Gallery Application');
define('APP_URL', 'http://localhost');
define('APP_DEBUG', true);

// Session Configuration
ini_set('session.cookie_httponly', 1);
ini_set('session.use_strict_mode', 1);

// Error Reporting
if (APP_DEBUG) {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
} else {
    error_reporting(E_ALL);
    ini_set('display_errors', 0);
}