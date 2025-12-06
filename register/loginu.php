<?php
session_start();

// Function to display alert messages
function displayAlert($message, $alertType) {
    return "<div class=\"alert alert-$alertType rounded-0\">$message</div>";
}

// Assuming you have a database connection in db-connect.php
include_once('db-connect.php');

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    extract($_POST);

    // User Login
    if (isset($_POST['login'])) {
        // ... (Your existing login logic here)
        // After successful login, you can set session variables or redirect to a new page
        if ($loginSuccessful) {
            $_SESSION['username'] = $username; // Set session variable for the logged-in user
            header('Location: dashboard.php');
            exit;
        } else {
            $login_error = "Incorrect username or password. Please try again.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Authentication</title>
    <!-- Include Bootstrap CSS and JS links here -->
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <!-- Login Form -->
                <form action="" method="POST">
                    <h2>Login</h2>
                    <!-- Replace the following lines with your actual form fields -->
                    <div class="mb-3">
                        <label for="username" class="form-label">Username</label>
                        <input type="text" class="form-control" id="username" name="username" required>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>
                    <!-- End of form fields -->

                    <button type="submit" class="btn btn-primary" name="login">Login</button>
                </form>

                <!-- Display Login Error Message -->
                <?php
                if (!empty($login_error)) {
                    echo displayAlert($login_error, 'danger');
                }
                ?>
            </div>
        </div>
    </div>
</body>
</html>
