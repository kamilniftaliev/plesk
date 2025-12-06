<?php
$host = "localhost";
$username = "u676821063_new2";
$pw = "!/F:6h[E9";
$db_name = "u676821063_new2";

$conn = new mysqli($host, $username, $pw, $db_name);

if(!$conn){
    die("Database Connection Failed");
}

