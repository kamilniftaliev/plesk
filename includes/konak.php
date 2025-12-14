<?php
// Use database configuration from config.php
require_once __DIR__ . '/../config/config.php';

$host = DB_HOST;
$username = DB_USER;
$password = DB_PASSWORD;
$database = DB_NAME;

// Attempt to establish a connection to the database
$koneksi = new mysqli($host, $username, $password, $database);

if ($koneksi->connect_errno) {
	die("gagalcok");
} // this my specialy char gaga