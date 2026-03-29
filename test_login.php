<?php

require_once 'config/config.php';
require_once 'classes/User.php';

$userObj = new User();

echo "<h2>Database Login Test</h2>";

// Test officials table
echo "<h3>Testing Officials Table:</h3>";
try {
    $officials = $userObj->runQuery("SELECT official_id, official_username, official_password, is_captain FROM " . TABLE_OFFICIALS . " LIMIT 5");
    $officials->execute();
    
    if ($officials->rowCount() > 0) {
        echo "<table border='1'>";
        echo "<tr><th>ID</th><th>Username</th><th>Password</th><th>Captain</th></tr>";
        while ($row = $officials->fetch(PDO::FETCH_ASSOC)) {
            echo "<tr>";
            echo "<td>" . $row['official_id'] . "</td>";
            echo "<td>" . htmlspecialchars($row['official_username']) . "</td>";
            echo "<td>" . htmlspecialchars($row['official_password']) . "</td>";
            echo "<td>" . ($row['is_captain'] ?? '0') . "</td>";
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "<p>No officials found in database!</p>";
    }
} catch (Exception $e) {
    echo "<p>Error checking officials: " . $e->getMessage() . "</p>";
}

// Test residents table
echo "<h3>Testing Residents Table:</h3>";
try {
    $residents = $userObj->runQuery("SELECT resident_id, username, password FROM " . TABLE_RESIDENTS . " LIMIT 5");
    $residents->execute();
    
    if ($residents->rowCount() > 0) {
        echo "<table border='1'>";
        echo "<tr><th>ID</th><th>Username</th><th>Password</th></tr>";
        while ($row = $residents->fetch(PDO::FETCH_ASSOC)) {
            echo "<tr>";
            echo "<td>" . $row['resident_id'] . "</td>";
            echo "<td>" . htmlspecialchars($row['username']) . "</td>";
            echo "<td>" . htmlspecialchars($row['password']) . "</td>";
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "<p>No residents found in database!</p>";
    }
} catch (Exception $e) {
    echo "<p>Error checking residents: " . $e->getMessage() . "</p>";
}

// Test actual login
echo "<h3>Testing Login Method:</h3>";
$testLogin = $userObj->login('admin1', 'admin1');
echo "<p>Login result for 'admin1'/'admin1': " . $testLogin . "</p>";

?>
