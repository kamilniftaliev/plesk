<?php
session_name('DASHBOARD_SESSION');
session_start();
require_once 'includes/auth_validate.php';
require_once './config/config.php';
$del_id = filter_input(INPUT_POST, 'del_id');
$db = getDbInstance();

if ($_SESSION['admin_type'] != 'admin') {
    header('Location:/dashboard/login.php');
    exit();
}


// Delete a user using user_id
if ($del_id && $_SERVER['REQUEST_METHOD'] == 'POST') {

    $db->where('id', $del_id);
    $stat = $db->delete('server');
    if ($stat) {
        $_SESSION['info'] = "servers deleted successfully!";
        header('location: servers.php');
        exit;
    }
}