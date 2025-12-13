<?php
session_name('AUTHID_SESSION');
session_start();
include('../includes/konak.php');
require_once '../config/config.php';
require_once '../includes/auth_validate.php';
if (!isset($_SESSION['admin_type']) || $_SESSION['admin_type'] !== 'admin') {
    // Show permission denied message
    header('Location:' . URL_PREFIX . '/authid/login.php');
    exit();
}

//serve POST method, After successful insert, redirect to customers.php page.
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $passtoken = filter_input(INPUT_POST, 'passtoken', FILTER_SANITIZE_SPECIAL_CHARS);
    $uid = filter_input(INPUT_POST, 'uid', FILTER_SANITIZE_SPECIAL_CHARS);
    $deviceid = filter_input(INPUT_POST, 'deviceid', FILTER_SANITIZE_SPECIAL_CHARS);
    $mihost = filter_input(INPUT_POST, 'mihost', FILTER_SANITIZE_SPECIAL_CHARS);
    $apiurl = filter_input(INPUT_POST, 'apiurl', FILTER_SANITIZE_SPECIAL_CHARS);

    if ($mihost == "global") {
        $mihost = "unlock.update.intl.miui.com";
    } elseif ($mihost == "in") {
        $mihost = "in-unlock.update.intl.miui.com";
    } elseif ($mihost == "cn") {
        $mihost = "unlock.update.miui.com";
    }


    $sqls = "INSERT INTO server (passtoken,uid,deviceid,mihost,apiurl,delay,servicetoken,ssecurity,unlockapiph) VALUES ('$passtoken','$uid','$deviceid','$mihost','$apiurl',0,'','','')";
    if (mysqli_query($koneksi, $sqls)) {
        $_SESSION['success'] = "Server added successfully!";
        header('location: add_server.php');
        exit();
    } else {
        echo 'insert failed: ';
        exit();

    }





}

//We are using same form for adding and editing. This is a create form so declare $edit = false.
$edit = false;

require_once './includes/admin_header.php';
?>




<div id="page-wrapper">

    <?php include_once 'includes/flash_messages.php'; ?>
    <div class="row">
        <div class="row">
            <h1 class="page-header">Add Server</h1>
        </div>

    </div>
    <form class="form w-full md:w-2/3 lg:w-1/2 mx-auto" action="" method="post" id="add_server_form"
        enctype="multipart/form-data">
        <?php include_once('../forms/add_server_form.php'); ?>
    </form>
</div>






<script type="text/javascript">
    $(document).ready(function () {
        $("#add_server_form").validate({
            rules: {
                passtoken: {
                    required: true,
                    minlength: 3
                },
                uid: {
                    required: true,
                    minlength: 3
                },
                deviceid: {
                    required: true,
                    minlength: 3
                },
                apiurl: {
                    required: true,
                    minlength: 3
                },
            }
        });
    });
</script>

<?php include_once '../includes/footer.php'; ?>