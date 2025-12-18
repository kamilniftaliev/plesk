<?php
session_name('DASHBOARD_SESSION');
session_start();
require_once '../includes/auth_validate.php';

$serverid = filter_input(INPUT_GET, 'serverid');
$operation = filter_input(INPUT_GET, 'operation', FILTER_SANITIZE_SPECIAL_CHARS);
($operation == 'edit') ? $edit = true : $edit = false;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (getCurrentUserType() !== 'admin') {
        echo 'Permission Denied';
        exit();
    }
    $status = filter_input(INPUT_POST, 'status', FILTER_SANITIZE_SPECIAL_CHARS);
    $limitflash = filter_input(INPUT_POST, 'limitflash', FILTER_SANITIZE_SPECIAL_CHARS);
    $limitfrp = filter_input(INPUT_POST, 'limitfrp', FILTER_SANITIZE_SPECIAL_CHARS);
    $limitfdl = filter_input(INPUT_POST, 'limitfdl', FILTER_SANITIZE_SPECIAL_CHARS);
    $serverid = filter_input(INPUT_GET, 'serverid', FILTER_VALIDATE_INT);
    $serversupport = filter_input(INPUT_POST, 'serversupport', FILTER_SANITIZE_SPECIAL_CHARS);
    $mihost = filter_input(INPUT_POST, 'mihost', FILTER_SANITIZE_SPECIAL_CHARS);
    $passtoken = filter_input(INPUT_POST, 'passtoken', FILTER_SANITIZE_SPECIAL_CHARS);
    $uid = filter_input(INPUT_POST, 'uid', FILTER_SANITIZE_SPECIAL_CHARS);
    $apiurl = filter_input(INPUT_POST, 'apiurl', FILTER_SANITIZE_SPECIAL_CHARS);

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

    header('location: servers.php');
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
    <div class="form-container-responsive">
        <form class="well form-horizontal" action="" method="post" id="contact_form" enctype="multipart/form-data">
            <?php include_once '../forms/server_form.php'; ?>
        </form>
    </div>
</div>
<?php include_once '../includes/footer.php'; ?>