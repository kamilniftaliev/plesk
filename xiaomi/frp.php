<?php


include 'konak.php';
include 'anti.php';
$failed = 'failed';
date_default_timezone_set('asia/jakarta');
$timedelay =8;
$sekarang = date('Y-m-d H:i:s');
$tanggalsekarang = date('Y-m-d');

$timenya =  3;


$data = mysqli_query($koneksi, "SELECT * FROM server ");
$jumlah = mysqli_num_rows($data);
if ($jumlah >0 ){
	while($row = mysqli_fetch_array($data)){
        $delay = $row['delay'];
        $rowserver =  $row['serverproses'];
        $timeton = strtotime($delay. "+" .$timenya. "minutes");
        $serverid = $row['id'];
        if ($rowserver == 1){
         if(strtotime($sekarang) >  $timeton  ){
          	$sqls = "UPDATE server SET serverproses = 0  WHERE id = '".$serverid."' ";
		      if(mysqli_query($koneksi, $sqls)){	}else {	} 
         }
            
        }
   
	}
}











$idcek = 1;
$data = mysqli_query($koneksi, "SELECT * FROM server  where id = '$idcek' ");
$cek = mysqli_num_rows($data);
if($cek > 0){ 
    $user = mysqli_fetch_array($data);

    $datenow = $user['datenow'];

    if (($datenow == $tanggalsekarang)==false){

			$sqls = "UPDATE server SET datenow='$tanggalsekarang' ";
			if(mysqli_query($koneksi, $sqls)){}else {
			//send error report tele
			}
			
			$dataserver = mysqli_query($koneksi, "SELECT * FROM server");
			while($rowdata = mysqli_fetch_array($dataserver)){
					$serid = $rowdata['id'];
					$limitedl = $rowdata['limitedl'];
					$limitfrp = $rowdata['limitfrp'];
					$limitubl = $rowdata['limitubl'];
					$limitfdl = $rowdata['limitfdl'];
					$limitunlock = $rowdata['limitunlock'];
					$sqls = "UPDATE server SET limitleftedl='$limitedl',limitleftfrp='$limitfrp',limitleftubl='$limitubl',limitleftfdl='$limitfdl',limitleftunlock='$limitunlock'  WHERE id ='$serid' ";
					if(mysqli_query($koneksi, $sqls)){}else {}
					
					
			}
			
			
	    
	    
	}

}






$timetunggu = strtotime($sekarang. "+" .$timedelay. "minutes");
$nextdelay = date('Y-m-d H:i:s',$timetunggu);


if(!isset(($_POST['configblob']))){ 
$jsonarray = ["status"=> "error","message" => "Blob Data Not send"];
echo json_encode($jsonarray);
die();
					}


if(!isset(($_POST['serviceId']))){ 
$jsonarray = ["status"=> "error","message" => "Service Id Not Intitialized"];
echo json_encode($jsonarray);
return;
}

if(!isset(($_POST['OTP']))){ 
$jsonarray = ["status"=> "error","message" => "OTP Not Intitialized"];
echo json_encode($jsonarray);
return;

}






$username = anti_injection($_POST['OTP']);

	if ($username != "" ){ 
                	$data = mysqli_query($koneksi, "SELECT * FROM user WHERE apikey='".$username."' ");
					$cek = mysqli_num_rows($data);
					if($cek > 0){ 
						$user = mysqli_fetch_array($data);
					
							$sukseslogin = true;
							$credit = $user['credit'];
							$iduser = $user['id'];
							$apikey = $user['apikey'];
							

					} 	 else
						{
					$jsonarray = ["status"=> "error","message" => "Username Not Registered"];
					echo json_encode($jsonarray);
					die();
						}
			
	
	
	}	else 
	{
		
	$jsonarray = ["status"=> "error","message" => "Username Password Not Intitialized"];
	echo json_encode($jsonarray);
	die();	
		
	}
	


?>
<?php
$servicename = "";
$configblob = anti_injection($_POST['configblob']);
$serviceId = anti_injection($_POST['serviceId']);
$serversupport = "";

if ( $serviceId==1 ){
$serversupport 	= "flash";
    if (strlen($configblob) > 55 ){
        $servicename ="MTK 6";  
    
    
    } else 
    {
    
      $servicename ="QCOM"; 
    
    }




}
if ( $serviceId==2 ){
$serversupport 	= "frp";
$servicename ="FRP";		
}

