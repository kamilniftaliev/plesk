<?php
include "/includes/konak.php";
include "anti.php";
$username = anti_injection($_POST['username']);
$password = anti_injection($_POST['password']);
if ($username != "" && $password != "") {
	if (strpos($username, '@') == true) {
		$data = mysqli_query($koneksi, "SELECT * FROM user WHERE email='" . $username . "' ");
		$cek = mysqli_num_rows($data);
		if ($cek > 0) {
			$user = mysqli_fetch_array($data);
			$hashpassword = $user['password'];
			if (password_verify($password, $hashpassword)) {

				session_start();
				$_SESSION['username'] = $username;
				$_SESSION['status'] = "login";
				$_SESSION['site'] = "vegito";
				$_SESSION['id'] = $user['id'];
				header("location:https://ah-tool.com/index.php");
			} else {
				$jsonarray = ["status" => "error", "message" => "Wrong Password"];
				echo json_encode($jsonarray);
				die();
			}
		} else {
			$jsonarray = ["status" => "error", "message" => "email Not Registered"];
			echo json_encode($jsonarray);
			die();
		}

	} else {
		$data = mysqli_query($koneksi, "SELECT * FROM user WHERE username='" . $username . "' ");
		$cek = mysqli_num_rows($data);
		if ($cek > 0) {
			$user = mysqli_fetch_array($data);
			$hashpassword = $user['password'];
			if (password_verify($password, $hashpassword)) {
				//	$sukseslogin = true;
				//	$credit = $user['credit'];
				//	$iduser = $user['id'];
				//	$apikey = $user['apikey'];
				session_start();
				$_SESSION['username'] = $username;
				$_SESSION['status'] = "login";
				$_SESSION['site'] = "vegito";
				$_SESSION['id'] = $user['id'];

				header("location:https://ah-tool.com/index.php");
			} else {
				$jsonarray = ["status" => "error", "message" => "Wrong Password"];
				echo json_encode($jsonarray);
				die();
			}

		} else {
			$jsonarray = ["status" => "error", "message" => "Username Not Registered"];
			echo json_encode($jsonarray);
			die();
		}

	}


} else {

	$jsonarray = ["status" => "error", "message" => "Username Password Not Intitialized"];
	echo json_encode($jsonarray);
	die();

}


?>