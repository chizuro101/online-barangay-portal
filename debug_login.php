<?php

require_once 'config/config.php';

echo "<h2>Debug Login Process</h2>";

// Test 1: Check if config loads
echo "<h3>Step 1: Config Test</h3>";
if (defined('DB_HOST')) {
    echo "✅ Config loaded successfully<br>";
    echo "DB_HOST: " . DB_HOST . "<br>";
    echo "DB_NAME: " . DB_NAME . "<br>";
} else {
    echo "❌ Config failed to load<br>";
}

// Test 2: Check Database class
echo "<h3>Step 2: Database Class Test</h3>";
try {
    $db = Database::getInstance();
    echo "✅ Database class instantiated<br>";
    
    // Test connection
    $conn = $db->getConnection();
    echo "✅ Database connection established<br>";
    
    // Test a simple query
    $stmt = $db->prepare("SELECT 1");
    $stmt->execute();
    echo "✅ Database query works<br>";
    
} catch (Exception $e) {
    echo "❌ Database error: " . $e->getMessage() . "<br>";
}

// Test 3: Check User class
echo "<h3>Step 3: User Class Test</h3>";
try {
    $userObj = new User();
    echo "✅ User class instantiated<br>";
    
    // Test runQuery method
    $stmt = $userObj->runQuery("SELECT 1");
    echo "✅ User runQuery works<br>";
    
} catch (Exception $e) {
    echo "❌ User class error: " . $e->getMessage() . "<br>";
}

// Test 4: Check tables
echo "<h3>Step 4: Table Check</h3>";
try {
    $db = Database::getInstance();
    
    // Check officials table
    $stmt = $db->prepare("SHOW TABLES LIKE '" . TABLE_OFFICIALS . "'");
    $stmt->execute();
    if ($stmt->rowCount() > 0) {
        echo "✅ Officials table exists<br>";
        
        // Count records
        $stmt = $db->prepare("SELECT COUNT(*) as count FROM " . TABLE_OFFICIALS);
        $stmt->execute();
        $count = $stmt->fetch()['count'];
        echo "Officials records: " . $count . "<br>";
    } else {
        echo "❌ Officials table doesn't exist<br>";
    }
    
    // Check residents table
    $stmt = $db->prepare("SHOW TABLES LIKE '" . TABLE_RESIDENTS . "'");
    $stmt->execute();
    if ($stmt->rowCount() > 0) {
        echo "✅ Residents table exists<br>";
        
        // Count records
        $stmt = $db->prepare("SELECT COUNT(*) as count FROM " . TABLE_RESIDENTS);
        $stmt->execute();
        $count = $stmt->fetch()['count'];
        echo "Residents records: " . $count . "<br>";
    } else {
        echo "❌ Residents table doesn't exist<br>";
    }
    
} catch (Exception $e) {
    echo "❌ Table check error: " . $e->getMessage() . "<br>";
}

// Test 5: Test login method directly
echo "<h3>Step 5: Login Method Test</h3>";
try {
    $userObj = new User();
    $result = $userObj->login('admin1', 'admin1');
    echo "Login result: " . ($result !== false ? $result : 'false') . "<br>";
    
    if ($result !== false) {
        echo "✅ Login method works<br>";
    } else {
        echo "❌ Login method failed<br>";
    }
    
} catch (Exception $e) {
    echo "❌ Login method error: " . $e->getMessage() . "<br>";
}

echo "<h3>Session Info:</h3>";
echo "Session status: " . session_status() . "<br>";
echo "Session data: " . json_encode($_SESSION) . "<br>";

?>
