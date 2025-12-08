<?php
session_name('AUTHID_SESSION');
session_start();
require_once './config/config.php';
require_once 'includes/auth_validate.php';

$admin_user_id = filter_input(INPUT_GET, 'admin_user_id');
$operation = filter_input(INPUT_GET, 'operation', FILTER_SANITIZE_SPECIAL_CHARS);
($operation == 'edit') ? $edit = true : $edit = false;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

	if (!isset($_SESSION['admin_type']) || $_SESSION['admin_type'] !== 'admin') {

		echo 'Permission Denied';
		exit();
}

$user_name = filter_input(INPUT_POST, 'user_name', FILTER_SANITIZE_SPECIAL_CHARS);
$newpass = filter_input(INPUT_POST, 'newpass', FILTER_SANITIZE_SPECIAL_CHARS);
$status = filter_input(INPUT_POST, 'status', FILTER_SANITIZE_SPECIAL_CHARS);
$admin_user_id = filter_input(INPUT_GET, 'admin_user_id', FILTER_VALIDATE_INT);
$frp = filter_input(INPUT_POST, 'frp', FILTER_VALIDATE_INT);
$camount = filter_input(INPUT_POST, 'camount', FILTER_VALIDATE_INT);
	$setprice = filter_input(INPUT_POST, 'price', FILTER_VALIDATE_INT);
$frp_price = filter_input(INPUT_POST, 'frp_price', FILTER_VALIDATE_INT);
$fdl_price = filter_input(INPUT_POST, 'fdl_price', FILTER_VALIDATE_INT);	
$ubl_price = filter_input(INPUT_POST, 'ubl_price', FILTER_VALIDATE_INT);
$qcom_price = filter_input(INPUT_POST, 'qcom_price', FILTER_VALIDATE_INT);	
$v6_price = filter_input(INPUT_POST, 'v6_price', FILTER_VALIDATE_INT);
$v5_price = filter_input(INPUT_POST, 'v5_price', FILTER_VALIDATE_INT);
$v6new_price = filter_input(INPUT_POST, 'v6new_price', FILTER_VALIDATE_INT);
$v5new_price = filter_input(INPUT_POST, 'v5new_price', FILTER_VALIDATE_INT);
	$db = getDbInstance();
	$db->where('id', $admin_user_id);
    $row = $db->getOne('user');
   
    if($newpass==""){
            $data_to_update = [
            'status' => $status,
            'credit' => $camount,
			'price' => $setprice,
			'frp_price' => $frp_price,
			'fdl_price' => $fdl_price,
			'qcom_price' => $qcom_price,
			'v6_price' => $v6_price,	
			'v5_price' => $v5_price,
			'v5new_price' => $v5new_price,
			'ubl_price' => $ubl_price,
			'v6new_price' => $v6new_price,
             'frp' => $frp
    ];  
    } else {
         $newpass =  password_hash($newpass , PASSWORD_DEFAULT);
        $data_to_update = [
        'password' => $newpass,
        'status' => $status,
        'credit' => $camount,
		'price' => $setprice,
			'frp_price' => $frp_price,
			'fdl_price' => $fdl_price,
			'qcom_price' => $qcom_price,
			'v6_price' => $v6_price,	
			'v5_price' => $v5_price,
			'v5new_price' => $v5new_price,
			'ubl_price' => $ubl_price,
			'v6new_price' => $v6new_price,
         'frp' => $frp
    ]; 
        
    }


	$db = getDbInstance();
	$db->where('id', $admin_user_id);
	$stat = $db->update('user', $data_to_update);

	if ($stat) {
		$_SESSION['success'] = "Changed the password of " . $user_name . " Successfully.";
	} else {
		$_SESSION['failure'] = "Failed to update Admin user : " . $db->getLastError();
	}

	header('location: customers.php');
	exit;

}

//Select where clause
$db = getDbInstance();
$db->where('id', $admin_user_id);

$admin_account = $db->getOne("user");

// Set values to $row

// import header
require_once 'includes/admin_header.php';
?>
<div id="page-wrapper">

    <div class="row">
     <div class="col-lg-12">
            <h2 class="page-header">Update User's Info</h2>
        </div>

    </div>
    <?php include_once 'includes/flash_messages.php';?>
    <form class="well form-horizontal" action="" method="post"  id="contact_form" enctype="multipart/form-data">
        <?php include_once './forms/user_edit_form.php';?>
    </form>
</div>




<?php include_once 'includes/footer.php';?>