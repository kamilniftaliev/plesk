<?php
session_name('AUTHID_SESSION');
session_start();
include 'konak.php';
require_once './config/config.php';
require_once 'includes/auth_validate.php';


    $pagelimit = 50;

    $authidid = 0;
    $page = 1;


    $db = getDbInstance();
    $select = array('authidid','jumlah');

    $total = 0;

    $db->where('authidid', $authidid)->where('ispay','1');
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
