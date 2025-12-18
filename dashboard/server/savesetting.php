<?php

require_once '/includes/konak.php';
session_name('DASHBOARD_SESSION');
session_start();

// sid: sid,
//    flash: flash,
//   frp: frp,
//  fdl: fdl,

$typeadmin = getCurrentUserType();
if (!$typeadmin == "super") {
  echo "<h1><center>You Are Not Admin Super</h1><center>";
  die();
}
if (!isset($_POST['sid'])) {
  echo "Error Not post Server id";
  die();
}

if (!isset($_POST['flash'])) {
  echo "Error Not post Flash Value";
  die();
}
if (!isset($_POST['frp'])) {
  echo "Error Not post frp value";
  die();
}


if (!isset($_POST['fdl'])) {
  echo "Error Not post fdl value";
  die();
}


$sid = antiInject($_POST['sid']);
$frp = antiInject($_POST['frp']);
$flash = antiInject($_POST['flash']);
$fdl = antiInject($_POST['fdl']);
$status = antiInject($_POST['status']);


$sqls = "UPDATE status SET limitsetflash = '$flash' , limitsetfrp = '$frp' , limitsetfdl = '$fdl' , state = '$status'  WHERE id = '" . $sid . "' ";
if (mysqli_query($koneksi, $sqls)) {

  echo "Success Update Limit setting in Server ID : " . $sid;

} else {
  echo "Failed Update Limit setting On Server ID : " . $sid;
}








function antiInject($data)
{
  $filter = stripslashes(strip_tags(htmlspecialchars($data, ENT_QUOTES)));

  return $filter;

}

