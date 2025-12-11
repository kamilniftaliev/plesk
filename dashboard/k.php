<?php
session_name('DASHBOARD_SESSION');
session_start();
include '/includes/konak.php';
require_once '../config/config.php';
require_once '../includes/auth_validate.php';





$pagelimit = 50;

$resllerid = 1;
$page = 1;


$db = getDbInstance();
$select = array('resellerid', 'jumlah');

$total = 0;

$db->where('resellerid', 1);
$db->pageLimit = $pagelimit;

$rows = $db->arraybuilder()->paginate('penjualancredit', $page, $select);

foreach ($rows as $row):
  $total += $row['jumlah'];
endforeach;
echo $total;
