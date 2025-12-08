<?php
$db_host = 'localhost'; // Server Name
$db_user = 'u676821063_new2'; // Username
$db_pass = '!/F:6h[E9'; // Password
$db_name = 'u676821063_new2'; // Database Name

$conn = mysqli_connect($db_host, $db_user, $db_pass, $db_name);
if (!$conn) {
	die ('Failed to connect to MySQL: ' . mysqli_connect_error());	
}
?>