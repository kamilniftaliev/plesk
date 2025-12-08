<?php
require_once './config/config.php';
session_name('RESELLER_SESSION');
session_start();
require("auth.php");
error_reporting(E_ERROR | E_PARSE);

//***************SELECT FROM DATABASE AND CREATE API*****************
// id = change it as per need

//Check connection was successful
  if ($conn->connect_errno) {
     printf("Failed to connect to database");
     exit();
  }
$query = $_GET['query'];
$getquery = $_GET['get'];
$username = $_GET['name'];
$passwd = $_GET['pwd'];
if ($query === 'login'){
	
	$db = getDbInstance();

	$db->where("user_name", $username);

	$row = $db->get('admin_accounts');
	if ($db->count >= 1) {
		$db_password = $row[0]['password'];
		$user_id = $row[0]['id'];
		$db_user_name = $row[0]['user_name'];
		
		if (password_verify($passwd, $db_password)) {

			$_SESSION['user_logged_in'] = TRUE;
			$_SESSION['admin_type'] = $row[0]['admin_type'];
			
			//Auth Success
			if ($getquery === 'auth'){
			echo 'Status : Authentication successful. <br>';
			echo 'Account ID: ' . $user_id . '<br>';
			echo 'Account hashed password : ' . $db_password . '<br>';
			echo 'Account name : ' . $db_user_name . '<br>';
			}
			else if ($getquery === 'listdevice'){
			 $s = 'SELECT * FROM spdevices';
			$result = $conn->query($s);
			$dbdata = array();
			while ( $row = $result->fetch_assoc())  {
			$dbdata[]=$row;
			}
			echo json_encode($dbdata);
				
			}
			
			
			//header('Location:jsonout.php');
		}
		else{
		echo 'Wrong password.';
		echo 'Please try again !';
		exit;
	}
	}
	else{
		echo 'The username that you entered is not existed.';
		exit;
	}
}
else {
	echo 'Status : Unknown query';
}
?>