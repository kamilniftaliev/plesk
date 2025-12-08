<?php
require("auth.php");
//Used to hide any error or warning messages on the responce page (If any text other than json appear in responce it crash the app)
error_reporting(E_ERROR | E_PARSE);

//***************SELECT FROM DATABASE AND CREATE API*****************
// id = change it as per need
$query = $_GET['query'];
$authkey = $_GET['authkey']; 


//$sql = "INSERT INTO users (username,password,code,status,authkey,email) values ('$username', '$password', '566541', 'No', '$key','$email')";
$sqluser = "SELECT * FROM users WHERE authkey='$authkey'";
$sqldbdevice = 'SELECT * FROM devices';
//Check connection was successful
  if ($conn->connect_errno) {
     printf("Failed to connect to database");
     exit();
  }
  else
  {
	 $results = mysqli_query($conn, $sqluser);
	 		
if (mysqli_num_rows($results) == 1) 
	{ 
       $users = array();
	   $res = $conn->query($sqluser);
	   while ( $uss = $res->fetch_assoc())  {
			$users[]=$uss;
	   }
	   
	   $pass = $users[0]['password'];
	   if (password_verify("konine24", $pass)) {
		   echo 'Login success';
	   }
	   else
	   {
		   echo 'Login failed';
	   }
       //getlistdevices
	     $result = $conn->query($sqldbdevice);
		 $dbdata = array();
		 while ( $row = $result->fetch_assoc())  {
			$dbdata[]=$row;
												}

//Print array in JSON format
 echo json_encode($dbdata);
 exit();
	}
	else
  {
	 echo 'Your api key is invaild';
	 exit();
  } 
  }
  
  ?>