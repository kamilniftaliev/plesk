<?php
function decryptProtectedKey($encryptedKey, $privateKeyPath) {
    $privateKey = file_get_contents($privateKeyPath);
	
    $res = openssl_get_privatekey($privateKey);
    if (!$res) {
        die("Gagal membaca private key!");
    }

    $decodedKey = base64_decode($encryptedKey);
    $decryptedKey = "";
    
    $success = openssl_private_decrypt($decodedKey, $decryptedKey, $res);
    
    if (!$success) {
        die("Gagal mendekripsi protected Key!");
    }

    return $decryptedKey;
}

$protectedKey = "TSaghQiuYQY6PCExIZc9SzQ8oZ0QTlxzyY/3gOixBBnY4zzNr4OF9CW3RbDc9ObzM0DABfiRapdIFcs6Lg+WwQXyhw2K3QnNulJifoY8Pcna46EfxHjxXkIbVBV+N5SV9T9SGekVMyjZsjP+2BlUqMsQ7Vf8Lnz4UZpeYO5RB97tnj6V2ytXjPmPhl7S0JoIHyAvI657FGVRHWEBK5TPGmMntolLt2SkjQj4SOavej9jSXOMZtnGOyIeg58DZ+flk2RgEKOZRmnaeNCmK3RPAIyjeTjCVXfxoR7XZeff9Tnhr4lSPm4c6hXVvmqp3xYst4mFKc8+J5iq8DULmtPnIw==";

$privateKeyPath = "private_2048.pem";

$secretKey = decryptProtectedKey($protectedKey, $privateKeyPath);

function decryptData($cipherText, $iv, $secretKey) {
	
	$iv = base64_decode($iv);
	
	if (strlen($iv) < 16) {
		$iv = str_pad($iv, 16, "\0");
	}
		
    return openssl_decrypt(
        base64_decode($cipherText),
        'AES-256-CBC',
        $secretKey,
        OPENSSL_RAW_DATA,
        $iv
    );
}

$jsonData = '{
    "account": {"cipher":"fimdleYOmXTjlIOAEoakzaVQHyOpmYvbWg==","iv":"8zKYYifXxVOQl02E"},
    "password": {"cipher":"WC2IlvgMmHHvtYPcOT+0p8IIqht4+37KQg==","iv":"8zKYYifXxVOQl02E"},
    "mac": {"cipher":"J2/VzOYT7nH3oKhCzu+bL6lp0ukBzi6ykmoDB4tFMA+H","iv":"8zKYYifXxVOQl02E"}
}';

$data = json_decode($jsonData, true);

$account = decryptData($data['account']['cipher'], $data['account']['iv'], $secretKey);
$password = decryptData($data['password']['cipher'], $data['password']['iv'], $secretKey);
$mac = decryptData($data['mac']['cipher'], $data['mac']['iv'], $secretKey);

echo "Account: " . $account . PHP_EOL;
echo "Password: " . $password . PHP_EOL;
echo "MAC: " . $mac . PHP_EOL;
?>
