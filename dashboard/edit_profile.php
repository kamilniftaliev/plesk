<?php
session_name('DASHBOARD_SESSION');
session_start();
require_once './config/config.php';
require_once 'includes/auth_validate.php';

$admin_user_id = $_SESSION['admin_id'];

// Fetch current user data
$db = getDbInstance();
$db->where('id', $admin_user_id);
$row = $db->get('user');

if (empty($row)) {
    $_SESSION['failure'] = "User not found";
    header('location: index.php');
    exit;
}

$admin_account = $row[0];

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_SPECIAL_CHARS);
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $telegram_chat_id = filter_input(INPUT_POST, 'telegram_chat_id', FILTER_SANITIZE_SPECIAL_CHARS);

    // Validation errors array
    $errors = [];

    // Validate username - must be letters only
    if (strlen(trim($username)) < 6) {
        $errors[] = "Username must be at least 6 characters long";
    }
    if (strlen(trim($username)) > 30) {
        $errors[] = "Username must not exceed 30 characters";
    }
    if (!preg_match('/^[a-zA-Z]+$/', trim($username))) {
        $errors[] = "Username must contain only letters (no numbers, spaces, or special characters)";
    }

    // Validate email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Please enter a valid email address";
    }

    // Validate Telegram Chat ID (optional, but if provided must be exactly 10 digits)
    if (!empty(trim($telegram_chat_id))) {
        if (!preg_match('/^\d{10}$/', trim($telegram_chat_id))) {
            $errors[] = "Telegram Chat ID must be exactly 10 digits";
        }
    }

    // Check if username already exists (for other users)
    $db = getDbInstance();
    $db->where('username', $username);
    $db->where('id', $admin_user_id, '!=');
    $existing_user = $db->getOne('user');

    if (!empty($existing_user)) {
        $errors[] = "Username already exists. Please choose a different username";
    }

    // Check if email already exists (for other users)
    $db = getDbInstance();
    $db->where('email', $email);
    $db->where('id', $admin_user_id, '!=');
    $existing_email = $db->getOne('user');

    if (!empty($existing_email)) {
        $errors[] = "Email already exists. Please use a different email address";
    }

    // If there are errors, show them
    if (!empty($errors)) {
        $_SESSION['failure'] = implode(". ", $errors);
        header('location: edit_profile.php');
        exit;
    }

    // Prepare data to update
    $data_to_update = [
        'username' => $username,
        'email' => $email,
        'telegram_chat_id' => !empty(trim($telegram_chat_id)) ? trim($telegram_chat_id) : null,
    ];

    // Update user profile
    $db = getDbInstance();
    $db->where('id', $admin_user_id);
    $stat = $db->update('user', $data_to_update);

    if ($stat) {
        // Update session username if changed
        $_SESSION['name'] = $username;
        $_SESSION['success'] = "Profile updated successfully!";
    } else {
        $_SESSION['failure'] = "Failed to update profile: " . $db->getLastError();
    }

    header('location: edit_profile.php');
    exit;
}

// Refresh user data after potential update
$db = getDbInstance();
$db->where('id', $admin_user_id);
$admin_account = $db->getOne("user");

// Load appropriate header based on user type
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
        <h1 class="page-header">Edit Profile</h1>
    </div>
    <?php include_once 'includes/flash_messages.php'; ?>
    <form class="well form-horizontal" action="" method="post" id="profile_form" enctype="multipart/form-data">
        <?php include_once './forms/profile_edit_form.php'; ?>
    </form>
</div>




<?php include_once 'includes/footer.php'; ?>