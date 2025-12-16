<?php

require_once '../config/config.php';

$host = "localhost";
$db_name = DB_NAME;
$db_user = DB_USER;
$db_pass = DB_PASSWORD;

$conn = new mysqli($host, $db_user, $db_pass, $db_name);

if (!$conn) {
    die("Database Connection Failed");
}

