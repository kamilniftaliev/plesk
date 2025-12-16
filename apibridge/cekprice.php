<?php


include "../includes/konak.php";
$sekarang = date('Y-m-d H:i:s');
date_default_timezone_set('asia/jakarta');


$serversupport = "";

if (!isset($_POST['cek'])) {

    echo "cek server support not post";
    die();
}
$serversupport = $_POST['cek'];
if ($serversupport == "") {
    echo "empty cek server support";
    die();
}
$service = 0;

if ($serversupport == "2") {
    $service = '2';
}

if ($serversupport == "4") {
    $service = 4;
}

if ($serversupport == " 1") {
    $service = 1;
}



if ($serversupport == "6") {
    $service = 6;
}

if ($serversupport == "9") {
    $service = 9;
}

$sekarang = date('Y-m-d H:i:s');
$data = mysqli_query($koneksi, "SELECT * FROM price  WHERE serviceid = '$service' ");
$jumlah = mysqli_fetch_array($data);
$harga = $jumlah['harga'];
echo $harga;


