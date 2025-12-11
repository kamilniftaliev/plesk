<?php
session_name('DASHBOARD_SESSION');
session_start();
require_once '../config/config.php';
require_once '../includes/auth_validate.php';
require("auth.php");
//Used to hide any error or warning messages on the responce page (If any text other than json appear in responce it crash the app)
error_reporting(E_ERROR | E_PARSE);

//Only super admin is allowed to access this page
if ($_SESSION['admin_type'] !== 'admin') {
	// show permission denied message
	echo 'Permission Denied';
	exit();
}


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	$admin_name = $_SESSION['name'];
	$admin_id = $_SESSION['admin_id'];
	$soldadmin = $_SESSION['admin_sold'];
	$db = getDbInstance();

	$db->where("username", $name);

	$rowm = $db->get('user');
	if ($db->count >= 1) {
		$left_act = $rowm[0]['left_act'];

	}
	$random_bytes = random_bytes(16); // Generate 16 random bytes
	$md5_hash = "BD-" . md5($random_bytes);
	$uppercase_md5_hash = strtoupper($md5_hash);
	$mode_num = 1;
	$rs_sold = 0;
	$rs_count = $_POST['count'];
	$min_left = $left_act -= $mode_num;
	$dbsold = $soldadmin += $mode_num;
	$date = date('y-m-d H:i:s');
	$data_to_store = filter_input_array(INPUT_POST);
	$db = getDbInstance();
	//Check whether the user name already exists ; 
	$username = $_POST['user_name'];
	$password = password_hash($_POST['password'], PASSWORD_DEFAULT);
	;
	$email = $_POST['email'];
	$expireddate = $date;
	$amout = $_POST['amount'];
	$sql = "INSERT INTO admin_accounts (user_name,password,series_id,remember_token,expires,admin_type,authkey,sold,left_act,api_token,email) values ('$username', '$password','unknown','unknown','$date', 'admin','nokey','$rs_sold','$rs_count','$uppercase_md5_hash','$email')";
	$sqlSold = "INSERT INTO sold_rs (name,amout,sold_date,sold_by) values ('$username', '$amout','$date','$admin_name')";
	$sqluser = "SELECT * FROM admin_accounts WHERE use_rname='$admin_name'";

	if ($conn->connect_errno) {
		printf("Failed to connect to database");
		exit();
	} else {
		$db = getDbInstance();

		$db->where("username", $admin_name);

		$row = $db->get('user');
		if ($db->count >= 1) {






			if (mysqli_query($conn, $sql)) {
				if (mysqli_query($conn, $sqlSold)) {
					$_SESSION['success'] = "New reseller added successfully!";
					header('location: admin_users.php');
					exit();
				} else {
					echo "ERROR: Could not able to execute $sqlSold. " . mysqli_error($conn);
				}

			} else {
				echo "ERROR: Could not able to execute $sql. " . mysqli_error($conn);
			}





		} else {
			echo "Admin not found";
		}



	}
}

$edit = false;


require_once 'includes/header.php';
?>
<div id="page-wrapper">
	<div class="row">
		<h1 class="page-header">Add Reseller</h1>
	</div>
	<?php
	include_once('includes/flash_messages.php');
	?>
	<form class="well form-horizontal" action=" " method="post" id="contact_form" enctype="multipart/form-data">
		<?php include_once './forms/admin_users_form.php'; ?>
	</form>
</div>




<?php include_once '../includes/footer.php'; ?>