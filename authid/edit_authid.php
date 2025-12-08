<?php
session_name('AUTHID_SESSION');
session_start();
require_once './config/config.php';
require_once 'includes/auth_validate.php';

//User ID for which we are performing operation
$admin_user_id = filter_input(INPUT_GET, 'admin_user_id');
$operation = filter_input(INPUT_GET, 'operation', FILTER_SANITIZE_SPECIAL_CHARS);
($operation == 'edit') ? $edit = true : $edit = false;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	// If non-super user accesses this script via url. Stop the exexution
	if (!isset($_SESSION['admin_type']) || $_SESSION['admin_type'] !== 'admin') {
	
		echo 'Permission Denied';
		exit();
	}


$user_name = filter_input(INPUT_POST, 'user_name', FILTER_SANITIZE_SPECIAL_CHARS);
$camount = filter_input(INPUT_POST, 'camount', FILTER_VALIDATE_FLOAT);
$famount = filter_input(INPUT_POST, 'famount', FILTER_VALIDATE_FLOAT);
    	$admin_user_id = filter_input(INPUT_GET, 'admin_user_id', FILTER_VALIDATE_INT);
	//Check whether the user name already exists ;
	$db = getDbInstance();
	$db->where('user', $user_name);
	$db->where('id', $admin_user_id, '!=');
	//print_r($data_to_update['user_name']);die();
	$row = $db->getOne('username');
	//print_r($data_to_update['user_name']);
	//print_r($row); die();

	if (!empty($row['user_name'])) {

		$_SESSION['failure'] = "User name already exists";

		$query_string = http_build_query(array(
			'admin_user_id' => $admin_user_id,
			'operation' => $operation,
		));
		header('location: refill_admin.php?'.$query_string );
		exit;
	}


	
  
	//Encrypting the password
//	$data_to_update['left_act'] = $data_to_update['camount'] + $data_to_update['famount'];
	

     $data_to_update = [
        'user_name' => $user_name,
        'left_act' => $camount + $famount,
    ];
	$db = getDbInstance();
	$db->where('id', $admin_user_id);
	$stat = $db->update('username', $data_to_update);

	if ($stat) {
		$_SESSION['success'] = $famount . " Credit Added to " . $user_name . " Successfully.";
	} else {
		$_SESSION['failure'] = "Failed to update Admin user : " . $db->getLastError();
	}

	header('location: admin_users.php');
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
            <h2 class="page-header">Update Admin Info</h2>
        </div>

    </div>
    <?php include_once 'includes/flash_messages.php';?>
    <form class="well form-horizontal" action="" method="post"  id="contact_form" enctype="multipart/form-data">
        <?php include_once './forms/refill_edit_form.php';?>
    </form>
</div>




<?php include_once 'includes/footer.php';?>