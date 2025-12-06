<?php

//$hexld0b = "4C44490010EF0A000A0000000A4000000020000000000000000000000000A244000000000000000000000000010000000000000000000000000000000000000064D54435015841959C7F6EB0C2A64F55B1D6018E15EE80FEDB11C94531179B844CDA8CDEF633331D7D6C5B5EF8E858DAEE2213F8B8BD2DD5186FB893526CD5954AB96D2F1A7925C700DB7E7F217756793F086BADD56D9FA38EBF8856D4AEA0A74AB96D2F1A7925C700DB7E7F217756793F086BADD56D9FA38EBF8856D4AEA0A74AB96D2F1A7925C700DB7E7F217756793F086BADD56D9FA38EBF8856D4AEA0A74AB96D2F1A7925C700DB7E7F217756793F086BADD56D9FA38EBF8856D4AEA0A74AB96D2F1A7925C700DB7E7F217756793F086BADD56D9FA38EBF8856D4AEA0A74AB96D2F1A7925C700DB7E7F217756793F086BADD56D9FA38EBF8856D4AEA0A74AB96D2F1A7925C700DB7E7F217756793F086BADD56D9FA38EBF8856D4AEA0A74AB96D2F1A7925C700DB7E7F217756793F086BADD56D9FA38EBF8856D4AEA0A7";
if(!isset(($_POST['imei1']))){ 
$jsonarray = ["status"=> "error","message" => "Password Not Intitialized"];
echo json_encode($jsonarray);
return;

}
if(!isset(($_POST['imei2']))){ 
$jsonarray = ["status"=> "error","message" => "Password Not Intitialized"];
echo json_encode($jsonarray);
return;

}


$imeistring1 = $_POST['imei1'] .'fffff';
$imeistring2 = $_POST['imei2'] .'fffff';

$imei1 = ImeiEncDec($imeistring1);
$imei2 = ImeiEncDec($imeistring2);




function  ImeiEncDec($data) {
	
$swap = SwapMtkNv($data);
$swapbin = hex2bin($swap);
$md5Hash = md5($swapbin);
$md5Hashbin = hex2bin($md5Hash );


echo $swap ."<br>";
echo $md5Hash."<br>";
for ($i = 0; $i < 8; $i++)
{
$csum[$i] = $md5Hashbin[$i] ^ $md5Hashbin[$i + 8];
}

$bin = join($csum);
$csumhex = bin2hex($bin);
$imeihex = $swap . $csumhex ."000000000000000000000000";
echo $imeihex .'<br>';
$yyy = encrptor($imeihex);
echo bin2hex($yyy);

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