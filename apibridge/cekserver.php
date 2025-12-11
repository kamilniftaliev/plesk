<?php

include "/includes/konak.php";
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
$limitcek = "";

if ($serversupport == "frp") {
    $limitcek = 'limitleftfrp';
}

if ($serversupport == "fdl") {
    $limitcek = 'limitleftfdl';
}

if ($serversupport == "flash") {
    $limitcek = 'limitleftedl';
}


$sekarang = date('Y-m-d H:i:s');
$data = mysqli_query($koneksi, "SELECT * FROM server WHERE status = 'ON' and delay <= '" . $sekarang . "' and serversupport LIKE '%" . $serversupport . "%' and $limitcek > 0 ");
$jumlah = mysqli_num_rows($data);

if ($jumlah > 0) {

    echo 1;
} else {
    echo 0;

}






