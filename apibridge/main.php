<?php

include 'konak.php';
include 'anti.php';
$failed = 'failed';
date_default_timezone_set('asia/jakarta');
$timedelay = 0;
$sekarang = date('Y-m-d H:i:s');
$tanggalsekarang = date('Y-m-d');
$hari = date('l', strtotime($sekarang));

$timenya =  0;

			

$timezone = time() + (60 * 60 * 8);
$chinatime =  gmdate('Y-m-d', $timezone);


$data = mysqli_query($koneksi, "SELECT * FROM server ");
$jumlah = mysqli_num_rows($data);
if ($jumlah >0 ){ // restore server on process
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





$idcek = 4;
$data = mysqli_query($koneksi, "SELECT * FROM server  ");
$cek = mysqli_num_rows($data);
if($cek > 0){ 
    $user = mysqli_fetch_array($data);
  //  telebot("test Check report and reset");
 //   telebot2("test Check report and reset");
    $datenow = $user['datenow'];

    if (($datenow == $chinatime)==false){

			$sqls = "UPDATE server SET datenow='$chinatime' ";
			if(mysqli_query($koneksi, $sqls)){}else {
			//send error report tele
			}

            reportDaily($koneksi);
			$dataserver = mysqli_query($koneksi, "SELECT * FROM server");
			while($rowdata = mysqli_fetch_array($dataserver)){
					$serid = $rowdata['id'];
					$limitedl = $rowdata['limitedl'];
					$limitfrp = $rowdata['limitfrp'];
					$limitubl = $rowdata['limitubl'];
					$limitfdl = $rowdata['limitfdl'];
					$limitunlock = $rowdata['limitunlock'];
					$sqls = "UPDATE server SET limitleftedl='$limitedl',limitleftfrp='$limitfrp',limitleftubl='$limitubl',limitleftfdl='$limitfdl',limitleftunlock='$limitunlock'  WHERE id ='$serid' ";
					if(mysqli_query($koneksi, $sqls)){
					    telebot("reset limit  success");
			            telebot2("reset limit  Success");
					}else {}
					
					
			}
			
			
	    
	    
	}

}




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

if(!isset(($_POST['username']))){ 
$jsonarray = ["status"=> "error","message" => "Username Not Intitialized"];
echo json_encode($jsonarray);
return;

}


if(!isset(($_POST['password']))){ 
$jsonarray = ["status"=> "error","message" => "Password Not Intitialized"];
echo json_encode($jsonarray);
return;

}
$platformname = "none";
$projectname = "none";
if(isset(($_POST['projectname']))){ 
	$projectname = $_POST['projectname'];

}
if(isset(($_POST['platformname']))){ 
	$platformname = $_POST['platformname'];

}
//telebot($projectname);

$configblob = anti_injection($_POST['configblob']);
$serviceId = anti_injection($_POST['serviceId']);

$configblob = str_replace(" ","+", $configblob);

$blobasli = $configblob;

if(isset(($_POST['userdevice']))){ 

$userdevice = anti_injection($_POST['userdevice']);
} else {
    $userdevice = "xxx";
}




if ($serviceId == 2 || $serviceId == 4) {
  $timedelay = 0;  
    
}






$v6 = false;

$timetunggu = strtotime($sekarang. "+" .$timedelay. "minutes");
$nextdelay = date('Y-m-d H:i:s',$timetunggu);

$username = anti_injection($_POST['username']);
$password = anti_injection($_POST['password']);
	if ($username != "" && $password != ""){ 
		if (strpos($username, '@')==true) { 
			$data = mysqli_query($koneksi, "SELECT * FROM user WHERE email='".$username."' ");
			$cek = mysqli_num_rows($data);
			if($cek > 0){ 
				$user = mysqli_fetch_array($data);
				$hashpassword = $user['password'];
				if (password_verify($password, $hashpassword)) {
					$sukseslogin = true;
					$credit = $user['credit'];
					$iduser = $user['id'];
					$apikey = $user['apikey'];
					$paket = $user['frp'];
					$v6 = $user['v6'];
					$sekarangggggg = $user['activev6'];
					$jumlahv6nya = $user['jumlahv6'];
					$uname = $user['username'];
					$statuson = $user['statuson'];
					$setprice = $user['price'];
					$frp_price = $user['frp_price'];
					$fdl_price = $user['fdl_price'];
					$qcom_price = $user['qcom_price'];
					$ubl_price = $user['ubl_price'];
					$v6_price = $user['v6_price'];
					$v5_price = $user['v5_price'];
					$v6new_price = $user['v6new_price'];
					$v5new_price = $user['v5new_price'];
						} else 
						{
					$jsonarray = ["status"=> "error","message" => "Wrong Password"];
					echo json_encode($jsonarray);
					die();
						}
				} else 
				{ 
					$jsonarray = ["status"=> "error","message" => "email Not Registered"];
					echo json_encode($jsonarray);
					die(); }
					
			} else {
					$data = mysqli_query($koneksi, "SELECT * FROM user WHERE username='".$username."' ");
					$cek = mysqli_num_rows($data);
					if($cek > 0){ 
						$user = mysqli_fetch_array($data);
						$hashpassword = $user['password'];
						if (password_verify($password, $hashpassword)) {
							$sukseslogin = true;
							$credit = $user['credit'];
							$iduser = $user['id'];
							$apikey = $user['apikey'];
							$paket = $user['frp'];
							$v6 = $user['v6'];
							$sekarangggggg = $user['activev6'];
					        $jumlahv6nya = $user['jumlahv6'];
							$uname = $user['username'];
							$statuson = $user['statuson'];
							$setprice = $user['price'];
							$frp_price = $user['frp_price'];
							$fdl_price = $user['fdl_price'];
							$qcom_price = $user['qcom_price'];
							$ubl_price = $user['ubl_price'];
							$v6_price = $user['v6_price'];
							$v5_price = $user['v5_price'];
							$v6new_price = $user['v6new_price'];
							$v5new_price = $user['v5new_price'];
						

							} else 
							{
								$jsonarray = ["status"=> "error","message" => "Wrong Password"];
								echo json_encode($jsonarray);
								die();
							}

					} 	 else
						{
					$jsonarray = ["status"=> "error","message" => "Username Not Registered"];
					echo json_encode($jsonarray);
					die();
						}
			
				}
	
	
	}	else 
	{
		
	$jsonarray = ["status"=> "error","message" => "Username Password Not Intitialized"];
	echo json_encode($jsonarray);
	die();	
		
	}
	


?>
<?php
$username = $uname;
if($uname == "aku"){
  //	$jsonarray = ["status"=> "error","message" => "Username Password Not Intitialized"];
//	echo json_encode($jsonarray);
//die();	
		
    
}

if ( $serviceId== 8){
$serversupport 	= "flash";
$servicename ="FLASH MTK NEW";		
}
if ( $serviceId== 10){
$serversupport 	= "flash";
$servicename ="FLASH MTK MALACHITE";		
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
if ( $serviceId==11 ){
$serversupport 	= "flash";
$servicename ="FLASH MTK 5 NEW";	
}
if ( $serviceId==9 ){
$serversupport 	= "flash";
$servicename ="FLASH MTK 6 OLD ";	
}





if($statuson == "no"){
    telebot($uname . " Temporary Disable Auth Service");
    $jsonarray = ["status"=> "error","message" => "Your Account Disable Temporary Using Auth Service"];
    echo json_encode($jsonarray);
    die();	
		
    
}
$servicename = "";

$serversupport = "";
$ismtk = false;
if ($serviceId == 1 || $serviceId == 9 ){
    $jjj = base64_decode($configblob);
    $hextoken =  (bin2hex($jjj));
    $ismtks =  substr($hextoken,0,4);
    if($ismtks == '0200'){
       // telebot('yes mtk');
       if($uname == 'cftools'   || $username == 'f' ) {
          
       } else {  $serviceId = 9; } 
       
       
       
        $ismtk = true;    
        $servicename ="MTK v6"; 
    } else {
        $servicename ="QCOM EDL";  
        $serviceId == 1;
    }
    

   		
}



if($serviceId == 2 || $serviceId == 4){
    $g= base64_decode($configblob);
    $ty = json_decode($g);
    $token = $ty->token;
    $token8 = substr($token, 0, 8);
    
    
} else {
   $token =  $configblob;
    
}




if($serviceId==1){
 $token =   str_replace(" ","+",$token);
}
if($serviceId==9){
 $token =   str_replace(" ","+",$token);
}
if($serviceId==8){
 $token =   str_replace(" ","+",$token);
}
if($serviceId==10){
 $token =   str_replace(" ","+",$token);
}
if($serviceId==11){
 $token =   str_replace(" ","+",$token);
}



$getinfostatus = mysqli_query($koneksi, "SELECT * FROM server WHERE status ='ON' ");

while($infostatusOn = mysqli_fetch_array($getinfostatus)){
   $frpstatus = $infostatusOn['frpon'];
    $fdlstatus = $infostatusOn['fdlon'];
    $edlstatus = $infostatusOn['edlon'];
    $ublstatus = $infostatusOn['ublon'];
    break;
    
}



if ($serviceId == 2){
    if ($frpstatus == 0 ) {
            $jsonarray = ["status"=> "error","message" => "FRP STATUS OFF"];
            echo json_encode($jsonarray);	
   	       // telebot("test Server frp OFF");
	        die(); 
    }
}

if ($serviceId == 4){
    if ($fdlstatus == 0 ) {
            $jsonarray = ["status"=> "error","message" => "FDL STATUS OFF"];
            echo json_encode($jsonarray);	
   	     //   telebot("test Server fdl OFF");
	        die(); 
    }
}
if ($serviceId == 5){
    if ($ublstatus == 0 ) {
            $jsonarray = ["status"=> "error","message" => "ubl STATUS OFF"];
            echo json_encode($jsonarray);	
   	      //  telebot("test Server ubl OFF");
	        die(); 
    }
}

if ($serviceId == 1 || $serviceId == 9 || $serviceId == 8){
    if ($edlstatus == 0 ) {
          $jsonarray = ["status"=> "error","message" => "FLASH STATUS OFF"];
        echo json_encode($jsonarray);	
   	      //  telebot("test Server Flash OFF " . $edlstatus);
	      die(); 
   }
}



$getinfoharga = mysqli_query($koneksi, "SELECT * FROM price WHERE serviceid='".$serviceId."' ");
$infoharga = mysqli_fetch_array($getinfoharga);
$harga = $infoharga['harga'];
$potong = $harga;

$iscn = false;
$ishabis = false;

	if ($setprice == 1  ){
				if ($serviceId == 1 ){ 
					if($qcom_price=="0"){}else{$harga = $qcom_price;}
				} elseif($serviceId == 2 ){
					if($frp_price=="0"){}else{$harga = $frp_price;}
				} elseif($serviceId == 3 ){
					if($ubl_price=="0"){}else{$harga = $ubl_price;}
				} elseif($serviceId == 4 ){
					if($fdl_price=="0"){}else{$harga = $fdl_price;}
				} elseif($serviceId == 5 ){
				} elseif($serviceId == 6 ){
					if($v5_price=="0"){}else{$harga = $v5_price;}
				} elseif($serviceId == 8 ){
					if($v6new_price=="0"){}else{$harga = $v6new_price;}
				} elseif($serviceId == 9 ){
					if($v6_price=="0"){}else{$harga = $v6_price;}
				} elseif($serviceId == 10 ){
					if($v5_price=="0"){}else{$harga = $v5_price;}
				} elseif($serviceId == 11 ){
					if($v5new_price=="0"){}else{$harga = $v5new_price;}
				}
		}





if (($credit < $harga) == true) { //  if credit not enough
    
    if ( $serviceId== 8){
        $serversupport 	= "flash";
        $servicename ="FLASH MTK NEW";		
    }
    if ( $serviceId== 10){
        $serversupport 	= "flash";
        $servicename ="FLASH MTK MALACHITE";		
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
if ( $serviceId==11 ){
    $serversupport 	= "flash";
    $servicename ="FLASH MTK 5 NEW";	
}
if ( $serviceId==9 ){
    $serversupport 	= "flash";
    $servicename ="FLASH MTK 6 OLD ";	
}
    $jsonarray = ["status"=> "error","message" => "Credit Not Enough, cek price update ,Please Contact reseller"];
    echo json_encode($jsonarray);	
   	        $message = "Username : ". $username ."\n";
            $message .= "Request : ". $servicename ."\n";
            $message .= "Credit Not Enough\n";
            telebot($message);
            telebot2($message);
	        die(); 
   
    

    

} 
       
       
      
if($ismtk){
    $getinfopatch = mysqli_query($koneksi, "SELECT * FROM multipatch WHERE id='1' ");
    $infoPatch = mysqli_fetch_array($getinfopatch);
    $optionpatch = $infoPatch['patchflash'];
    $multipliers = $infoPatch['multipliers'];
    
    $tokenasli = $token;
    $jjj = base64_decode($token);
    $hextoken =  substr(bin2hex($jjj), -32);
    $empty = "0000000000000000000000000000000000000000000000000000000000000000";
    $deviceid = "0000000000000000000000000000000000000000000000000000000000000000";

    $nonya = 0;
    foreach(file('newfile.txt') as $line) {
        $nonya++;
        $deviceid =  $line;
    
        if($deviceid == $empty){
        
        } else {
        
            break;  
        
        }
    }




    if ($deviceid == $empty){
        GenerateDevicesId($multipliers);
        $no = 0;
        foreach(file('newfile.txt') as $line) {
            $no++;
            $deviceid =  $line;
    
            if($deviceid == $empty){
        
            } else {
        
                break;  
        
            }
        }

    }
    $numflash = "0";
    if($multipliers == 3 ) {$numflash==76;}elseif($multipliers == 2){$numflash == 51;}else{$numflash == 11;}
    
    
    if ($nonya == $numflash){
        GenerateDevicesId($multipliers);
        $no = 0;
        foreach(file('newfile.txt') as $line) {
            $no++;
            $deviceid =  $line;
    
            if($deviceid == $empty){
        
            } else {
        
                break;  
        
            }
        }
    }


    Timpa($nonya);
    
    
  
    

    
    //    $optionpatch = "multi" ;//  this use multi flash

 
    if ($optionpatch == "1"){
        $deviceid =  str_replace("\n", '', $deviceid); // patch multi flash
        $tokenawal = "0200000001340220" . $deviceid ."0310";
        $tokens = $tokenawal . $hextoken;
        $token = base64_encode(hex2bin($tokens));   
        $configblob = $token;
    	 if($serviceId==11){$configblob = $tokenasli; }  
         if($serviceId==8){$configblob = $tokenasli; } 

    } elseif ($optionpatch == "2"){
        $deviceid  = GenerateRandomDevices(64); // patch random random 1 time
        $deviceid =  str_replace("\n", '', $deviceid); // patch multi flash
        $tokenawal = "0200000001340220" . $deviceid ."0310";
        $tokens = $tokenawal . $hextoken;
        $token = base64_encode(hex2bin($tokens));   
        $configblob = $token;
         if($serviceId==11){$configblob = $tokenasli; }  
         if($serviceId==8){$configblob = $tokenasli; }  
    } else {
        
        $configblob = $tokenasli; // original token this use original token
    }
    
    

    
    
    

}











$data = mysqli_query($koneksi, "SELECT * FROM server WHERE status = 'ON' and delay <= '".$sekarang."' and serversupport LIKE '%".$serversupport."%' "); 
if($serviceId== 2 || $serviceId == 4 ){
   $data = mysqli_query($koneksi, "SELECT * FROM server WHERE status = 'ON'  and serversupport LIKE '%".$serversupport."%' ");  
}
elseif ($serviceId== 3) {
     $data = mysqli_query($koneksi, "SELECT * FROM server WHERE status = 'ON'  and serversupport LIKE '%ubl%' ");  
} else {
    
}
  



$jumlah = mysqli_num_rows($data);












$i = 1;
$idb = "1";
$getinfo = mysqli_query($koneksi, "SELECT * FROM backups WHERE id='".$idb."' ");
$infoexist = mysqli_num_rows($getinfo);
$rowactive = mysqli_fetch_array($getinfo);
$serveractive = $rowresponse['serverid'];



$idb = "1";
$queryactive = mysqli_query($koneksi, "SELECT * FROM active WHERE id='".$idb."' ");
$infoactive = mysqli_num_rows($queryactive);
$rowactive = mysqli_fetch_array($queryactive);
$serveractive = $rowactive['serverid'];
//$serveractive = 17;



$getsavenode = mysqli_query($koneksi, "SELECT * FROM savemode WHERE id='1' ");
$savemodearray = mysqli_fetch_array($getsavenode);
$isSave = $savemodearray['status'];


$getsavenodefrp = mysqli_query($koneksi, "SELECT * FROM backupsfrp WHERE id='1' ");
$savemodearrayfrp = mysqli_fetch_array($getsavenodefrp);
$isSavefrp = $savemodearrayfrp['status'];


if ( $serviceId== 8){
    $serversupport 	= "flash";
    $servicename ="FLASH MTK NEW";		
}
if ( $serviceId== 10){
    $serversupport 	= "flash";
    $servicename ="FLASH MTK MALACHITE";		
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

if ( $serviceId==9 ){
    $serversupport 	= "flash";
    $servicename ="FLASH MTK 6 OLD ";	
}

if ( $serviceId==11 ){
    $serversupport 	= "flash";
    $servicename ="FLASH MTK 5 NEW ";	
}



while($row = mysqli_fetch_array($data)){

//	$idserver  = $row['id'];

	$datenow = $row['datenow'];
	$mihost = $row['mihost'];
	$apiurl = $row['apiurl'];
	$waktunya = $row['delay'];
	$setlimit = $row['setlimit'];
	$serverid = $row['id'];
    $rowserver =  $row['serverproses'];
    $timenya=  2;
    $timeton = strtotime($sekarang. "+" .$timenya. "minutes");
    $datcek = mysqli_query($koneksi, "SELECT * FROM server WHERE id = '".$serverid."'  ");
    $rowrun = mysqli_fetch_array($datcek);
    $rowserver =  $rowrun['serverproses'];
    $userId = $row['uid'];
	$deviceId = $row['deviceid'];
	$passtoken = $row['passtoken'];
    $servicetoken = $row['servicetoken'];
    $unlockApiph = $row['unlockApiph'];
    $ssecurity = $row['ssecurity'];
    $islogin = $row['islogin'];
    
 

  

    if($iscn == true){
       if(str_contains($mihost,"unlock.update.miui.com")){
           // when use china ID will auto to search id china
       
       
       
       
        } else {
           $i++;
          continue;
            
        }
    }

    $rowlimit = "";



if ($setlimit=="true"){
    if ($serviceId == 1 ) {
            
            $limit = $row['limitleftedl'];
	        $rowlimit = "limitleftedl";
    }
        

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
    $limit= $row['limitleftfdl'];
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
    
    if ($serviceId == 9 ) {
    $limit = $row['limitleftedl'];
	$rowlimit = "limitleftedl";
}
    if ($serviceId == 11 ) {
    $limit = $row['limitleftedl'];
	$rowlimit = "limitleftedl";
}
    if ($serviceId == 10 ) {
    $limit = $row['limitleftedl'];
	$rowlimit = "limitleftedl";
}
    if ($serviceId == 8 ) {
    $limit = $row['limitleftedl'];
	$rowlimit = "limitleftedl";
}

    
if ($datenow == $tanggalsekarang){
	    if ($limit == 0 ){
		    if ($i == $jumlah){

		        
			    $jsonarray = ["status"=> "error","message" => "All Server For This Service has reach Limit"];
				echo json_encode($jsonarray);
            	$sqls = "UPDATE server SET serverproses = 0  WHERE id = '".$serverid."' ";
			    if(mysqli_query($koneksi, $sqls)){  }else  {  }	
	            	$sqls = "UPDATE data SET status='".$failed."' WHERE configblob = '$token' ";
			        if(mysqli_query($koneksi, $sqls)){}else {}
				         die();
			}

            $i++;
		    continue;	
				
		}	
			
} 
		
		
	

		
if ($limit == 0 ){
				
	if ($i == $jumlah){
        $jsonarray = ["status"=> "error","message" => "All Server For This Service has reach Limit"];
		echo json_encode($jsonarray);
        $sqls = "UPDATE server SET serverproses = 0  WHERE id = '".$serverid."' ";
		if(mysqli_query($koneksi, $sqls)){	}else 	{}
            $sqls = "UPDATE data SET status='".$failed."' WHERE configblob = '$token' ";
		    if(mysqli_query($koneksi, $sqls)){}else {}
            die();
		}
		$i++;
    continue;	
}

	
$getinfo = mysqli_query($koneksi, "SELECT * FROM data WHERE configblob='".$token."' ");
$infoexist = mysqli_num_rows($getinfo);
$rowresponse = mysqli_fetch_array($getinfo);
if ($infoexist > 0)
    {
			if (strlen($rowresponse['response'])>512){
				$res =  $rowresponse['response'];
				if ($serviceId==1){
				  $res =  substr_replace($res, '', 25, 1);
		          $res =  substr_replace($res, '', 636, 1);
	            
		            echo $res; 
				    
				} else {
				     echo $res; 
				    
				}
            	$sqls = "UPDATE server SET serverproses = 0  WHERE id = '".$serverid."' ";
				if(mysqli_query($koneksi, $sqls)){}else {}			
				
				die();
			}
			
			$sqls = "UPDATE data SET serverid = '$serverid'  WHERE configblob = '".$token."' ";
			if(mysqli_query($koneksi, $sqls)){	}else {		}
	
		
		
			
		}
		else {
		        if($userdevice== "xxx") {
		           $userdevice = "zero"; 
		        }
				$sqls = "INSERT INTO data (iduser,name,serverid,serviceid,configblob,status,tgl,userdevice) VALUES ('$iduser','$uname','$serverid','$serviceId','$token','request','$sekarang','$userdevice')";
				if(mysqli_query($koneksi, $sqls))
				{ }else{	}
			
}
		
	

	    

		
//$pings = pingserver($apiurl);
//if($pings){

//}
//else {
 //   telebot('ping server ' . $apiurl . ' Failed');
//    $i++;
 //   continue;  
//}
	
$url = "";
 
$url = akunlogin($username,$apiurl);



    $message = "Username : ". $username ."\n";
    $message .= "Request : ". $servicename ."\n";
    $message .= "Server  : ". $serverid ."\n";
    $message .= "Service Id  : ". $serviceId ."\n";
	
	$message .= "Projectname  : ". $projectname ."\n";
	$message .= "Platformname  : ". $platformname ."\n";
    $message .= "Call Mi auth\n";
 
    telebot($message);
    telebot2($message);
if ($islogin == "yes"){

    $dataArray = ["apikey"=>"kmzway87aa","serviceId"=>$serviceId,"configblob"=>$configblob,"servicetoken"=>$servicetoken,"userId"=>$userId, "unlockApiph"=>$unlockApiph,"mihost"=>$mihost,"ssecurity"=>$ssecurity,"projectname"=>$projectname,"platformname"=>$platformname];
   
	$kuy = redirect_post($url,$dataArray);  
    
} else {
    telebot("login Service Token");
    telebot2("login Service Token");
    $sukseslogin = XiaomiLogin($apiurl,$passtoken,$userId,$deviceId,$mihost,$unlockApiph,$ssecurity,$servicetoken);
    if($sukseslogin) {
        telebot("success login");
        telebot2("Sucess Login Auth");
        $yes = "yes";
        $sqls = "UPDATE server SET islogin = 'yes' ,servicetoken ='$servicetoken',ssecurity='$ssecurity',unlockapiph='$unlockApiph'  WHERE id = '".$serverid."' ";
		if(mysqli_query($koneksi, $sqls)){}else {}	
        

    } else {
     
        telebot("Login Failed : " );
        telebot2("Login Failed : " );
        $i++;
        continue;
    }
    
    
    if ($uname == 'cftools'  || $username == 'hhh' ) {
        $url = "https://" . $apiurl ."/apicf2.php";    
    } else {
    
       $url = "https://" . $apiurl ."/apimi2.php";  
    
    }  
    
    $dataArray = ["apikey"=>"kmzway87aa","serviceId"=>$serviceId,"configblob"=>$configblob,"servicetoken"=>$servicetoken,"userId"=>$userId, "unlockApiph"=>$unlockApiph,"mihost"=>$mihost,"ssecurity"=>$ssecurity,"projectname"=>$projectname,"platformname"=>$platformname];
        $kuy = redirect_post($url,$dataArray);  

}


  

    $read = json_decode($kuy, true);	

	
    if (!isset($read['status'])) {
	    $message = "Username : ". $username ."\n";
	    $message .= "Request : ". $servicename ."\n";
		$message .= "Server [" . $serverid . "]\n";
		$message .= "failed Read response read status\n";
		$message .= "Remain Credit " . $credit ."\n";
		$message .= $kuy;
		telebot($message);
		telebot2($message);
        $cn = '{"status" : "error","message":"Server Timeout"}';
		$sqls = "UPDATE data SET response = '$cn' ,status='failed' WHERE configblob ='$token' ";
	    if(mysqli_query($koneksi, $sqls)){	}else 	{	}
		   
	    $jsonarray = ["status"=> "error","message" => "BLOB key Sent Encryption is INVALID"];
		echo json_encode($jsonarray);
		die();

	if ($i == $jumlah){

		    
		    
			$kuy = trim(preg_replace('/\s+/', ' ', $kuy));
			echo $kuy;
			
			
			$sqls = "UPDATE data SET response='$kuy' ,status='".$failed."' WHERE configblob = '".$token."' ";
			if(mysqli_query($koneksi, $sqls)){	}else {	}
		
			$sqls = "UPDATE server SET serverproses = 0  WHERE id = '".$serverid."' ";
			if(mysqli_query($koneksi, $sqls)){	}else 	{}
			
			die();	
	
			
			
    }
		
} 
	else {
		
		if ($read['status'] == "OK") {
		  
		    
		    
			 $sisalimit = $limit - 1;

			 
			 $sqls = "UPDATE server SET $rowlimit = '$sisalimit' ,delay = '$nextdelay' WHERE id = '$serverid' ";
			 if(mysqli_query($koneksi, $sqls)){}else {}  

	
			if ($setprice == 1  ){
				if ($serviceId == 1 ){ 
					if($qcom_price=="0"){}else{$potong = $qcom_price;}
				} elseif($serviceId == 2 ){
					if($frp_price=="0"){}else{$potong = $frp_price;}
				} elseif($serviceId == 3 ){
					if($ubl_price=="0"){}else{$potong = $ubl_price;}
				} elseif($serviceId == 4 ){
					if($fdl_price=="0"){}else{$potong = $fdl_price;}
				} elseif($serviceId == 5 ){
				} elseif($serviceId == 6 ){
					if($v5_price=="0"){}else{$potong = $v5_price;}
				} elseif($serviceId == 8 ){
					if($v6new_price=="0"){}else{$potong = $v6new_price;}
				} elseif($serviceId == 9 ){
					if($v6_price=="0"){}else{$potong = $v6_price;}
				} elseif($serviceId == 10 ){
					if($v5_price=="0"){}else{$potong = $v5_price;}
				} elseif($serviceId == 11 ){
					if($v5new_price=="0"){}else{$potong = $v5new_price;}
				}
		}
		
			if($serviceId==8){
				if($projectname == 'degas' || $projectname == 'rothko' || $projectname == 'duchamp'){
					$potong = 0;
				}
			}
	
             $sisalimit = $credit - $potong;
			$sqls = "UPDATE user SET credit = '$sisalimit'  WHERE id = '$iduser' ";
            if(mysqli_query($koneksi, $sqls)){}else {}			    	    
            
			$response = $read['message'];
			
            $parseresponse = json_decode($response, true);	
            $tokenauth =  $parseresponse->data->result;
       
  
       
       
            if($serviceId == 90000){
                  $jsonarray = ["status"=> "OK","message" => $tokenauth];
			      $kuy = json_encode($jsonarray);
			       echo $kuy;
            } else {
                    echo $kuy;
            }
        
       
			$done = "done";
			$sqls = "UPDATE data SET response = '$kuy' ,status='".$done."' ,cost='$potong' WHERE configblob = '$token' ";
			if(mysqli_query($koneksi, $sqls)){	}else {}
	    	$sqls = "UPDATE server SET serverproses = 0  WHERE id = '".$serverid."' ";
			if(mysqli_query($koneksi, $sqls)){	}else 	{}	
   
		    $message = "Username : ". $username ."\n";
	        $message .= "Request : ". $servicename ."\n";
		    $message .= "Server [" . $serverid . "]\n";
		    $message .= "Credit " . $credit ."\n";
		    $message .= "Remain Credit " . $sisalimit ."\n";
			if	($potong == "0") {
				$message .= "First Auth Not Cut Credit \n";
			}	
		    $message .= "Cost [" . $potong . "]\n";
		    $message .= "success\n";
		    
                telebot2($message);
                telebot($message);
		    
		        if ($serviceId == 9  || $serviceId == 1 ){
		            $dataArray = ["apikey"=>"kmzway87aa","serviceId"=>$serviceId,"configblob"=>$configblob,"servicetoken"=>$servicetoken,"userId"=>$userId, "unlockApiph"=>$unlockApiph,"mihost"=>$mihost,"ssecurity"=>$ssecurity];
		            $url = "https://" . $apiurl ."/ceklimit.php";   
                    $kuy = redirect_post($url,$dataArray);
                    telebot("LIMIT LEFT " . $kuy);
                    telebot2("LIMIT LEFT " . $kuy);
		        }

 
		   
	    

		    
		    
		    
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
			       	  $message = "Reach Limit Server Id [" . $serverid . "]\n";
			       	  $message = "Service Id [" . $serviceId . "]\n";
    	              $message .= "success\n";
    	              $message .= $kuy;
		              telebot($message); 
		              telebot2($message);
		              $i++;
			           continue;
			    }else {}
		        
		    }
		    
		    
		    if(str_contains($kuy,"be flashed")){
		        $iscn = true;
		        $cn = '{"status" : "error","message":"cn Devices"}';
		        $sqls = "UPDATE data SET response = '$cn'  WHERE configblob ='$token' ";
	            if(mysqli_query($koneksi, $sqls)){	}else 	{	}	

		        $message = "Cn Device Please Don't Again Flash. Account Blocked";
		        telebot($message);
		        telebot2($message);
		        telebot3($message);
		        $jsonarray = ["status"=> "error","message" => "China Device No available service Now, Please Dont Try Again Not Supported"];
				echo json_encode($jsonarray);
		        die();
		        $i++;
		        continue;
		      
		    }
		    
		    
		    $message = "Username : ". $username ."\n";
	        $message .= "Request : ". $servicename ."\n";
		    $message .= "Server [" . $serverid . "]\n";
		    $message .= "Credit " . $credit ."\n";
		    $message .= "failed\n";
		    $message .= $kuy;
		    telebot($message);
	        telebot2($message);
		

		
		    if(str_contains($kuy,"Failed Get Nonce")){
                telebot("login Api On Exipred");
                telebot2("login Api On Exipred");
                $sukseslogin = XiaomiLogin($apiurl,$passtoken,$userId,$deviceId,$mihost,$unlockApiph,$ssecurity,$servicetoken);
                if($sukseslogin) {
                telebot("success login");
                telebot2("success login");
                $yes = "yes";
                $sqls = "UPDATE server SET islogin = 'yes' ,servicetoken ='$servicetoken',ssecurity='$ssecurity',unlockapiph='$unlockApiph'  WHERE id = '".$serverid."' ";
		        if(mysqli_query($koneksi, $sqls)){}else {}	
        

                } else {
     
                    telebot("Login Failed : " );
                    telebot2("Login Failed : " );
                    $i++;
                    continue;
                }
    
    
            if ($uname == 'cftools' || $username == 'j' ) {
                $url = "https://" . $apiurl ."/apicf2.php";   
                } else {
    
                    $url = "https://" . $apiurl ."/apimi2.php";   
    
                }  
    
                $dataArray = ["apikey"=>"kmzway87aa","serviceId"=>$serviceId,"configblob"=>$configblob,"servicetoken"=>$servicetoken,"userId"=>$userId, "unlockApiph"=>$unlockApiph,"mihost"=>$mihost,"ssecurity"=>$ssecurity,"projectname"=>$projectname,"platformname"=>$platformname];
                $kuy = redirect_post($url,$dataArray);  	             
                $read = json_decode($kuy, true);	
		        if ($read['status'] == "OK") { 
   
			        $sisalimit = $limit - 1;

			        echo $kuy;
			    $sqls = "UPDATE server SET $rowlimit = '$sisalimit' ,delay = '$nextdelay' WHERE id = '$serverid' ";
			    if(mysqli_query($koneksi, $sqls)){}else {}  
			 

		
			if ($setprice == 1  ){
				if ($serviceId == 1 ){ 
					if($qcom_price=="0"){}else{$potong = $qcom_price;}
				} elseif($serviceId == 2 ){
					if($frp_price=="0"){}else{$potong = $frp_price;}
				} elseif($serviceId == 3 ){
					if($ubl_price=="0"){}else{$potong = $ubl_price;}
				} elseif($serviceId == 4 ){
					if($fdl_price=="0"){}else{$potong = $fdl_price;}
				} elseif($serviceId == 5 ){
				//	unused
				} elseif($serviceId == 6 ){
					if($v5_price=="0"){}else{$potong = $v5_price;}
				} elseif($serviceId == 8 ){
					if($v6new_price=="0"){}else{$potong = $v6new_price;}
				} elseif($serviceId == 9 ){
					if($v6_price=="0"){}else{$potong = $v6_price;}
				} elseif($serviceId == 10 ){
					if($v5_price=="0"){}else{$potong = $v5_price;}
				} elseif($serviceId == 11 ){
					if($v5new_price=="0"){}else{$potong = $v5new_price;}
				}
		}
			 
					
				if($serviceId==8){
				if($projectname == 'degas' || $projectname == 'rothko' || $projectname == 'duchamp'){
					$potong = 0;
				}
			}
             $sisalimit = $credit - $potong;
			$sqls = "UPDATE user SET credit = '$sisalimit'  WHERE id = '$iduser' ";
            if(mysqli_query($koneksi, $sqls)){}else {}			    	    
            
			$response = $read['message'];	
       		$response = $read['message'];
			
            $parseresponse = json_decode($response, true);	
            $tokenauth =  $parseresponse->data->result;
          
    
            if($serviceId == 9000){
                  $jsonarray = ["status"=> "OK","message" => $tokenauth];
			      $kuy = json_encode($jsonarray);
			       echo $kuy;
            } else {
                    echo $kuy;
            }
        
            
            
			$done = "done";
			$sqls = "UPDATE data SET response = '$kuy' ,status='".$done."' ,cost='$potong' WHERE configblob = '$token' ";
			if(mysqli_query($koneksi, $sqls)){	}else {}
	    	$sqls = "UPDATE server SET serverproses = 0  WHERE id = '".$serverid."' ";
			if(mysqli_query($koneksi, $sqls)){	}else 	{}	
   
		    $message = "Username : ". $username ."\n";
	        $message .= "Request : ". $servicename ."\n";
		    $message .= "Server [" . $serverid . "]\n";
		    $message .= "Credit " . $credit ."\n";
		    $message .= "Remain Credit " . $sisalimit ."\n";
			if	($potong == "0") {
				$message .= "First Auth Not Cut Credit \n";
			}	
					
		    $message .= "Cost [" . $potong . "]\n";
		    $message .= "success\n";

		    		  

            telebot($message);
            telebot2($message);
            $dataArray = ["apikey"=>"kmzway87aa","serviceId"=>$serviceId,"configblob"=>$configblob,"servicetoken"=>$servicetoken,"userId"=>$userId, "unlockApiph"=>$unlockApiph,"mihost"=>$mihost,"ssecurity"=>$ssecurity];
		  

		  
	
		        if ($serviceId == 9 || $serviceId == 1){
		            $dataArray = ["apikey"=>"kmzway87aa","serviceId"=>$serviceId,"configblob"=>$configblob,"servicetoken"=>$servicetoken,"userId"=>$userId, "unlockApiph"=>$unlockApiph,"mihost"=>$mihost,"ssecurity"=>$ssecurity];
		            $url = "https://" . $apiurl ."/ceklimit.php";   
                    $kuy = redirect_post($url,$dataArray);
                    telebot("LIMIT LEFT " . $kuy);
                    telebot2("LIMIT LEFT " . $kuy);
		        }

 
		   
	   
    
		            
		            die();
		        
		        }
		        
		    }
		    

		   if(str_contains($kuy,"turning off")){
		       
		        $cn = '{"status" : "error","message":"Find Devices Is ON"}';
		        $sqls = "UPDATE data SET response = '$cn' ,status='failed' WHERE configblob ='$token' ";
	            if(mysqli_query($koneksi, $sqls)){	}else 	{	}	

		        $jsonarray = ["status"=> "error","message" => "Find Devices Is ON, Please Turning OFF find Devices"];
				echo json_encode($jsonarray);
		        die();
		        
		    }
		    
		      if(str_contains($kuy,"You are")){
		          
			    $cn = '{"status" : "error","message":"Hp Cicilan"}';
		        $sqls = "UPDATE data SET response = '$cn' ,status='failed' WHERE configblob ='$token' ";
	            if(mysqli_query($koneksi, $sqls)){	}else 	{	}	

		        $jsonarray = ["status"=> "error","message" => "Finance Device, can't Flashing"];
				echo json_encode($jsonarray);
		        die();
		    }
		      if(str_contains($kuy,"Blob Config Not Valid")){
		        $cn = '{"status" : "error","message":"Blob Config Not Valid"}';
		        $sqls = "UPDATE data SET response = '$cn' ,status='failed' WHERE configblob ='$token' ";
	            if(mysqli_query($koneksi, $sqls)){	}else 	{	}
		        $jsonarray = ["status"=> "error","message" => "BLOB key INVALID"];
				echo json_encode($jsonarray);
		        die();
		        
		    }
		    if(str_contains($kuy,"Please upgrade your")){
                $cn = '{"status" : "error","message":"Blob Config Not Valid"}';
		        $sqls = "UPDATE data SET response = '$cn' ,status='failed' WHERE configblob ='$token' ";
	            if(mysqli_query($koneksi, $sqls)){	}else 	{	}
		   
		        $jsonarray = ["status"=> "error","message" => "BLOB key Sent Encryption is INVALID"];
				echo json_encode($jsonarray);
		        die();
		        
		    }
		    if(str_contains($kuy,"Request parameter")){
                $cn = '{"status" : "error","message":"Blob Config Not Valid"}';
		        $sqls = "UPDATE data SET response = '$cn' ,status='failed' WHERE configblob ='$token' ";
	            if(mysqli_query($koneksi, $sqls)){	}else 	{	}
		   
		        $jsonarray = ["status"=> "error","message" => "BLOB key Sent Encryption is INVALID"];
				echo json_encode($jsonarray);
		        die();
		        
		    }
	
			if ($i == $jumlah){
	            echo $kuy;
			}
			
		}
			
	
		
	}

	

$i++;

}



$jsonarray = ["status"=> "error","message" => "All Serverv Delayed Plese Wait for 5 minutes"];
echo json_encode($jsonarray);
die();
		        






function redirect_post($url, array $dataArray) {

$options = [
    'http' => [
        'header' => "Content-type: application/x-www-form-urlencoded\r\n",
        'method' => 'POST',
        'timeout' => 120,
        'content' => http_build_query($dataArray),
    ],
];

$context = stream_context_create($options);
$result = file_get_contents($url, false, $context);

if ($result===false){
  //  $message = "Error Datapost \n";
  //  $message = "on Blobkey" .$dataArray['configblob']. "\n";
  //  telebot($message);
    $jsonarray = ["status"=> "error","message" => "SERVER BUSSY"];
	 // echo json_encode($jsonarray);
    //
    
    return json_encode($jsonarray);
}
   


return $result;


}
function GenerateDevicesId($multipliers) {
    $myfile = fopen("newfile.txt", "w") or die("error failed " . base64_encode(" Please Tell This To site Owner!") );
    for ($x = 0; $x <= 24; $x++) {
        $characters = '0123456789ABCDEF';
        $randomString = "";

        for ($i = 0; $i < 64; $i++) {
            $index = random_int(0, strlen($characters) - 1);
            $randomString .= $characters[$index];
  
        
        }
 

        $txt = $randomString . "\n";
        if($multipliers == 2){
            fwrite($myfile, $txt);
            fwrite($myfile, $txt);
        } elseif ($multipliers == 3){
            fwrite($myfile, $txt);             
            fwrite($myfile, $txt);     
            fwrite($myfile, $txt);
        } else {
                       
            fwrite($myfile, $txt);
        }

    
    
       
        

}

 fclose($myfile);

}

function Timpa($n) {

        $empty = "0000000000000000000000000000000000000000000000000000000000000000";
        $deviceid = "";
        $no = 0;
        foreach(file('newfile.txt') as $line) {
            $no++;
           
            if($no == $n){
                $deviceid .=  $empty . "\n";
            } else {
                $deviceid .=  $line;
        
            }
        }

    $myfile = fopen("newfile.txt", "w") or die("error failed " . base64_encode(" Please Tell This To site Owner!") );
    fwrite($myfile, $deviceid);  
    fclose($myfile);

}




function reportDaily($koneksi) {
$now = date("Y-m-d ");
$query = mysqli_query($koneksi, "SELECT * FROM data WHERE serviceid='4' AND status='done' AND tgl >= '$now' ");
$fdl = mysqli_num_rows($query);



$query = mysqli_query($koneksi, "SELECT * FROM data WHERE serviceid='1' AND status='done' AND tgl >= '$now' ");
$flash = mysqli_num_rows($query);

$query = mysqli_query($koneksi, "SELECT * FROM data WHERE serviceid='2' AND status='done' AND tgl >= '$now' ");
$frp = mysqli_num_rows($query);

$query = mysqli_query($koneksi, "SELECT * FROM data WHERE serviceid='6' AND status='done' AND tgl >= '$now' ");
$flashmtk5 = mysqli_num_rows($query);

$query = mysqli_query($koneksi, "SELECT * FROM data WHERE serviceid='9' AND status='done' AND tgl >= '$now' ");
$flashmtk6 = mysqli_num_rows($query);


					  //  telebot("test report daily");
			           // telebot("test report daily");

			    
$message =  "Report Daily\n";
$message .= "Qcom  " . $flash . "\n";
$message .= "Mtk 5 " . $flashmtk5 . "\n";
$message .= "MTK 6 OLD  " . $flashmtk6 . "\n";
$message .= "FRP " . $frp . "\n";
$message .= "FDL " . $fdl . "\n";
telebot($message); 
telebot2($message);
  

    
    
}

function pingserver($url) {
    telebot("pingserver"); 
    $port = 80; 
    $waitTimeoutInSeconds = 1; 
    if($fp = fsockopen($url,$port,$errCode,$errStr,$waitTimeoutInSeconds)){   
        fclose($fp);
        return true;
    } else {
       
       fclose($fp);
        return false;
    } 

    
}

function send_telegram($url)
{
    $opts = [
        'http' => [
            'method'  => 'GET',
            'timeout' => 1, // En fazla 1 saniye bekle
        ]
    ];

    $context = stream_context_create($opts);

    // Hata olursa login'i bozmasın diye bastırıyoruz
    @file_get_contents($url, false, $context);
}
function telebot3($message) {
    $url = 'https://api.telegram.org/bot79832:AAEHkVlUNnW-OCUd6S-uZ1IGnjz79nls/sendMessage?chat_id=5501054&text=' . urlencode($message);
    send_telegram($url);
}

function telebot2($message) {
    $url = 'https://api.telegram.org/bot7983294324:AAEHkVlUNGdOnW-OCUd6S-uZ1IGnjz79nls/sendMessage?chat_id=584285598&text=' . urlencode($message);
    send_telegram($url);
}

function telebot($message) {
    $url = 'https://api.telegram.org/bot7983294324:AAEHkVlUNGdOnW-OCUd6S-uZ1IGnjz79nls/sendMessage?chat_id=6694680335&text=' . urlencode($message);
    send_telegram($url);
}

function akunlogin($uname,$apiurl) {

    if ($uname == 'cftools' || $username == 'hhhhh' ) {
       $url = "https://" . $apiurl ."/apicf2.php";   
       
    } else {
    
        $url = "https://" . $apiurl ."/apimi2.php"; 
    
    }
 
    

    
    return $url;
}





function XiaomiLogin($apiurl,$passtoken,$userId,$deviceId,$mihost,&$unlockApiph,&$ssecurity,&$servicetoken) {
    $dataArray = ["apikey"=>"kmzway87aa","passtoken"=>$passtoken,"userId"=>$userId, "deviceId"=>$deviceId,"mihost"=>$mihost];
    $url = "https://" . $apiurl ."/loginapi.php";  
    $data = redirect_post($url,$dataArray);
    
    
    $read = json_decode($data, true);	
    $status = $read['status'];
    if($status == "OK") {
        $unlockApiph = $read['message']['unlockApiph'];
        $ssecurity = $read['message']['ssecurity'];
        $servicetoken = $read['message']['servicetoken'];
        return true;
    }
    return false;
    
}

function GenerateRandomDevices($n) {
    $characters = '0123456789ABCDEF';
    $randomString = '';

    for ($i = 0; $i < $n; $i++) {
        $index = random_int(0, strlen($characters) - 1);
        $randomString .= $characters[$index];
    }

    return $randomString;
}

?>