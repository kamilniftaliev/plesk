<?php 
session_name('RESELLER_SESSION');
session_start();
require_once 'includes/auth_validate.php';
require_once './config/config.php';
$del_id = filter_input(INPUT_POST, 'del_id');
 $db = getDbInstance();

if(!isset($_SESSION['admin_type']) || $_SESSION['admin_type']!='admin'){
    header('HTTP/1.1 401 Unauthorized', true, 401);
    exit("401 Unauthorized");
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