<?php


if(!isset(($_POST['imei1']))){ 
$jsonarray = ["status"=> "error","message" => "imei 1 can't be empty"];
echo json_encode($jsonarray);
return;

}
if(!isset(($_POST['imei2']))){ 
$jsonarray = ["status"=> "error","message" => "imei 2 can't be empty"];
echo json_encode($jsonarray);
return;

}
//die();

$imeistring1 = $_POST['imei1'];
$imeistring2 = $_POST['imei2'];




if(strlen($imeistring1) < 15){
    $jsonarray = ["status"=> "error","message" => "imei 1 less than 15 digit"];
    echo json_encode($jsonarray);
return; 
    
}

if(strlen($imeistring2) < 15){
    $jsonarray = ["status"=> "error","message" => "imei 2 less than 15 digit"];
    echo json_encode($jsonarray);
    return; 
    
}
if(strlen($imeistring1) > 15){
    $imeistring1 = substr($imeistring1,0,15);
    
}

if(strlen($imeistring2) > 15){
    $imeistring2 = substr($imeistring2,0,15);
    
}
$imeistring1 = $imeistring1 .'fffff';
$imeistring2 = $imeistring2  .'fffff';

$imei1 = ImeiEncDec($imeistring1);
$imei2 = ImeiEncDec($imeistring2);
$jsonarray = ["imei1"=> $imei1,"imei2" =>  $imei2];
echo json_encode($jsonarray);


function  ImeiEncDec($data) {
	
$swap = SwapMtkNv($data);
$swapbin = hex2bin($swap);
$md5Hash = md5($swapbin);
$md5Hashbin = hex2bin($md5Hash );


//echo $swap ."<br>";
//echo $md5Hash."<br>";
for ($i = 0; $i < 8; $i++)
{
$csum[$i] = $md5Hashbin[$i] ^ $md5Hashbin[$i + 8];
}

$bin = join($csum);
$csumhex = bin2hex($bin);
$imeihex = $swap . $csumhex ."000000000000000000000000";

$yyy = encrptor($imeihex);
return bin2hex($yyy);
}


function SwapMtkNv($data) {
 $tmp = "";
  
$h = 0;
    for ($i = 0; $i < 10; $i++) {
	//	substr(string,start,length)
		
        $num = substr($data,$h,2);
       
        $tmp .= ABCDEF($num );
		$h += 2;
		$tmp = $tmp;
		
		
    }

   
    return $tmp;
}

function ABCDEF($x) {
   $numa = substr($x,0,1);
   $numb = substr($x,1,1);
   return  $numb . $numa;
}


function encrptor($bytecipherText) {
//$cipherText = byte2String($bytecipherText);
	
	$key = hex2bin( '3f06bd14d45fa985dd027410f0214d22');
	$paddedData = padZero(hex2bin($bytecipherText));
	
	
    try {
        $algo = openssl_cipher_iv_length('AES-128-ECB');

        $options = OPENSSL_RAW_DATA | OPENSSL_ZERO_PADDING;

		$rawData = openssl_encrypt($paddedData, 'AES-128-ECB', $key, OPENSSL_RAW_DATA  | OPENSSL_ZERO_PADDING);
		

        return $rawData;
    } catch (Exception $ex) {
        echo $ex->getMessage();
    }
}
function padZero($data, $blocksize = 16)
{
    $pad = $blocksize - (strlen($data) % $blocksize);
    return $data . str_repeat("\0", $pad);
}