if ( $serviceId==3 ){
$serversupport 	= "ubl";
$servicename ="UBL";	
}
if ( $serviceId==4 ){
$serversupport 	= "fdl";
$servicename ="FDL";	
}
if ( $serviceId==5 ){
$serversupport 	= "unlock";
$servicename ="Unlock Server";
}
if ( $serviceId==6 ){
$serversupport 	= "flash";
$servicename ="FLASH MTK 5";	
}










$getinfoharga = mysqli_query($koneksi, "SELECT * FROM price WHERE serviceid='".$serviceId."' ");
$infoharga = mysqli_fetch_array($getinfoharga);
$harga = $infoharga['harga'];




if (($credit < $harga) == true) {
    $jsonarray = ["status"=> "error","message" => "Credit Not Enough"];
    echo json_encode($jsonarray);	
	die();
}

///
$data = mysqli_query($koneksi, "SELECT * FROM server WHERE status = 'ON' and delay <= '".$sekarang."' and serversupport LIKE '%".$serversupport."%' ");
$jumlah = mysqli_num_rows($data);
if (!$jumlah >0 ){
    $jsonarray = ["status"=> "error","message" => "All Server For This Service has reach Limit / delayed "];
    echo json_encode($jsonarray);
	$sqls = "UPDATE server SET serverproses = 0  WHERE id = '".$serverid."' ";
	if(mysqli_query($koneksi, $sqls)){	}else 	{	}	
				
    die();	
	
}





//

