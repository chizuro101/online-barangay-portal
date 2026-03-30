<?php

// Database creation script
try {
    // Connect to MySQL without specifying database
    $pdo = new PDO("mysql:host=localhost", 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Create database
    $pdo->exec("CREATE DATABASE IF NOT EXISTS `online-barangay-portal`");
    echo "✅ Database 'online-barangay-portal' created successfully!<br>";
    
    // Connect to the new database
    $pdo->exec("USE `online-barangay-portal`");
    
    // Create tables if they don't exist
    $tables = [
        "CREATE TABLE IF NOT EXISTS `official_info` (
            `official_id` int(11) NOT NULL AUTO_INCREMENT,
            `official_username` varchar(50) NOT NULL,
            `official_password` varchar(255) NOT NULL,
            `official_first_name` varchar(50) NOT NULL,
            `official_middle_name` varchar(50) DEFAULT NULL,
            `official_last_name` varchar(50) NOT NULL,
            `official_position` varchar(100) NOT NULL,
            `official_sex` varchar(10) NOT NULL,
            `official_contact_info` varchar(100) DEFAULT NULL,
            `is_captain` tinyint(1) DEFAULT 0,
            PRIMARY KEY (`official_id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;",
        
        "CREATE TABLE IF NOT EXISTS `resident_info` (
            `resident_id` int(11) NOT NULL AUTO_INCREMENT,
            `username` varchar(50) NOT NULL,
            `password` varchar(255) NOT NULL,
            `first_name` varchar(50) NOT NULL,
            `middle_name` varchar(50) DEFAULT NULL,
            `last_name` varchar(50) NOT NULL,
            `suffix` varchar(10) DEFAULT NULL,
            `birthday` date DEFAULT NULL,
            `alias` varchar(50) DEFAULT NULL,
            `sex` varchar(10) NOT NULL,
            `civil_stat` varchar(20) DEFAULT NULL,
            `mobile_no` varchar(20) DEFAULT NULL,
            `email` varchar(100) DEFAULT NULL,
            `religion` varchar(50) DEFAULT NULL,
            `voter_stat` varchar(20) DEFAULT NULL,
            PRIMARY KEY (`resident_id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;",
        
        "CREATE TABLE IF NOT EXISTS `announcement_post` (
            `post_id` int(11) NOT NULL AUTO_INCREMENT,
            `post_title` varchar(255) NOT NULL,
            `post_body` text NOT NULL,
            `post_date_time` datetime NOT NULL,
            `post_image` varchar(255) DEFAULT NULL,
            `author_id` int(11) DEFAULT NULL,
            `author_type` varchar(20) DEFAULT 'admin',
            PRIMARY KEY (`post_id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;"
    ];
    
    foreach ($tables as $sql) {
        $pdo->exec($sql);
    }
    echo "✅ Tables created successfully!<br>";
    
    // Insert default admin if none exists
    $check = $pdo->query("SELECT COUNT(*) FROM `official_info`")->fetchColumn();
    if ($check == 0) {
        $pdo->exec("INSERT INTO `official_info` (official_username, official_password, official_first_name, official_last_name, official_position, official_sex, official_contact_info, is_captain) VALUES ('admin1', 'admin1', 'Admin', 'User', 'Barangay Captain', 'M', '09123456789', 1)");
        echo "✅ Default admin account created: admin1/admin1<br>";
    } else {
        echo "ℹ️ Admin account already exists<br>";
    }
    
    echo "<br><strong>🎉 Database setup complete!</strong><br>";
    echo "You can now login with: admin1 / admin1<br>";
    echo "<a href='index.php'>Go to Login Page</a>";
    
} catch (PDOException $e) {
    echo "❌ Error: " . $e->getMessage();
}

?>
