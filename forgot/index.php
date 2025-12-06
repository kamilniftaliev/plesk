<?php
include "konak.php";
require 'vendor/autoload.php';
use PHPMailer\PHPMailer\PHPMailer;
date_default_timezone_set('asia/jakarta');
$sekarang = date('Y-m-d H:i:s');
$currentURL = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";


if (str_contains($currentURL, 'frp62')) {
  header("location:https://frp62.id/masuk/index.php");
}

function generateRandomString($length = 10) {
   $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
   $randomString = substr(str_shuffle($characters), 0, $length);

   return $randomString;
}

?>

<!DOCTYPE html>
<html>
<head>
	<title>Membuat Form Validasi dengan Javascript - WWW.MALASNGODING.COM</title>
	<link rel="stylesheet" type="text/css" href="style.css">
	<style>
a:link, a:visited {
  background-color: #f44336;
  color: white;
  padding: 14px 25px;
  text-align: center;
  text-decoration: none;
  display: inline-block;
}

a:hover, a:active {
  background-color: red;
}


</style>
</head>
<body>
    
<?php 

if(isset($_POST['email'])) {
    $emailuser = $_POST['email'];
    
    
      $data = mysqli_query($koneksi, "SELECT * FROM user  WHERE email='$emailuser' ");
	$cek = mysqli_num_rows($data);
	if($cek > 0){ 
	    
	} else {
	     echo "<h2><center>Email Is Un Registered</h2><br>"; 
	    
	    die();
	}
    
    $data = mysqli_query($koneksi, "SELECT * FROM forgot  WHERE email='$emailuser' ORDER BY id DESC LIMIT 1  ");
	$cek = mysqli_num_rows($data);
	if($cek > 0){ 
	    $user = mysqli_fetch_array($data); 
		$daterequest = $user['date'];
		$timenow = strtotime($sekarang);
        $timeton = strtotime($daterequest . "+10 minutes");
		if ($timenow > $timeton ){

		  
		} else {
	        echo "<h2><center>Plese cek Your Email, Maybe On Spam  Folder</h2><br>"; 
	        $waitime =  date('Y-m-d H:i:s', $timeton);
	        echo "<h2><center>Or Wait Until UTC+7 ".$waitime." </h2></center><br>";
	        die();
		    
		}
		
					    
	}
    


    $random =  generateRandomString(10);
    $mail = new PHPMailer;
    $mail->isSMTP();
    $mail->SMTPDebug = 0;
    $mail->Host = 'smtp.hostinger.com';
    $mail->Port = 587;
    $mail->SMTPAuth = true;
    $mail->Username = 'admin@vegito-auth.com';
    $mail->Password = 'Zahr@666';
    $mail->setFrom('admin@vegito-auth.com', 'Admin');
    $mail->addReplyTo('admin@vegito-auth.com', 'Admin');
    $mail->addAddress($emailuser, 'Customer');
    $mail->Subject = 'Password  Reset';
    $mail->msgHTML(file_get_contents('message.html'), __DIR__);
    $mail->Body = htmlspecialchars('This Is Email To Reset Password https://vegito-auth.com/forgot/reset.php?email='.$emailuser.'&uniq='.$random.'');
//$mail->addAttachment('test.txt');
    if (!$mail->send()) {
        echo 'Mailer Error: ' . $mail->ErrorInfo;
    } else {
        
       
             
$sqls = "INSERT INTO forgot (email,uniq,date) VALUES ('$emailuser','$random', '$sekarang')";
if(mysqli_query($koneksi, $sqls))
			{  echo '<center>The  Email Contain Link For Reset Password  was sent<center>.'; }else{	}
        
    
    }    
}




?>  
	<br/>
	<br/>
	<center><h2>VEGITO Forgot Password</h2></center>	
	<br/>
	<div class="login">
	<br/>
		<form action="index.php" method="post" onSubmit="return validasi()">
			<div>
				<label>email :</label>
				<input type="text" name="email" id="email" />
			</div>
	
			
			<div>
				<input type="submit" value="Send Reset Link" class="tombol">
			</div>
		</form>
	
		
		
	</div>
</body>
 