$i = 1;
$serviceId = $_POST['serviceId'];
while($row = mysqli_fetch_array($data)){
	$i  = $i+ 1;
   	$userId = $row['uid'];
	$deviceId = $row['deviceid'];
	$passtoken = $row['passtoken'];
	$datenow = $row['datenow'];
	$mihost = $row['mihost'];
	$apiurl = $row['apiurl'];
	$waktunya = $row['delay'];
	
    $rowserver =  $row['serverproses'];
    $timenya=  2;
    $timeton = strtotime($sekarang. "+" .$timenya. "minutes");


    if ($rowserver == 1 ){
      
            continue;
    
        
        if ($i == $jumlah){
	        $jsonarray = ["status"=> "error","message" => "All Server For This Service has reach Limit"];
			echo json_encode($jsonarray); 
	    }
    } else {
		$sqls = "UPDATE server SET serverproses = 1  WHERE id = '".$serverid."' ";
		if(mysqli_query($koneksi, $sqls)){	}else {	}
		
	}

	
	
	
	
	
	
	
	$setlimit = $row['setlimit'];
	$serverid = $row['id'];
	$rowlimit = "";
	if ($setlimit=="true"){
		
		if ($serviceId == 1 ) {
			$limit = $row['limitleftedl'];
			$rowlimit = "limitleftedl";
		}
			
		if ($serviceId == 2 ) {
		    $limit = $row['limitleftfrp'];
			$rowlimit = "limitleftfrp";
			
			
		}
		if ($serviceId == 3 ) {
			
			$limit = $row['limitleftubl'];
			$rowlimit = "limitleftubl";
		}
		
		if ($serviceId == 4 ) {
			$limit = $row['limitleftfdl'];
			$rowlimit = "limitleftfdl";
			
		}
		if ($serviceId == 5 ) {
			$limit = $row['limitleftunlock'];
			$rowlimit = "limitleftunlock";	
			
			
		}
		
		if ($serviceId == 6 ) {
			$limit = $row['limitleftedl'];
			$rowlimit = "limitleftedl";
		}
		
		
		
		$message = "Username : ". $username ."\n";
        $message .= "Request : ". $servicename ."\n";
		$message .= "On Server : ". $serverid ."\n";
        telebot($message);
		
		
		if ($datenow == $tanggalsekarang){
			if ($limit == 0 ){
				if ($i == $jumlah){
			    	$jsonarray = ["status"=> "error","message" => "All Server For This Service has reach Limit"];
					echo json_encode($jsonarray);
					
			    	$sqls = "UPDATE server SET serverproses = 0  WHERE id = '".$serverid."' ";
			        if(mysqli_query($koneksi, $sqls)){  }else  {  }	
			 
					$sqls = "UPDATE data SET status='".$failed."' WHERE configblob = '$configblob' ";
			        if(mysqli_query($koneksi, $sqls)){}else {}
				
				
				    die();
				}
				
			
		        $message = "Server:[". $serverid ."]\n";
		        $message .= "has been reach Limit\n";
                telebot($message);
				continue;	
				
			}	
			
		} 
		
	if ($limit == 0 ){
				
	if ($i == $jumlah){
		$jsonarray = ["status"=> "error","message" => "All Server For This Service has reach Limit"];
		echo json_encode($jsonarray);
				
		    $sqls = "UPDATE server SET serverproses = 0  WHERE id = '".$serverid."' ";
		    if(mysqli_query($koneksi, $sqls)){	}else 	{}
			    
		    $sqls = "UPDATE data SET status='".$failed."' WHERE configblob = '$configblob' ";
		    if(mysqli_query($koneksi, $sqls)){}else {}
				
			die();
		}
	continue;	
	}
	}
	
	
		// CEK BLOB IF EXISST
		$getinfo = mysqli_query($koneksi, "SELECT * FROM data WHERE configblob='".$configblob."' ");
		$infoexist = mysqli_num_rows($getinfo);
		$rowresponse = mysqli_fetch_array($getinfo);
		if ($infoexist > 0)
		{
			if (strlen($rowresponse['response'])>512){
				echo $rowresponse['response'];
				$sqls = "UPDATE server SET serverproses = 0  WHERE id = '".$serverid."' ";
				if(mysqli_query($koneksi, $sqls)){}else {}			
				
				die();
			}
			
			$sqls = "UPDATE data SET serverid = '$serverid'  WHERE configblob = '".$configblob."' ";
			if(mysqli_query($koneksi, $sqls)){	}else {		}
	
		
		
			
		} else {
				$sqls = "INSERT INTO data (iduser,serverid,serviceid,configblob,status,tgl) VALUES ('$iduser','$serverid','$serviceId','$configblob','request','$sekarang')";
				if(mysqli_query($koneksi, $sqls))
				{ }else{	}
			
		}
		
		

		

	
	
	//SET SERVER ON PROCESS



    $message = "Call Mi auth\n";
    telebot($message);


	$dataArray = ["apikey"=>"kmzway87aa","serviceId"=>$serviceId,"configblob"=>$configblob,"passtoken"=>$passtoken,"userId"=>$userId, "deviceId"=>$deviceId,"mihost"=>$mihost];
	$kuy = redirect_post($apiurl,$dataArray);
    

	$read = json_decode($kuy, true);	

	
	if (!isset($read['status'])) {
		    $message = "Username : ". $username ."\n";
	        $message .= "Request : ". $servicename ."\n";
		    $message .= "Server [" . $serverid . "]\n";
		    $message .= "failed Read response read status\n";
		    $message .= $kuy;
		    telebot($message);
	
		if ($i == $jumlah){
			$kuy = trim(preg_replace('/\s+/', ' ', $kuy));
			echo $kuy;
			
			
			$sqls = "UPDATE data SET response='$kuy' ,status='".$failed."' WHERE configblob = '".$configblob."' ";
			if(mysqli_query($koneksi, $sqls)){	}else {	}
		
			$sqls = "UPDATE server SET serverproses = 0  WHERE id = '".$serverid."' ";
			if(mysqli_query($koneksi, $sqls)){	}else 	{}
			
			die();	
	
			
			
		}
		
	} else {
		
		if ($read['status'] == "OK") {
		    $message = "Username : ". $username ."\n";
	        $message .= "Request : ". $servicename ."\n";
		    $message .= "Server [" . $serverid . "]\n";
		    $message .= "success\n";
		    
		    telebot($message);
		    
			$sisalimit = $limit - 1;
			
			$sqls = "UPDATE server SET $rowlimit = '$sisalimit' ,delay = '$nextdelay' WHERE id = '$serverid' ";
			if(mysqli_query($koneksi, $sqls)){}else {}
			
			$sisalimit = $credit - $harga;
			$sqls = "UPDATE user SET credit = '$sisalimit'  WHERE id = '$iduser' ";
			if(mysqli_query($koneksi, $sqls)){}else {}
			
			
		    echo $kuy;
			$response = $read['message'];	
				
		
			$kuy = trim(preg_replace('/\s+/', ' ', $kuy));
			$done = "done";
			$sqls = "UPDATE data SET response = '$kuy' ,status='".$done."' WHERE configblob = '$configblob' ";
			if(mysqli_query($koneksi, $sqls)){	}else {}
			
			
			$sqls = "UPDATE server SET serverproses = 0  WHERE id = '".$serverid."' ";
			if(mysqli_query($koneksi, $sqls)){	}else 	{}	
					
		    
			
			
	//	''	$dataArray = ["apikey"=>$apikey,"credit"=>$sisalimit];
	//	    $url = "https://azegsm.com/apipragma/crosscredit.php";
	//	    $kuy = redirect_post($url,$dataArray);
		
		    
			die();
			
		} 

		else 
		{
		    $kuy = trim(preg_replace('/\s+/', ' ', $kuy));
		    $sqls = "UPDATE server SET serverproses = 0  WHERE id = '".$serverid."' ";
			if(mysqli_query($koneksi, $sqls)){	}else 	{}	
		    
		    if(str_contains($kuy,"40011")){
		        $habis = 0;
		       	$sqls = "UPDATE server SET $rowlimit = '$habis' WHERE id = '$serverid' ";
			    if(mysqli_query($koneksi, $sqls)){
			       	  $message = "Set LImit 0 For Service Id [" . $serviceId . "]\n";
    	              $message .= "success\n";
		              telebot($message); 
			        
			    }else {}
		        
		    }
		    
		    
		    
		    
		    
		    $message = "Username : ". $username ."\n";
	        $message .= "Request : ". $servicename ."\n";
		    $message .= "Server [" . $serverid . "]\n";
		    $message .= "failed\n";
		    $message .= $kuy;
		    telebot($message);
	
			
		
			
			
			
		
		    
		   // Please upgrade your
		
		    if(str_contains($kuy,"turning off")){
		       	$jsonarray = ["status"=> "error","message" => "Find Device Is On"];
				echo json_encode($jsonarray);
		        die();
		        
		    }
		      if(str_contains($kuy,"You are,")){
		       	$jsonarray = ["status"=> "error","message" => "Maaf Hp CICILAN GAGAL GAK BISA DI FLASH"];
				echo json_encode($jsonarray);
		        die();
		        
		    }
		      if(str_contains($kuy,"Blob Config Not Valid")){
		        $jsonarray = ["status"=> "error","message" => "BLOB key INVALID"];
				echo json_encode($jsonarray);
		        die();
		        
		    }
		    if(str_contains($kuy,"Please upgrade your")){
		        $jsonarray = ["status"=> "error","message" => "BLOB key Sent Encryption is INVALID"];
				echo json_encode($jsonarray);
		        die();
		        
		    }
		    if(str_contains($kuy,"Request parameter,")){
		       	$jsonarray = ["status"=> "error","message" => "Blob key invalid"];
				echo json_encode($jsonarray);
		        die();
		        
		    }

		    
			$sqls = "UPDATE data SET response = '".$kuy."' ,status='".$failed."' WHERE configblob = '$configblob' ";
			if(mysqli_query($koneksi, $sqls)){}else {}
			
			
			
			if ($i == $jumlah){
	            echo $kuy;
			}
			
		}
			
	
		
	}

	

$i++;

}









function redirect_post($url, array $dataArray) {

$options = [
    'http' => [
        'header' => "Content-type: application/x-www-form-urlencoded\r\n",
        'method' => 'POST',
        'timeout' => 15,
        'content' => http_build_query($dataArray),
    ],
];

$context = stream_context_create($options);
$result = file_get_contents($url, false, $context);

if ($result===false){
    $message = "Error Datapost \n";
    $message = "on Blobkey" .$dataArray['configblob']. "\n";
    telebot($message);
       //   $jsonarray = ["status"=> "error","message" => "SERVER BUSSY"];
	//	  echo json_encode($jsonarray);
    //
}
   


return $result;


}

function telebot($message) {
$token = "76944303:AAF3dNoD07QIcUqony9ti_CPz7i62w32qAE";
$chatid = "-1002383474691";
$url = 'https://api.telegram.org/bot76944395:AAF3dNoD07QIcUqony9ti_CPz7i62w32qAE/sendMessage?chat_id=-1002474691&text='.urlencode($message)  ;
$result = file_get_contents($url, false, $context);
if ($result === false) {
    /* Handle error */
}
return $result;
}



?>