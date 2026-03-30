<?php
// Simple database test
echo "<h2>Database Connection Test</h2>";

try {
    require_once 'config/config.php';
    require_once 'classes/User.php';
    
    echo "✅ Config loaded successfully<br>";
    
    $userObj = new User();
    echo "✅ User object created<br>";
    
    // Test database connection
    $stmt = $userObj->db->query("SELECT 1");
    echo "✅ Database connection successful<br>";
    
    // Test if resident table exists
    $stmt = $userObj->db->query("DESCRIBE resident_info");
    if ($stmt) {
        echo "✅ Table 'resident_info' exists<br>";
        
        // Show table structure
        echo "<h3>Table Structure:</h3>";
        $columns = $stmt->fetchAll(PDO::FETCH_COLUMN);
        echo "<pre>" . print_r($columns, true) . "</pre>";
    } else {
        echo "❌ Table 'resident_info' not found<br>";
    }
    
    // Test existing usernames
    echo "<h3>Existing Usernames:</h3>";
    $stmt = $userObj->db->query("SELECT username FROM resident_info");
    $users = $stmt->fetchAll(PDO::FETCH_COLUMN);
    echo "<pre>" . print_r($users, true) . "</pre>";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "<br>";
    echo "Stack trace: <pre>" . $e->getTraceAsString() . "</pre>";
}
?>
