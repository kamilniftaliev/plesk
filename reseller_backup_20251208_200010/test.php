<?php
session_name('RESELLER_SESSION');
session_start();
include 'konak.php';
require_once './config/config.php';
require_once 'includes/auth_validate.php';


    $pagelimit = 50;

    $resellerid = 0;
    $page = 1;


    $db = getDbInstance();
    $select = array('resellerid','jumlah');

    $total = 0;

    $db->where('resellerid', $resellerid)->where('ispay','1');
    $db->pageLimit = $pagelimit;

    $rows = $db->arraybuilder()->paginate('penjualancredit', $page, $select);

    foreach ($rows as $row): 
     $total += $row['jumlah'];    
    endforeach;
    
    if($total >= 200){
        
        $_SESSION['failure'] = "You Hash Reach limit Slot Credit Transfer : ";
        header('location: transfer_credit.php');
        exit;
        
        
    }
echo $total;
