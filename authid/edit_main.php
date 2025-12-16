<?php
session_name('AUTHID_SESSION');
session_start();
require_once '../config/config.php';
require_once '../includes/auth_validate.php';

$admin_user_id = $_SESSION['admin_id'];

$db = getDbInstance();
$db->where('id', $admin_user_id);
$row = $db->get('user');
$name = $row[0]['username'];
$hasholdpass = $row[0]['password'];





if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $oldpass = filter_input(INPUT_POST, 'oldpassword', FILTER_SANITIZE_SPECIAL_CHARS);
    $pw = filter_input(INPUT_POST, 'newpassword', FILTER_SANITIZE_SPECIAL_CHARS);

    if (!password_verify($oldpass, $hasholdpass)) {
        $_SESSION['failure'] = "Old password Inputed Is not match with record";
        header('location: edit_main.php');
        exit;
    }

    $pw = password_hash($pw, PASSWORD_DEFAULT);
    $data_to_update = [
        'password' => $pw,
    ];




    ;
    $db = getDbInstance();
    $db->where('id', $admin_user_id);
    $stat = $db->update('user', $data_to_update);



    if ($stat) {
        $_SESSION['success'] = "user info has been updated successfully";
    } else {
        $_SESSION['failure'] = "Failed to update Admin user : " . $db->getLastError();
    }

    header('location: edit_main.php');
    exit;

}


$db = getDbInstance();
$db->where('id', $admin_user_id);

$admin_account = $db->getOne("user");

if (isset($_SESSION['admin_type']) && $_SESSION['admin_type'] == 'user') {
    require_once 'includes/user_header.php';
}
if (isset($_SESSION['admin_type']) && $_SESSION['admin_type'] == 'admin') {
    require_once '../includes/header.php';
}
if (isset($_SESSION['admin_type']) && $_SESSION['admin_type'] == 'authid') {
    require_once 'includes/authid_header.php';
}

?>
<div id="page-wrapper">

    <div class="row">
        <h1 class="page-header">Update User Info</h1>
    </div>
    <?php include_once 'includes/flash_messages.php'; ?>
    <form class="well form-horizontal" action="" method="post" id="contact_form" enctype="multipart/form-data">
        <?php include_once '../forms/pass_edit_form.php'; ?>
    </form>
</div>




<?php include_once '../includes/footer.php'; ?>