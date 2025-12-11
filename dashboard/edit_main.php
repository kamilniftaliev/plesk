<?php
session_name('DASHBOARD_SESSION');
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
    $confirmpassword = filter_input(INPUT_POST, 'confirmpassword', FILTER_SANITIZE_SPECIAL_CHARS);

    // Validate password strength
    $password_errors = [];

    // Check minimum length (8 characters)
    if (strlen($pw) < 8) {
        $password_errors[] = "at least 8 characters";
    }

    // Check maximum length (64 characters)
    if (strlen($pw) > 64) {
        $password_errors[] = "no more than 64 characters";
    }

    // Check for at least one letter
    if (!preg_match('/[a-zA-Z]/', $pw)) {
        $password_errors[] = "at least one letter";
    }

    // Check for at least one number
    if (!preg_match('/[0-9]/', $pw)) {
        $password_errors[] = "at least one number";
    }

    // If password doesn't meet requirements, show error
    if (!empty($password_errors)) {
        $_SESSION['failure'] = "Password does not meet requirements. Must have: " . implode(", ", $password_errors) . ".";
        header('location: edit_main.php');
        exit;
    }

    // Check if new password and confirm password match
    if ($pw !== $confirmpassword) {
        $_SESSION['failure'] = "New passwords do not match. Please make sure both password fields are identical.";
        header('location: edit_main.php');
        exit;
    }

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

if ($_SESSION['admin_type'] == 'user') {
    require_once 'includes/user_header.php';
}
if ($_SESSION['admin_type'] == 'admin') {
    require_once 'includes/admin_header.php';
}
if ($_SESSION['admin_type'] == 'reseller') {
    require_once 'includes/reseller_header.php';
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