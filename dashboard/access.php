<?php
require("auth.php");
//Used to hide any error or warning messages on the responce page (If any text other than json appear in responce it crash the app)
error_reporting(E_ERROR | E_PARSE);

//***************SELECT FROM DATABASE AND CREATE API*****************
// id = change it as per need
$gettoken = $_GET['get'];
$username = $_GET['name'];
$password = $_GET['pwd']; 
$sqluser = "SELECT * FROM users WHERE username='$username'";
if ($conn->connect_errno) {
     printf("Failed to connect to database");
     exit();
  }
  else
  {
	  $results = mysqli_query($conn, $sqluser);	
date_default_timezone_set('Asia/Rangoon');	  
      if (mysqli_num_rows($results) == 1) 
	  {
		  $users = array();
	   $res = $conn->query($sqluser);
	   while ( $uss = $res->fetch_assoc())  {
			$users[]=$uss;
	   }
	   
	   $pass = $users[0]['password'];
	   $db_startdate = $users[0]['created_at'];
	   $db_startenddate = $users[0]['expired_at'];
	   $authkeyout = $users[0]['authkey'];
	   $expire = strtotime($db_startenddate);
       $today = date('Y-m-d');
	   
	   if (password_verify($password, $pass)) {
		   if ($today >= $db_startenddate)
		   {
			   echo 'Your account is expired!';
			   
			   
		   }
		   else
		   {
			    echo $authkeyout;
				
			
		   }
		  
		   
	   }
	   else
	   {
		   echo 'Login failed';
	   }
	  }
	  else
	  {
		  echo 'The username that you entered is does not existed';
	  }  
  }
?>