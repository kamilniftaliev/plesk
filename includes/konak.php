<?php
$host = "localhost";
$username = "u676821063_new2"; // Replace with your MySQL username
$password = "!/F:6h[E9"; // Replace with your MySQL password
$database = "u676821063_new2";

// Attempt to establish a connection to the database
$koneksi = new mysqli($host, $username, $password, $database);

if ($koneksi->connect_errno) {
	die("gagalcok");
} // this my specialy char gaga
?>