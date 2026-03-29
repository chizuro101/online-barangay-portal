<?php

// Path Configuration
define('ROOT_PATH', dirname(__DIR__) . '/');
define('ADMIN_PATH', ROOT_PATH . 'admin/');
define('USER_PATH', ROOT_PATH . 'user/');
define('API_PATH', ROOT_PATH . 'api/');
define('ASSETS_PATH', ROOT_PATH . 'assets/');
define('CLASSES_PATH', ROOT_PATH . 'classes/');
define('CONFIG_PATH', ROOT_PATH . 'config/');
define('DATABASE_PATH', ROOT_PATH . 'database/');
define('STORAGE_PATH', ROOT_PATH . 'storage/');
define('TEMPLATES_PATH', ROOT_PATH . 'templates/');
define('LOGS_PATH', ROOT_PATH . 'logs/');

// URL Paths
define('ROOT_URL', APP_URL);
define('ADMIN_URL', APP_URL . 'admin/');
define('USER_URL', APP_URL . 'user/');
define('API_URL', APP_URL . 'api/');
define('ASSETS_URL', APP_URL . 'assets/');
define('STORAGE_URL', APP_URL . 'storage/');

// Asset Paths
define('CSS_URL', ASSETS_URL . 'css/');
define('JS_URL', ASSETS_URL . 'js/');
define('IMAGES_URL', ASSETS_URL . 'images/');
define('FONTS_URL', ASSETS_URL . 'fonts/');

// Upload Paths
define('UPLOADS_PATH', STORAGE_PATH . 'uploads/');
define('DOCUMENTS_PATH', UPLOADS_PATH . 'documents/');
define('IMAGES_UPLOAD_PATH', UPLOADS_PATH . 'images/');
define('AVATARS_PATH', UPLOADS_PATH . 'avatars/');
define('TEMP_PATH', UPLOADS_PATH . 'temp/');

// Cache Paths
define('CACHE_PATH', STORAGE_PATH . 'cache/');
define('VIEWS_CACHE_PATH', CACHE_PATH . 'views/');
define('DATA_CACHE_PATH', CACHE_PATH . 'data/');

?>
