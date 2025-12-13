<?php
session_name('AUTHID_SESSION');
session_start();
require_once '../includes/auth_validate.php';
require_once '../config/config.php';
$del_id = filter_input(INPUT_POST, 'del_id');
$db = getDbInstance();

if (!isset($_SESSION['admin_type']) || $_SESSION['admin_type'] != 'admin') {
    header('Location:' . URL_PREFIX . '/authid/login.php');
    exit();
}


// Delete a user using user_id
if ($del_id && $_SERVER['REQUEST_METHOD'] == 'POST') {

    $db->where('id', $del_id);
    $stat = $db->delete('user');
    if ($stat) {
        $_SESSION['info'] = "User deleted successfully!";
        header('location: customers.php');
        exit;
    }
}