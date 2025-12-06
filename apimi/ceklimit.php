<?php
include 'anti.php';
date_default_timezone_set('asia/jakarta');
$sekarang = date('Y-m-d H:i:s');

$ivkey = "0102030405060708";



$serviceId = $_POST['serviceId'];
$configblob = $_POST['configblob'];
$servicetoken = $_POST['servicetoken'];
$userId = $_POST['userId'];
$unlockApi_ph = $_POST['unlockApiph'];
$mihost ="https://". $_POST['mihost'];
$ssecurity = $_POST['ssecurity'];


$yyh = SendNoncev2($mihost ,$nonce,$ivkey,$servicetoken,$userId,$unlockApi_ph,$ssecurity);


if (!$yyh) {

die();
}
		

Datapostv2($mihost,$nonce,$ivkey,$servicetoken,$userId,$unlockApi_ph,$ssecurity);		


die();

function SendNoncev2($HostApi,&$returnNonce,$ivkey,$servicetoken,$userId,$unlockApi_ph,$ssecurity ) {
$skey = base64_decode($ssecurity);
	



$rasli = generateRandomString(16, true);
$ture = bin2hex($rasli);
$adval = $ture . "10101010101010101010101010101010";
$massse = hex2Byte($adval);
$uy = base64_encode(encrptor($massse ,$skey,$ivkey));

$sid = "miui_unlocktool_client";
$ture = bin2hex($sid);
$yrt = $ture . "0a0a0a0a0a0a0a0a0a0a";
$massse = hex2Byte($yrt);
$uyk = base64_encode(encrptor($massse ,$skey,$ivkey));

$urlnyasig = "POST\n/api/v2/nonce\nr=" . $rasli . "&sid=miui_unlocktool_client";
$hhhh = getsha1($urlnyasig);
$ture = bin2hex($hhhh);
$yrt = $ture . "0808080808080808";
$massse = hex2Byte($yrt);
$aeskeysigenc = base64_encode(encrptor($massse ,$skey,$ivkey));



	

$urlnyasignature =  "POST&/api/v2/nonce&" . "r=" . $uy . "&sid=" . $uyk . "&sign=" . $aeskeysigenc . "&" . $ssecurity;
$signaturenya = getSHA1Hash($urlnyasignature);


$encodesignatrue = base64_encode(byte2String( hex2Byte($signaturenya)));
$url1 = $HostApi . "/api/v2/nonce";
$cookie = "serviceToken=".$servicetoken.";userId=".$userId.";unlockApi_slh=null;unlockApi_ph=".$unlockApi_ph;



$data = "r=".urlencode($uy). "&sid=" . urlencode($uyk) . "&sign=" . urlencode($aeskeysigenc) . "&signature=" . urlencode($encodesignatrue);


$context = stream_context_create([
    "http" => [
        "method" => "POST",
		"content" =>  $data,
        "header" => "Content-Type: application/x-www-form-urlencoded\r\n" .
		"User-Agent: XiaomiPCSuite\r\n" .
        "Cookie: " . $cookie
    ]
]);

$responseFromServer = file_get_contents($url1, false, $context);



$ggg = $http_response_header;	

$responseheader = $ggg[0];



 $responseFromServer = str_replace("&&&START&&&", "", $responseFromServer);
	$kkkkkkkkk = Decryptor(base64_decode($responseFromServer),$skey ,$ivkey);
	$hhhhh = base64_decode( substr($kkkkkkkkk, 0,strlen($kkkkkkkkk) - 8 ));

	$read = json_decode($hhhhh, true);
	
    if (!isset($read['nonce'])) {
		$jsonarray = ["status"=> "error","message" => "Failed Get Nonce"];
		echo json_encode($jsonarray);
        return false;
    }

$returnNonce = $read['nonce'];
return true;	

	
}
function Datapostv2($HostApi,$retnonce,$ivkey,$servicetoken,$userid,$unlockApi_ph,$ssecurity ) {
    
$nonce = $retnonce;

$skey = base64_decode($ssecurity);





$sidss = "miui_unlocktool_client";
$ture = bin2hex($sidss);
$yrt = $ture . "0a0a0a0a0a0a0a0a0a0a";
$massse = hex2Byte($yrt);
$encSids = base64_encode(encrptor($massse ,$skey,$ivkey));


$ops = "flash";
$ture = bin2hex($ops);
$yrt = $ture . "0b0b0b0b0b0b0b0b0b0b0b";
$massse = hex2Byte($yrt);
$EncData = base64_encode(encrptor($massse ,$skey,$ivkey));



$ture = bin2hex($nonce);
$yrt = $ture . "0808080808080808" ;
$massse = hex2Byte($yrt);
$encNonces = base64_encode(encrptor($massse ,$skey,$ivkey));


$url = "/api/v1/flashBoxTool/permission";
$urlnyasig = "POST\n".$url."\ndata=" . $ops . "&nonce=" . $nonce . "&sid=miui_unlocktool_client" ;
$hhhh = getsha1($urlnyasig);
$ture = bin2hex($hhhh);
$yrt = $ture . "0808080808080808";
$massse = hex2Byte($yrt);
$aeskeysigenc = base64_encode(encrptor($massse ,$skey,$ivkey));




$urlnyasignaturer = "POST&" .$url. "&data=" . $EncData . "&nonce=" . $encNonces . "&sid=" . $encSids . "&sign=" . $aeskeysigenc . "&" . $ssecurity;
$signaturenya = getSHA1Hash($urlnyasignaturer);

$encodesignatrue = base64_encode(byte2String(hex2Byte($signaturenya)));



$sendpostnya = "sid=" . urlencode($encSids) . "&data=" . urlencode($EncData) . "&nonce=" . urlencode($encNonces) . "&sign=" . urlencode($aeskeysigenc) . "&signature=" . urlencode($encodesignatrue);


$cookie= 'serviceToken='.$servicetoken.'; userId='.$userid.'; unlockApi_slh=null; unlockApi_ph='.$unlockApi_ph;
	
$url1 = $HostApi  . $url;
$context = stream_context_create([
    "http" => [
        "method" => "POST",
		"content" =>  $sendpostnya,
        "header" => "Content-Type: application/x-www-form-urlencoded\r\n" .
		"User-Agent: XiaomiPCSuite\r\n" .
        "Cookie: " . $cookie
    ]
]);

$responseFromServer = file_get_contents($url1, false, $context);

$ggg = $http_response_header;	
$responseheader = $ggg[0];


   

$responseFromServer = str_replace("&&&START&&&", "", $responseFromServer);

$kkkkkkkkk = Decryptor(base64_decode($responseFromServer),$skey ,$ivkey);
$hhhhh = base64_decode(($kkkkkkkkk));

$read = json_decode($hhhhh, true);	
if (!isset($read['code'])) {
    $jsonarray = ["status"=> "error","message" => $hhhhh];
    echo json_encode($jsonarray);
    return false; 
}
	

 $hh = $read['SG'];

    echo($hh[0]['remainingDailyLimit']);


}
function byte2String($byteArray) {
  $chars = array_map("chr", $byteArray);
  return join($chars);
}
function Decryptor($bytecipherText, $key, $iv) {
$cipherText = $bytecipherText;
	
	
	
    try {
        $algo = openssl_cipher_iv_length('aes-128-cbc');

        $options = OPENSSL_RAW_DATA | OPENSSL_ZERO_PADDING;

        $buffout = openssl_decrypt($cipherText, 'aes-128-cbc', $key, $options, $iv);

        return $buffout;
    } catch (Exception $ex) {
        echo $ex->getMessage();
    }
}
function getSHA1Hash($input) {
    return hash('sha1', $input);
}
function hex2Byte($hexString) {
  $string = hex2bin($hexString);
  return unpack('C*', $string);
}
function BytesToHex($bytesdata) {
    return bin2hex($bytesdata);
}
function encodeURL($url) {
    return urlencode($url);
}
function string2ByteArray($string) {
  return unpack('C*', $string);
}
function encrptor($bytecipherText, $key, $iv) {
$cipherText = byte2String($bytecipherText);
	
	
	
    try {
        $algo = openssl_cipher_iv_length('aes-128-cbc');

        $options = OPENSSL_RAW_DATA | OPENSSL_ZERO_PADDING;

        $buffout = openssl_encrypt($cipherText, 'aes-128-cbc', $key, $options, $iv);

        return $buffout;
    } catch (Exception $ex) {
        echo $ex->getMessage();
    }
}
function generateRandomString($len, $lower) {
    $allowableChars = str_split("abcdef1234567890");
    $final = "";
    for ($i = 0; $i < $len; $i++) {
        $final .= $allowableChars[random_int(0, count($allowableChars) - 1)];
    }

    return $lower ? strtolower($final) : $final;
}
function getsha1($content) {
    $defaultkey = hex2Byte("327442656f45794a54756e6d57554771376251483241626e306b324e686875724f61714266797843754c56676e3441566a3773776361776535337544556e6f");
	$kkkk = byte2String($defaultkey);
    $myHMACSHA1 = hash_hmac('sha1', $content, $kkkk, true);


    return BytesToHex( $myHMACSHA1);
}
function redirect_post($url, array $data,$servicetoken,$userid,$unlockApi_ph) {
//curl_setopt($request, CURLOPT_COOKIE, 'serviceToken='.$servicetoken.';userId='.$userid.';unlockApi_slh=null;unlockApi_ph='.$unlockApi_ph.";");



$options = [
    'http' => [
        'header' => "Content-type: application/x-www-form-urlencoded"
					,"Cookie: serviceToken=".$servicetoken.";userId=".$userid.";unlockApi_slh=null;unlockApi_ph=".$unlockApi_ph,
        'method' => 'POST',
        'content' => http_build_query($data),
    ],
];

$context = stream_context_create($options);
$result = file_get_contents($url, false, $context);

echo $result;
}
function penambahanHexBytes($inputhex) {
    $len = strlen($inputhex) / 2;
    $maxnya = $len / 16;
    $maxCount = ceil($maxnya);
    $kurangnya = ($maxCount * 16) - $len;


    if ($kurangnya == 0) {
        return $inputhex . "10101010101010101010101010101010";
    } elseif ($kurangnya == 1) {
        return $inputhex . "01";
    } elseif ($kurangnya == 2) {
        return $inputhex . "0202";
    } elseif ($kurangnya == 3) {
        return $inputhex . "030303";
    } elseif ($kurangnya == 4) {
        return $inputhex . "04040404";
    } elseif ($kurangnya == 5) {
        return $inputhex . "0505050505";
    } elseif ($kurangnya == 6) {
        return $inputhex . "060606060606";
    } elseif ($kurangnya == 7) {
        return $inputhex . "07070707070707";
    } elseif ($kurangnya == 8) {
        return $inputhex . "0808080808080808";
    } elseif ($kurangnya == 9) {
        return $inputhex . "090909090909090909";
    } elseif ($kurangnya == 10) {
        return $inputhex . "0a0a0a0a0a0a0a0a0a0a";
    } elseif ($kurangnya == 11) {
        return $inputhex . "0b0b0b0b0b0b0b0b0b0b0b";
    } elseif ($kurangnya == 12) {
        return $inputhex . "0c0c0c0c0c0c0c0c0c0c0c0c";
    } elseif ($kurangnya == 13) {
        return $inputhex . "0d0d0d0d0d0d0d0d0d0d0d0d0d";
    } elseif ($kurangnya == 14) {
        return $inputhex . "0e0e0e0e0e0e0e0e0e0e0e0e0e0e";
    } elseif ($kurangnya == 15) {
        return $inputhex . "0f0f0f0f0f0f0f0f0f0f0f0f0f0f0f";
    } elseif ($kurangnya == 16) {
        return $inputhex . "10101010101010101010101010101010";
    }

    return $inputhex;
}
function penghilangsisahex($contenthexstring) {
    $twolastbyte = substr($contenthexstring, -4, 4);

    if ($twolastbyte === "0202") {
        return substr($contenthexstring, 0, -4);
    } elseif ($twolastbyte === "0303") {
        return substr($contenthexstring, 0, -6);
    } elseif ($twolastbyte === "0404") {
        return substr($contenthexstring, 0, -8);
    } elseif ($twolastbyte === "0505") {
        return substr($contenthexstring, 0, -10);
    } elseif ($twolastbyte === "0606") {
        return substr($contenthexstring, 0, -12);
    } elseif ($twolastbyte === "0707") {
        return substr($contenthexstring, 0, -14);
    } elseif ($twolastbyte === "0808") {
        return substr($contenthexstring, 0, -16);
    } elseif ($twolastbyte === "0909") {
        return substr($contenthexstring, 0, -18);
    } elseif ($twolastbyte === "0a0a") {
        return substr($contenthexstring, 0, -20);
    } elseif ($twolastbyte === "0b0b") {
        return substr($contenthexstring, 0, -22);
    } elseif ($twolastbyte === "0c0c") {
        return substr($contenthexstring, 0, -24);
    } elseif ($twolastbyte === "0d0d") {
        return substr($contenthexstring, 0, -26);
    } elseif ($twolastbyte === "0e0e") {
        return substr($contenthexstring, 0, -28);
    } elseif ($twolastbyte === "0f0f") {
        return substr($contenthexstring, 0, -30);
    } elseif ($twolastbyte === "1010") {
        return substr($contenthexstring, 0, -32);
    } else {
        return $contenthexstring;
    }
}
function newSign($inputhex) {
   $defaultKey = "2tBeoEyJTunmWUGq7bQH2Abn0k2NhhurOaqBfyxCuLVgn4AVj7swcawe53uDUno";
    
    $bytes = utf8_encode($inputhex);
    
    $hmac = hash_hmac('sha1', $bytes, $defaultKey, true);
    
    return bin2hex($hmac);
}
function calculateHmacSha256($inputhex) {
    $defaultKey = utf8_encode("B288B376D22C73E6BE8EA5AF36E5275E6FA67EDADF8B18F2E5D88ACA9A92E4A1");
    $bytes = utf8_encode($inputhex);
    
    $hmac = hash_hmac('sha256', $bytes, $defaultKey, true);
    
    return bin2hex($hmac);
}



function telebot($message) {
$token = "7694430395:AAF3dNoD07QIcUqony9ti_CPz7i62w32qAE";
$chatid = "-1002383474691";
$url = 'https://api.telegram.org/bot7694430395:AAF3dNoD07QIcUqony9ti_CPz7i62w32qAE/sendMessage?chat_id=-1002383474691&text='.urlencode($message)  ;
$result = file_get_contents($url, false, $context);
if ($result === false) {
    /* Handle error */
}
return $result;
}

function postdata($url, array $dataArray) {

$options = [
    'http' => [
        'header' => "Content-type: application/x-www-form-urlencoded\r\n",
        'method' => 'POST',
        'content' => http_build_query($dataArray),
    ],
];

$context = stream_context_create($options);
$result = file_get_contents($url, false, $context);

return $result;
}

?>