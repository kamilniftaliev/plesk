<?php
session_name('DASHBOARD_SESSION');
session_start();
require_once '../includes/auth_validate.php';

// Check permission for this page
requirePermission('delete_customer');
$del_id = filter_input(INPUT_POST, 'del_id');
if ($del_id && $_SERVER['REQUEST_METHOD'] == 'POST') {
    $customer_id = $del_id;

    $db = getDbInstance();
    $db->where('id', $customer_id);
    $status = $db->delete('admin_accounts');

    if ($status) {
        $_SESSION['success'] = "Customer deleted successfully!";
        header('location: admin_users.php');
        exit;
    } else {
        $_SESSION['failure'] = "Unable to delete customer";
        header('location: admin_users.php');
        exit;

    }

}