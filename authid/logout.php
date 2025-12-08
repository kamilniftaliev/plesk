<?php
require_once './config/config.php';
session_name('AUTHID_SESSION');
session_start();

// Clear all session variables
$_SESSION = array();



// Clear remember me cookies
if (isset($_COOKIE['series_id']) && isset($_COOKIE['remember_token'])) {


	clearAuthCookie();


	// // Debug: Print all cookies
	// echo "<h2>All AAAAAAAAAAA Cookies:</h2>";
	// echo "<h3>Cookie Data (\$_COOKIE):</h3>";
	// echo "<pre>";
	// print_r($_COOKIE);
	// echo "</pre>";
	// echo "<h3>Cookie Details:</h3>";
	// if (!empty($_COOKIE)) {
	// 	foreach ($_COOKIE as $key => $value) {
	// 		echo "<strong>Cookie Name:</strong> " . htmlspecialchars($key) . " => <strong>Value:</strong> ";
	// 		if (is_array($value)) {
	// 			echo "<pre>" . print_r($value, true) . "</pre>";
	// 		} else {
	// 			echo htmlspecialchars($value) . "<br>";
	// 		}
	// 	}
	// } else {
	// 	echo "No cookies set<br>";
	// }

	// exit;
}

// Destroy the session
session_destroy();

// Redirect to login page
header('Location:login.php');
exit;

?>