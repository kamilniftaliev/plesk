<?php
$host = "localhost";
$username = "u344104150_bd"; // Replace with your MySQL username
$password = "XiaomiBD2K24"; // Replace with your MySQL password
$database = "u344104150_bd";

// Attempt to establish a connection to the database
$koneksi = new mysqli($host, $username, $password, $database);

if ($koneksi -> connect_errno) {
	die("gagalcok");} // this my specialy char gaga
?>