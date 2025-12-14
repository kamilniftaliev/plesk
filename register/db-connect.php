<?php
$host = "localhost";
$db_name = DB_NAME ?: "u676821063_new2";
$db_user = DB_USER ?: "u676821063_new2";
$db_pass = DB_PASSWORD ?: "!/F:6h[E9";

$conn = new mysqli($host, $db_user, $db_pass, $db_name);

if (!$conn) {
    die("Database Connection Failed");
}

