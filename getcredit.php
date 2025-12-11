<?php
include 'anti.php';
include '/includes/konak.php';







if (!isset($_POST['otp'])) {
	echo "you dont send otp";
	die();

}

$otp = anti_injection($_POST['otp']);
if ($otp == "") {

	echo "Zero Otp";
	die();

}

$data = mysqli_query($koneksi, "SELECT * FROM temp WHERE otp = '$otp' ");
$cek = mysqli_num_rows($data);
$user = mysqli_fetch_array($data);
if ($cek > 0) {

	echo "sisa credit : " . $user['credit'];

} else {
	echo "jancok";
}



?>