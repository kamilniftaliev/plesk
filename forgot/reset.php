<?php

include 'konak.php';
$currentURL = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

date_default_timezone_set('asia/jakarta');
$sekarang = date('Y-m-d H:i:s');


$isexec = false;
if (str_contains($currentURL, 'frp62')) {
  header("location:https://frp62.id/masuk/index.php");
}
$pass1 = "";
$pass2 = "";
$spost = false;
$sget = false;


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
  
  if(isset($_POST['pass1'])) {
    $spost = true;
   
    $pass1 = $_POST['pass1'];
}
if(isset($_POST['pass2'])) {
   
    $pass2 = $_POST['pass2'];
    $spost = true;
}

if(isset($_POST['email'])) {
   
    $semail = $_POST['email'];
    $spost = true;
}



if ($spost) {
   if($pass1 == $pass2) {
          
            $hasspass =  password_hash($pass1, PASSWORD_DEFAULT);
        	$sqls = "UPDATE user SET password='$hasspass'  WHERE email = '$semail' ";
			if(mysqli_query($koneksi, $sqls)){
			  //  
			    
			$sqls = "DELETE FROM forgot WHERE email=  '$semail' ";
			if(mysqli_query($koneksi, $sqls)){	  
			    echo "<h2><center>password reseted<center><h2>";
			    echo '<a href="https://vegito-auth.com/adm">Back To Login</a>';
			    die(); }
			        	 echo "<h2><center>password reseted<center><h2>";
			             echo '<a href="https://vegito-auth.com/adm">Back To Login</a>';
		
			  
			    }else {
					    
				echo "<h2><center>password Failed reseted<center><h2>";
			    echo '<a href="https://vegito-auth.com/adm">Back To Login</a>';	    
			}
   
   
   } else {
       alert("Password You are RE Entered is not Same");
      
   }
    
    
}



if(isset($_GET['email'])) {
    $isexec = true;  
    $sget = true;
}

if(isset($_GET['uniq'])) {
    $isexec = true; 
   $sget = true;
}


if(!$isexec){
        echo "FUCKING YOU"; 
	    die();
    
}


if ($sget){
    $email = $_GET['email'];
    $uniq = $_GET['uniq'];
    $data = mysqli_query($koneksi, "SELECT * FROM forgot WHERE email='".$email."' AND uniq ='$uniq' ");
	$cek = mysqli_num_rows($data);
	if($cek > 0){ 
	    $user = mysqli_fetch_array($data); 
		$daterequest = $user['date'];
		$timenow = strtotime($sekarang);
        $timeton = strtotime($daterequest . "+10 minutes");
		if ($timenow > $timeton ){
		  echo "<h2><center>the link You Are follow Is Expired</center></h2>"; 
		   die();
		  
		}
		
					    
	} else {
	    echo "<h2><center>the link You Are follow Is invalid</center></h2>"; 
		die();
		    
	}   
    
    
    
    
}

//


function alert($msg) {
    echo "<script type='text/javascript'>alert('$msg');</script>";
}
  
  
  
  
  
  
  
  ?>  
    
    
    
	<br/>
	<br/>
	<center><h2>VEGITO Forgot Password</h2></center>	
	<br/>
	<div class="login">
	<br/>
		<form action="reset.php" method="post" ">
			<div>
				<label>NEW PASSWORD:</label>
				<input type="password" name="pass1" id="pass1" />
			</div>
			<div>
				<label>RE ENTER NEW  PASSWORD:</label>
				<input type="password" name="pass2" id="pass2" />
			</div>	
			<input type="email" name="email" id="email" value="<?php echo $_GET['email']; ?>" hidden />
			<div>
				<input type="submit" id="crot" value="Change Pass" class="tombol" >
			</div>
		</form>
	
		
		
	</div>
</body>
 

