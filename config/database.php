<?php

// Database Configuration
define('DB_HOST', 'localhost');
define('DB_NAME', 'online-barangay-portal');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_CHARSET', 'utf8mb4');
define('DB_COLLATE', 'utf8mb4_unicode_ci');

// PDO Options
define('DB_OPTIONS', [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES => false,
    PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4 COLLATE utf8mb4_unicode_ci"
]);

// Database Tables
define('TABLE_RESIDENTS', 'resident_info');
define('TABLE_OFFICIALS', 'official_info');
define('TABLE_ANNOUNCEMENTS', 'announcement_post');
define('TABLE_DOCUMENTS', 'documents');
define('TABLE_POSITIONS', 'staff_positions');
define('TABLE_SETTINGS', 'barangay_settings');
define('TABLE_ACTIVITY_LOG', 'activity_log');
define('TABLE_APPOINTMENTS', 'appointments');
define('TABLE_SERVICE_REQUESTS', 'service_requests');
define('TABLE_INVENTORY', 'inventory');
define('TABLE_FINANCIAL_RECORDS', 'financial_records');
define('TABLE_PROJECTS', 'projects');
define('TABLE_COMPLAINTS', 'complaints');
define('TABLE_MEETINGS', 'meetings');
define('TABLE_NOTIFICATIONS', 'notifications');

?>
