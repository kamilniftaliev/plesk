<?php

date_default_timezone_set('asia/jakarta');
$now = date("Y-m-d ");
$db = getDbInstance();
$numuser = $db->getValue("user", "count(*)");
$db->where("status", "authid");

$numTotalauthid = $db->getValue("user", "count(*)");
$name = $_SESSION['name'];


$db = getDbInstance();
$db->where("username", $name);
$row = $db->get('user');

//$data = mysqli_query($koneksi, "SELECT * FROM data WHERE DATE(tgl) = CURDATE() ");
$where = "status='done' AND tgl >= '$now' AND  serviceid='6'  ";
$db = getDbInstance();
$db->where($where);
$jumlahflashtoday = $db->getValue("data", "count(*)");

$where = "status='done' AND tgl >= '$now' AND  serviceid='1'  ";
$db = getDbInstance();
$db->where($where);
$jumlahflashmtk = $db->getValue("data", "count(*)");
$jumlahflashtoday += $jumlahflashmtk;

$where = "serviceid='2' AND status='done' AND tgl >= '$now' ";
$db = getDbInstance();
$db->where($where);
$jumlahfrptoday = $db->getValue("data", "count(*)");

$where = "serviceid='4' AND status='done' AND tgl >= '$now' ";
$db = getDbInstance();
$db->where($where);
$jumlahFDLtoday = $db->getValue("data", "count(*)");

?>