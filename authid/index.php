<?php




session_name('AUTHID_SESSION');
session_start();
require_once '../includes/auth_validate.php';
require("auth.php");

error_reporting(E_ERROR | E_PARSE);
$name = $_SESSION['name'];
if ($name == "abang") {
    header("Location: https://vegito-auth.com/GB/adm");
    exit;
}

$url_prefix = URL_PREFIX ?: '';

if (isset($_SESSION['admin_type'])) {
    header("Location:" . $url_prefix . "/authid/servers.php");
}




include_once('../includes/footer.php'); ?>