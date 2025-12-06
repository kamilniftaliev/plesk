<?php
session_start();
require_once './config/config.php';
require_once './includes/auth_validate.php';
require("auth.php");
//Used to hide any error or warning messages on the responce page (If any text other than json appear in responce it crash the app)
error_reporting(E_ERROR | E_PARSE);

//serve POST method, After successful insert, redirect to customers.php page.
if ($_SERVER['REQUEST_METHOD'] === 'POST') 
{
	date_default_timezone_set('Asia/Rangoon');
   
    //Mass Insert Data. Keep "name" attribute in html form same as column name in mysql table.
	$length=20;
   $key = '';
{
	
	list($usec, $sec) = explode(' ', microtime());
	mt_srand((float) $sec + ((float) $usec * 100000));
	
   	$inputs = array_merge(range('z','a'),range(0,9),range('A','Z'));

   	for($i=0; $i<$length; $i++)
	{
   	    $key .= $inputs{mt_rand(0,61)};
	}
}
    $admin_id = $_SESSION['admin_id'];
	$admin_name = $_SESSION['name'];
	$db = getDbInstance();

	    $db->where("user_name", $admin_name);

	    $row = $db->get('admin_accounts');
		if ($db->count >= 1)
		{
			$soldadmin = $row[0]['sold'];
			$left_act = $row[0]['left_act'];
		}
	
	$mode_num = 1;
	$min_left = $left_act -= $mode_num;
	$dbsold = $soldadmin += $mode_num;
	 $date = date('y-m-d H:i:s');
	$username = $_POST['username'];
	$password = password_hash($_POST['password'],PASSWORD_DEFAULT);;
	$email = $_POST['email'];
	$pcid = 'N/A';
	$expireddate = $date;
	$exp = date('y-m-d H:i:s', strtotime('+1 year'));
	$sql = "INSERT INTO users (username,password,email,pcid,created_at,expired_at,authkey,status) values ('$username', '$password', '$email','$pcid', '$date', '$exp','$key','active')";
	$sqluser = "SELECT * FROM users WHERE username='$username'";
	
	$sqlAdmin = "UPDATE admin_accounts SET sold='$dbsold' ,left_act='$min_left' WHERE id='$admin_id'";
	
     if ($conn->connect_errno) {
     printf("Failed to connect to database");
     exit();
  }
  else
  {
	 $results = mysqli_query($conn, $sqluser);
//if api key exist in db			
if (mysqli_num_rows($results) == 1) 
	{ 
       echo '<script>alert("The username that you entered is already existed!")</script>';
	}
	else
    {
		if ($min_left === -1)
		{
			
			echo '<script>alert("Sorry my dear xwa reseller. you are out of activation years!")</script>';
		}
		else
		{
			if(mysqli_query($conn, $sql))
	 {
		 if (mysqli_query($conn, $sqlAdmin))
		 {
			 $_SESSION['admin_sold'] = $dbsold;
			 $_SESSION['admin_left'] = $min_left;
			 $_SESSION['success'] = "New device added successfully!";
    	header('location: customers.php');
    	exit();
		 }
		 else
		 {
			 echo "ERROR: Could not able to execute $sql. " .mysqli_error($conn);
		 }
	
     } 
	  else
     {
	  echo "ERROR: Could not able to execute $sql. " .mysqli_error($conn);
     }	
		}
	  
    

    //Insert timestamp
   // $data_to_store['created_at'] = date('Y-m-d H:i:s');
    
}
  }
}

//We are using same form for adding and editing. This is a create form so declare $edit = false.
$edit = false;

require_once 'includes/header.php'; 
?>
<div id="page-wrapper">
<div class="row">
     <div class="col-lg-12">
            <h2 class="page-header">Add a new activation</h2>
        </div>
        
</div>
    <form class="form" action="" method="post"  id="customer_form" enctype="multipart/form-data">
       <?php  include_once('./forms/device_form.php'); ?>
    </form>
</div>


<script type="text/javascript">
$(document).ready(function(){
   $("#device_form").validate({
       rules: {
            modelname: {
                required: true,
                minlength: 10
            },
            productname: {
                required: true,
                minlength: 3
            },   
        }
    });
});
</script>

<?php include_once 'includes/footer.php'; ?>