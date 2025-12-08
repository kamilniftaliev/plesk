<?php



session_name('AUTHID_SESSION');
session_start();
require_once './config/config.php';
require_once 'includes/auth_validate.php';
require("auth.php");

error_reporting(E_ERROR | E_PARSE);
$name = $_SESSION['name'];
if ($name == "abang") {
    header("Location: https://vegito-auth.com/GB/adm");
    exit;
}

if (isset($_SESSION['admin_type']) && $_SESSION['admin_type'] == 'admin') {
    include_once('includes/admin_header.php');
    include_once('includes/admin_menu.php');
}


if (isset($_SESSION['admin_type']) && $_SESSION['admin_type'] == 'authid') {
    include_once('includes/authid_header.php');
    include_once('includes/authid_menu.php');
}

if (isset($_SESSION['admin_type']) && $_SESSION['admin_type'] == 'user') {
    include_once('includes/user_header.php');
    include_once('includes/user_menu.php');
}




include_once('includes/footer.php'); ?>