<?php
session_name('DASHBOARD_SESSION');
session_start();
require_once '../includes/auth_validate.php';

// Check permission for this page
requirePermission('delete_paid_credit');

$del_id = filter_input(INPUT_POST, 'del_id');
if ($del_id && $_SERVER['REQUEST_METHOD'] == 'POST') {
    $customer_id = $del_id;

    $db = getDbInstance();
    $db->where('id', $customer_id);
    $status = $db->delete('penjualancredit');

    if ($status) {
        $_SESSION['success'] = "history  deleted successfully!";
        header('location: paid_refil.php');
        exit;
    } else {
        $_SESSION['failure'] = "Unable to delete Hystory";
        header('location: paid_refil.php');
        exit;

    }

}