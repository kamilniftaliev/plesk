<?php
if(!isset($_GET['configblob'])){ 

return;

}
if(!isset($_GET['serviceId'])){ 

return;

}

?>
<?php
include 'konak.php';
include 'anti.php';

date_default_timezone_set('asia/jakarta');
$timedelay =10;
$sekarang = date('Y-m-d H:i:s');



$serviceId = $_GET['serviceId'];
$configblob = $_GET['configblob'];



$data = mysqli_query($koneksi, "SELECT * FROM server WHERE status='ON' ");
$jumlah = mysqli_num_rows($data);
$i = 0;
while($row = mysqli_fetch_array($data)){
	$i  += 1;
   	$userId = $row['uid'];
	$deviceId = $row['deviceid'];
	$passtoken = $row['passtoken'];
	$mihost = $row['mihost'];
	$apiurl = $row['apiurl'];
	$waktunya = $row['delay'];
	$timetunggu = strtotime($waktunya. "+" .$timedelay. "minutes");
	$timenow = strtotime($sekarang);
	


if($timenow > $timetunggu)
{
	
//$serviceId = $_POST['serviceId'];
//$configblob = $_POST['configblob'];
//$pastoken = $_POST['pastoken'];
//$userId = $_POST['userId'];
//$deviceId = $_POST['deviceId'];
//$HostApi = $_POST['HostApi'];
	
	
	$dataArray = ["apikey"=>"kmzway87aa","serviceId"=>$serviceId,"configblob"=>$configblob,"passtoken"=>$passtoken,"userId"=>$userId, "deviceId"=>$deviceId,"mihost"=>$mihost];
	$kuy = redirect_post("http://". $apiurl,$dataArray);


	$read = json_decode($kuy, true);	
	//echo $read['message'];	
	
	if (!isset($read['status'])) {
		
		if ($i == $jumlah){
		echo $kuy;
		}
		
	} else {
		
		if ($read['status'] = "OK") {
			echo $read['message'];
			die();
		}
			
	
		if ($i == $jumlah){
			if ($read['status']== "" ) {
			echo "Response Null" ; 
			}
		}
		
		
	}

	

}

}


function redirect_post($url, array $dataArray) {


$options = [
    'http' => [
        'header' => "Content-type: application/x-www-form-urlencoded",
        'method' => 'POST',
        'content' => http_build_query($dataArray),
    ],
];

$context = stream_context_create($options);
$result = file_get_contents($url, false, $context);
if ($result === false) {
    /* Handle error */
}
return $result;
}

?>