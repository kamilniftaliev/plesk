<?php
session_name('DASHBOARD_SESSION');
session_start();
require_once '../includes/auth_validate.php';

$serverid = filter_input(INPUT_GET, 'serverid');
$operation = filter_input(INPUT_GET, 'operation', FILTER_SANITIZE_SPECIAL_CHARS);
($operation == 'edit') ? $edit = true : $edit = false;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (getCurrentUserType() !== 'admin') {
        echo 'Permission Denied';
        exit();
    }

    $serviceid = filter_input(INPUT_POST, 'serviceid', FILTER_SANITIZE_SPECIAL_CHARS);
    $servicename = filter_input(INPUT_POST, 'servicename', FILTER_SANITIZE_SPECIAL_CHARS);
    $price = filter_input(INPUT_POST, 'price', FILTER_SANITIZE_SPECIAL_CHARS);
    $serverid = filter_input(INPUT_GET, 'serverid', FILTER_VALIDATE_INT);

    $db = getDbInstance();
    $db->where('id', $serverid);
    $row = $db->getOne('price');


    $data_to_update = [

        'servicename' => $servicename,
        'serviceid' => $serviceid,
        'harga' => $price
    ];
    $db = getDbInstance();
    $db->where('id', $serverid);
    $stat = $db->update('price', $data_to_update);

    if ($stat) {
        $_SESSION['success'] = "Changed the Prices of " . $user_name . " Successfully.";
    } else {
        $_SESSION['failure'] = "Failed to update Admin user : " . $db->getLastError();
    }

    header('location: price.php');
    exit;

}

//Select where clause
$db = getDbInstance();
$db->where('id', $serverid);

$admin_account = $db->getOne("price");

// Set values to $row

// import header
require_once '../includes/header.php';
?>
<div id="page-wrapper">

    <div class="row">
        <h1 class="page-header">Update Price Info</h1>
    </div>
    <?php include_once 'includes/flash_messages.php'; ?>
    <form class="well form-horizontal" action="" method="post" id="contact_form" enctype="multipart/form-data">
        <?php include_once './forms/price_form.php'; ?>
    </form>
</div>




<?php include_once '../includes/footer.php'; ?>