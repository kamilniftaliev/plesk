<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h2>MySQL Connection Test</h2>";

$host = "localhost";
$user = "u676821063_new2";
$pass = "!/F:6h[E9";
$db = "u676821063_new2";

echo "<p><strong>Connection Details:</strong></p>";
echo "<ul>";
echo "<li>Host: $host</li>";
echo "<li>User: $user</li>";
echo "<li>Database: $db</li>";
echo "</ul>";

try {
    $koneksi = new mysqli($host, $user, $pass, $db);

    if ($koneksi->connect_errno) {
        echo "<p style='color:red;'><strong>❌ Connection Failed!</strong></p>";
        echo "<p>Error: " . $koneksi->connect_error . "</p>";
        echo "<p>Error Number: " . $koneksi->connect_errno . "</p>";
    } else {
        echo "<p style='color:green;'><strong>✅ Connection Successful!</strong></p>";

        // Test query
        $result = $koneksi->query("SHOW TABLES");
        if ($result) {
            echo "<p><strong>Tables in database:</strong></p>";
            echo "<ul>";
            while ($row = $result->fetch_row()) {
                echo "<li>" . $row[0] . "</li>";
            }
            echo "</ul>";
            echo "<p>Total tables: " . $result->num_rows . "</p>";
        }

        $koneksi->close();
    }
} catch (Exception $e) {
    echo "<p style='color:red;'><strong>❌ Exception:</strong></p>";
    echo "<p>" . $e->getMessage() . "</p>";
}
?>
