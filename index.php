<?php


include("/includes/konak.php");


$jumlahFRP = GetServerOn($koneksi, "frp");
$jumlahFDL = GetServerOn($koneksi, "fdl");
$jumlahFlash = GetServerOn($koneksi, "flash");
$jumlahUBL = GetServerOn($koneksi, "ubl");




function GetServerOn($koneksi, $jenisauth)
{
    $ceklimit = "";
    if ($jenisauth == "frp") {
        $ceklimit = "limitleftfrp";
    }
    if ($jenisauth == "fdl") {
        $ceklimit = "limitleftfdl";
    }
    if ($jenisauth == "flash") {
        $ceklimit = "limitleftedl";
    }
    if ($jenisauth == "ubl") {
        $ceklimit = "limitleftubl";
    }


    $jumlahtotal = 0;
    $data = mysqli_query($koneksi, "SELECT * FROM server WHERE status = 'ON' and serversupport LIKE '%" . $jenisauth . "%' ");
    $jumlah = mysqli_num_rows($data);
    while ($row = mysqli_fetch_array($data)) {
        $user = mysqli_fetch_array($data);
        $jumlah = $row[$ceklimit];
        $jumlahtotal = $jumlahtotal + $jumlah;


    }


    return $jumlahtotal;

}
?>
<center>
    <h2>OUR LIMIT STATUS <br></h2>
    <h2>FRP <?php if ($jumlahFRP > 0) {
        echo "ONLINE ";
    } else {
        echo "OFFLINE";
    } ?> <br></h2>
    <h2>FDL <?php if ($jumlahFDL > 0) {
        echo "ONLINE ";
    } else {
        echo "OFFLINE";
    } ?> <br></h2>
    <h2>MTK v6 <?php if ($jumlahFlash > 0) {
        echo "ONLINE ";
    } else {
        echo "OFFLINE";
    } ?> <br></h2>
    <h2>UBL <?php if ($jumlahUBL > 0) {
        echo "ONLINE ";
    } else {
        echo "OFFLINE";
    } ?> <br></h2>

</center>