<?php
require_once './config/config.php';
require("auth.php");
session_start();
error_reporting(E_ERROR | E_PARSE);

//***************SELECT FROM DATABASE AND CREATE API*****************
// id = change it as per need

//Check connection was successful
  if ($conn->connect_errno) {
     printf("Failed to connect to database");
     exit();
  }

//Get DB instance. function is defined in config.php
$db = getDbInstance();

if ( $_SESSION['user_logged_in'] === TRUE){
	$s = 'SELECT * FROM spdevices';



//Fetch 3 rows from actor table
  $result = $conn->query($s);

//Initialize array variable
  $dbdata = array();

//Fetch into associative array
  while ( $row = $result->fetch_assoc())  {
	$dbdata[]=$row;
  }

//Print array in JSON format
 echo json_encode($dbdata);
	}
	else{
		echo 'Status : Authentication is required. <br>';
		echo 'You need to login your QC Auth Pro account from console <br>';
		echo '<br>';
		echo 'console: usage: https://api.auth.qcauthpro.com/console?query=login&name=Yourname&pwd=YourPassword<br>';
	}


	
?>