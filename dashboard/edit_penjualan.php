<?php
session_name('DASHBOARD_SESSION');
session_start();
require_once './config/config.php';
require_once 'includes/auth_validate.php';

$serverid = filter_input(INPUT_GET, 'serverid');
$operation = filter_input(INPUT_GET, 'operation', FILTER_SANITIZE_SPECIAL_CHARS);
($operation == 'edit') ? $edit = true : $edit = false;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	if ($_SESSION['admin_type'] !== 'admin') {
		echo 'Permission Denied';
		exit();
	}
$ispay = filter_input(INPUT_POST, 'ispay', FILTER_SANITIZE_SPECIAL_CHARS);

$serverid = filter_input(INPUT_GET, 'serverid', FILTER_VALIDATE_INT);

$db = getDbInstance();
$db->where('id', $serverid);
$row = $db->getOne('penjualancredit');


     $data_to_update = [
        'ispay' => $ispay
    ];
	$db = getDbInstance();
	$db->where('id', $serverid);
	$stat = $db->update('penjualancredit', $data_to_update);

	if ($stat) {
		$_SESSION['success'] = "Changed the Paid off " . $user_name . " Successfully.";
	} else {
		$_SESSION['failure'] = "Failed to update Admin user : " . $db->getLastError();
	}

	header('location: penjualan.php');
	exit;

}

//Select where clause
$db = getDbInstance();
$db->where('id', $serverid);

$admin_account = $db->getOne("penjualancredit");

// Set values to $row

// import header
require_once 'includes/admin_header.php';
?>
<div id="page-wrapper">

    <div class="row">
     <div class="col-lg-12">
            <h2 class="page-header">Update Servers Info</h2>
        </div>

    </div>
    <?php include_once 'includes/flash_messages.php';?>
    <form class="well form-horizontal" action="" method="post"  id="contact_form" enctype="multipart/form-data">
        <?php include_once './forms/edit_penjualan_form.php';?>
    </form>
</div>




<?php include_once 'includes/footer.php';?>