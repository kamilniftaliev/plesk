<?php

session_start();
include_once('connection.php');
require_once '../config/config.php';
require_once './includes/auth_validate.php';

if (isset($_POST['chpass'])) {

	$currentpass = $_POST['cpass'];
	$newpass = $_POST['npass'];
	$verifypass = $_POST['vpass'];

	$admin_id = $_SESSION['admin_id'];
	$admin_name = $_SESSION['name'];
	$db = getDbInstance();

	$db->where("user_name", $admin_name);

	$row = $db->get('admin_accounts');
	if ($db->count >= 1) {
		$db_password = $row[0]['password'];
		if (password_verify($currentpass, $db_password)) {
			if ($newpass === $verifypass) {
				$hashnewpass = password_hash($newpass, PASSWORD_DEFAULT);
				$sqlAdmin = "UPDATE admin_accounts SET password='$hashnewpass' WHERE id='$admin_id'";
				if ($conn->query($sqlAdmin)) {

					$_SESSION['success'] = 'New reseller account password changed successfully.';
					header('Location:index.php');
				} else {
					$_SESSION['error'] = 'The network connection was courrepted while changing password!';
					header('Location:index.php');
				}
			} else {
				$_SESSION['error'] = 'New password confirmation is error!';
				header('Location:index.php');
			}

		} else {
			$_SESSION['error'] = 'Current reseller password is wrong!';
			header('Location:index.php');
		}
	} else {

	}
} else {
	$_SESSION['error'] = 'Fill up change password form first';
	header('Location:index.php');
}



?>