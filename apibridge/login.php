<?php
include 'anti.php';
include '/includes/konak.php';
$ipuser = $_SERVER['REMOTE_ADDR'];


if (!isset($_POST['username'])) {
	echo "you dont send username";
	die();

}

$username = anti_injection($_POST['username']);
if ($username == "") {

	echo "Empty Password";
	die();

}


if (!isset($_POST['password'])) {
	echo "you dont send Password";
	die();

}

$password = anti_injection($_POST['password']);
if ($password == "") {

	echo "Empty Password";
	die();

}



if (strpos($username, '@') == true) {
	$data = mysqli_query($koneksi, "SELECT * FROM user WHERE email='" . $username . "' ");


} else {

	$data = mysqli_query($koneksi, "SELECT * FROM user WHERE username='" . $username . "' ");

}

$cek = mysqli_num_rows($data);
if ($cek > 0) {
	$user = mysqli_fetch_array($data);
	$hashpassword = $user['password'];
	if (password_verify($password, $hashpassword)) {
		$credit = $user['credit'];

		if ($credit > 0) {
			$message = $username . " LOGED IN\n";
			$message .= "IP " . $ipuser;
			telebot($message);
			telebot2($message);
			echo "sukses" . $credit;
			die();

		} else {
			telebot($username . " Failed Login Credit 0" . "\nIP user " . $ipuser);
			telebot2($username . " Failed Login Credit 0" . "\nIP user " . $ipuser);

			echo "Failed Credit Must more than 0 to Activate";
			die();

		}


	} else {
		telebot($username . " Wrong Password\n" . "IP user " . $ipuser);
		telebot2($username . " Wrong Password\n" . "IP user " . $ipuser);
		echo "Failed Wrong Password";
		die();
	}

} else {
	echo "Failed Username/Email Un registered";
	die();

}

echo "Failed You Dont Send Anything";

function send_telegram($url)
{
	$opts = [
		'http' => [
			'method' => 'GET',
			'timeout' => 1, // En fazla 2 saniye bekle
		]
	];

	$context = stream_context_create($opts);

	// Hata olursa login'i bozmasın diye bastırıyoruz
	@file_get_contents($url, false, $context);
}
function telebot2($message)
{
	$url = 'https://api.telegram.org/bot7983294324:AAEHkVlUNGdOnW-OCUd6S-uZ1IGnjz79nls/sendMessage?chat_id=584285598&text=' . urlencode($message);
	send_telegram($url);
}

function telebot($message)
{
	$url = 'https://api.telegram.org/bot7983294324:AAEHkVlUNGdOnW-OCUd6S-uZ1IGnjz79nls/sendMessage?chat_id=6694680335&text=' . urlencode($message);
	send_telegram($url);
}



?>