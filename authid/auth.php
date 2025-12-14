<?php
$db_host = 'localhost'; // Server Name
$db_name = DB_NAME ?: "u676821063_new2";
$db_user = DB_USER ?: "u676821063_new2";
$db_pass = DB_PASSWORD ?: "!/F:6h[E9";

$conn = mysqli_connect($db_host, $db_user, $db_pass, $db_name);
if (!$conn) {
	die('Failed to connect to MySQL: ' . mysqli_connect_error());
}
?>