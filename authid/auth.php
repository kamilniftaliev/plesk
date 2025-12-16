<?php

require_once '../config/config.php';

$db_host = 'localhost'; // Server Name
$db_name = DB_NAME;
$db_user = DB_USER;
$db_pass = DB_PASSWORD;

$conn = mysqli_connect($db_host, $db_user, $db_pass, $db_name);
if (!$conn) {
	die('Failed to connect to MySQL: ' . mysqli_connect_error());
}
?>