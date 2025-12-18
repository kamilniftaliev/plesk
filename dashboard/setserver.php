<?php
session_name('DASHBOARD_SESSION');
session_start();
require_once '../includes/auth_validate.php';

// Check permission for this page
requirePermission('setserver');

$serverid = filter_input(INPUT_GET, 'serverid');
$operation = filter_input(INPUT_GET, 'operation', FILTER_SANITIZE_SPECIAL_CHARS);
($operation == 'edit') ? $edit = true : $edit = false;

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    $authtype = filter_input(INPUT_GET, 'authtype', FILTER_SANITIZE_SPECIAL_CHARS);
    $status = filter_input(INPUT_GET, 'status', FILTER_SANITIZE_SPECIAL_CHARS);

    // If accessed without parameters, redirect to serverstatus page
    if (empty($authtype) && empty($status) && empty($serverid)) {
        header('location: serverstatus.php');
        exit;
    }

    // Only process if both authtype and status parameters are provided
    if (!empty($authtype) && !empty($status)) {
        $pencarianstatus = "";

        if ($authtype == "frp") {
            $pencarianstatus = "frpon";
        }
        if ($authtype == "fdl") {
            $pencarianstatus = "fdlon";
        }
        if ($authtype == "flash") {
            $pencarianstatus = "edlon";
        }
        if ($authtype == "ubl") {
            $pencarianstatus = "ublon";
        }

        // Validate that we have a valid column name
        if (empty($pencarianstatus)) {
            $_SESSION['failure'] = "Invalid auth type specified.";
            header('location: serverstatus.php');
            exit;
        }

        $statuson = "";

        if ($status == "on") {

            $statuson = 1;
        }

        if ($status == "off") {

            $statuson = 0;
        }

        // Validate that we have a valid status
        if ($statuson === "") {
            $_SESSION['failure'] = "Invalid status specified.";
            header('location: serverstatus.php');
            exit;
        }

    $pagelimit = 50;
    $page = 1;
    $db = getDbInstance();
    $select = array('limitedl', 'limitfrp', 'limitfdl', 'id');
    $db->where('status', 'ON');
    $db->pageLimit = $pagelimit;
    $rows = $db->arraybuilder()->paginate('server', $page, $select);
    foreach ($rows as $row):
        $id = $row['id'];


        $data_to_update[$pencarianstatus] = $statuson;
        $db = getDbInstance();
        $db->where('id', $id);
        $stat = $db->update('server', $data_to_update);
        if ($stat) {
            $_SESSION['success'] = "Changed the AUTH " . $authtype . " TO " . $status . " Successfully.";

        } else {



        }


    endforeach;

    header('location: serverstatus.php');
    exit;
    }



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
require_once '../includes/header.php';
?>
<div id="page-wrapper">

    <div class="row">
        <h1 class="page-header">Update Servers Info</h1>
    </div>
    <?php include_once 'includes/flash_messages.php'; ?>
    <form class="well form-horizontal" action="" method="post" id="contact_form" enctype="multipart/form-data">
        <?php include_once './forms/server_form.php'; ?>
    </form>
</div>




<?php include_once '../includes/footer.php'; ?>