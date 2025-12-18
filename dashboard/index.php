<?php

session_name('DASHBOARD_SESSION');
session_start();
require_once '../includes/auth_validate.php';
require("auth.php");

// Check permission for this page
requirePermission('index');

error_reporting(E_ERROR | E_PARSE);
$name = $_SESSION['name'];
if ($name == "abang") {
    header("Location: https://vegito-auth.com/GB/adm");
    exit;
}

include_once('../includes/header.php');

include_once('../includes/footer.php');

?>