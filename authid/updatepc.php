<?php
require_once './config/config.php';
$link = mysqli_connect("localhost", "root", "", "corephpadmin");
$opreation = $_GET['opreation'];
$authkey = $_GET['authkey'];

  
   date_default_timezone_set('Asia/Rangoon');
$date = date('y-m-d H:i:s');
    $db = getDbInstance();
	$db->where("authkey", $authkey);
	$row = $db->get('pcauth');
	if ($db->count >= 1) {
		$db_create_at = $row[0]['created_at'];
		$db_expired_at = $row[0]['expired_at'];
		$db_pcid_at = $row[0]['pcname'];
		$db_id = $row[0]['id'];
		// updatesession
		if($link === false){
	die("ERROR: Could not connect. "
				. mysqli_connect_error());
}
if ($opreation === 'updatesession'){
	date_default_timezone_set('Asia/Rangoon');
$date = date('y-m-d H:i:s');

$timestamp = strtotime($date) + 60*60*3;
$desktop = gethostname();
$time = date('y-m-d H:i:s', $timestamp);

$data = array("status" => "success", "id" => "$db_id", "desktop_name" => "$desktop", "createdAt" => "$date", "expiredAt" => "$time");

header("Content-Type: application/json");
$json_string = json_encode($data, JSON_PRETTY_PRINT);
echo $json_string;
echo "\n\n\n";
//$expire_time = date('y-m-d H:i:s', $timestamp);
$sql = "UPDATE pcauth SET created_at='$date' ,expired_at='$time' WHERE id='$db_id'";

if(mysqli_query($link, $sql)){
	echo 'A New Session Started Successfully With ' . $desktop ;
} else {
	echo "ERROR: Could not able to execute $sql. "
							. mysqli_error($link);
}
mysqli_close($link);
}
if ($opreation === 'checksession'){
		//checksession
		$time1 = new DateTime($db_expired_at);
		$currDate = new DateTime($db_create_at);
       
$timediff = $currDate->diff(new DateTime($date));
//echo $timediff->format('%y year %m month %d days %h hour %i minute %s second')."<br/>";
//echo "<br>";
$dbhours = $timediff->format('%h');
if ($dbhours >= '3'){
	echo  "status : success<br/>\n";
	echo ' You can start a new session with a new PC.';
echo "<br>";
}
else
{
	echo ' session started in ' . $db_pcid_at;
	 echo "<br>";
	$diff=strtotime($db_expired_at)-strtotime($date); 
echo "diff in seconds: $diff<br/>\n<br/>\n";
$temp=$diff/86400; // 60 sec/min*60 min/hr*24 hr/day=86400 sec/day 

// days 
$days=floor($temp); echo "days: $days<br/>\n"; $temp=24*($temp-$days); 
// hours 
$hours=floor($temp); echo "hours: $hours<br/>\n"; $temp=60*($temp-$hours); 
// minutes 
$minutes=floor($temp); echo "minutes: $minutes<br/>\n"; $temp=60*($temp-$minutes); 
// seconds 
$seconds=floor($temp); echo "seconds: $seconds<br/>\n<br/>\n";
echo 'Remaing time : ' . $hours . ' hours ' . $minutes . ' minutes ' . $seconds . ' seconds' ;
    echo "<br>";

	
}
	}
	//EndCheckSession
	
	//GetAuthInfo
if ($opreation === 'getinfo'){
	$db = getDbInstance();
	$db->where("authkey", $authkey);
	$row = $db->get('admin_accounts');
	if ($db->count >= 1) {
		$username = $row[0]['user_name'];
		$user_id = $row[0]['id'];
		$data = array("status" => "success", "id" => "$user_id", "username" => "$username");

header("Content-Type: application/json");
$json_string = json_encode($data, JSON_PRETTY_PRINT);
//echo $json_string;
       echo 'Status : ' . 'success';
	   echo "\n";
	   echo 'UserID : ' . $user_id;
	   echo "\n";
	   echo 'Username : ' . $username ;
	}
	else {
		echo 'Your auth key is invaild';
	}
  		
	}
	}
	
	else {
	  echo 'status : code 404';
	  echo 'exception : your authroize key is invaild';
	}
	
//

?>
