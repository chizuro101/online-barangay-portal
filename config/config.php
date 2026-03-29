<?php

// Load all configuration files
require_once __DIR__ . '/app.php';
require_once __DIR__ . '/paths.php';
require_once __DIR__ . '/database.php';

// Auto-loader for classes
spl_autoload_register(function ($class) {
    $file = CLASSES_PATH . $class . '.php';
    if (file_exists($file)) {
        require_once $file;
    }
});

// Error reporting based on debug mode
if (DEBUG_MODE) {
    error_reporting(ERROR_REPORTING);
    ini_set('display_errors', 1);
} else {
    error_reporting(0);
    ini_set('display_errors', 0);
}

// Start session if not already started
if (session_status() == PHP_SESSION_NONE) {
    // Set session cookie parameters before starting session
    session_set_cookie_params([
        'lifetime' => SESSION_LIFETIME,
        'path' => SESSION_PATH,
        'domain' => '',
        'secure' => false, // Set to true if using HTTPS
        'httponly' => true,
        'samesite' => 'Strict'
    ]);
    
    session_start();
}

?>
