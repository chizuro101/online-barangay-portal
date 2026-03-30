<?php

// Application Configuration
define('APP_NAME', 'Online Barangay Portal');
define('APP_VERSION', '2.0.0');
define('APP_URL', 'http://localhost/xampp/Online_Barangay_Portal-master/Online_Barangay_Portal-master/');

// Debug Mode
define('DEBUG_MODE', true);
define('ERROR_REPORTING', E_ALL);

// Session Configuration
define('SESSION_LIFETIME', 3600); // 1 hour
define('SESSION_PATH', '/');

// File Upload Configuration
define('MAX_FILE_SIZE', 5242880); // 5MB
define('ALLOWED_FILE_TYPES', ['pdf', 'doc', 'docx', 'jpg', 'jpeg', 'png', 'gif']);

// Pagination
define('ITEMS_PER_PAGE', 10);

// Timezone
date_default_timezone_set('Asia/Manila');

// Security
define('HASH_ALGORITHM', PASSWORD_DEFAULT);
define('ENCRYPTION_KEY', 'your-encryption-key-here');

?>
