<?php
include 'anti.php';
include '/includes/konak.php';



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
		echo $credit;

		die();

	}

} else {
	echo "Failed Username/Email Un registered";
	die();

}

echo "You Dont Send Anything";


function telebot($message)
{
	$token = "7694430395:AAF3dNoD07QIcUqony9ti_CPz7i62w32qAE";
	$chatid = "-1002383474691";
	$url = 'https://api.telegram.org/bot7694430395:AAF3dNoD07QIcUqony9ti_CPz7i62w32qAE/sendMessage?chat_id=-1002383474691&text=' . urlencode($message);
	$result = file_get_contents($url, false, $context);
	if ($result === false) {
		/* Handle error */
	}
	return $result;
}
?>