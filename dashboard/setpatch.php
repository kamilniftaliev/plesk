<?php
session_name('DASHBOARD_SESSION');
session_start();
require_once './config/config.php';
require_once 'includes/auth_validate.php';

$serverid = filter_input(INPUT_GET, 'serverid');
$operation = filter_input(INPUT_GET, 'operation', FILTER_SANITIZE_SPECIAL_CHARS);
($operation == 'edit') ? $edit = true : $edit = false;

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
	if ($_SESSION['admin_type'] !== 'admin') {
		echo 'Permission Denied';
		exit();
	}
$authtype = filter_input(INPUT_GET, 'authtype', FILTER_SANITIZE_SPECIAL_CHARS);
$status = filter_input(INPUT_GET, 'status', FILTER_SANITIZE_SPECIAL_CHARS);
$multi = filter_input(INPUT_GET, 'multi', FILTER_SANITIZE_SPECIAL_CHARS);
$pencarianstatus  ="";

if ($authtype == "frp") {
    $pencarianstatus = "frpon";
}
if ($authtype == "fdl") {
    $pencarianstatus = "fdlon" ;
}
if ($authtype == "flash") {
    $pencarianstatus = "edlon" ;
 }
if ($authtype == "ubl") {
    $pencarianstatus = "ublon" ;
}

$statuspatch = "ORIGINAL BLOB";
if($status == 1 ) {
    $statuspatch = "MULTI PATCH";
}  elseif ($status == 2){
    $statuspatch = "PATCH PATCH ONLY";
    $multi = 1;
} else {
   // $statuspatch = "PATCH PATCH ONLY";
   $multi = 1;
    
}


$pagelimit = 50;
$page = 1;
$db = getDbInstance();
$select = array('id','patchflash');
$db->where('id', '1');
$db->pageLimit = $pagelimit;
$rows = $db->arraybuilder()->paginate('multipatch', $page, $select);
foreach ($rows as $row): 
            $id = $row['id'];
         
            $data_to_update["multipliers"] = $multi;
            $data_to_update["patchflash"] = $status;
            $db = getDbInstance();
            $db->where('id',$id);
            $stat = $db->update('multipatch', $data_to_update);
            if ($stat){
               	$_SESSION['success'] = "Changed the Patch  TO "  . $statuspatch . " Successfully.";

            } else {
                
              
                
            }

            
 endforeach;

header('location: serverspatch.php');
exit;



echo $authtype;
die();

$db = getDbInstance();
$db->where('id', $serverid);
$row = $db->getOne('server');


     $data_to_update = [
        'status' => $status,
        'limitedl' => $limitflash,
        'limitfrp' => $limitfrp,
        'limitfdl' => $limitfdl,
        'mihost' => $mihost,
        'apiurl' => $apiurl,
        'passtoken' => $passtoken,
        'uid' => $uid,
        'limitleftedl' => $limitflash,
        'limitleftfrp' => $limitfrp,
        'limitleftfdl' => $limitfdl,
        'serversupport' => $serversupport
    ];
	$db = getDbInstance();
	$db->where('id', $serverid);
	$stat = $db->update('server', $data_to_update);

	if ($stat) {
		$_SESSION['success'] = "Changed the Server of " . $user_name . " Successfully.";
	} else {
		$_SESSION['failure'] = "Failed to update Admin user : " . $db->getLastError();
	}

	header('location: serverstatus.php');
	exit;

}

//Select where clause
$db = getDbInstance();
$db->where('id', $serverid);

$admin_account = $db->getOne("server");

// Set values to $row

// import header
require_once 'includes/admin_header.php';
?>
<div id="page-wrapper">

    <div class="row">
     <div class="col-lg-12">
            <h2 class="page-header">Update Servers Info</h2>
        </div>

    </div>
    <?php include_once 'includes/flash_messages.php';?>
    <form class="well form-horizontal" action="" method="post"  id="contact_form" enctype="multipart/form-data">
        <?php include_once './forms/server_form.php';?>
    </form>
</div>




<?php include_once 'includes/footer.php';?>