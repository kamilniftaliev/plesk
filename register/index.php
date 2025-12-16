<?php
session_name('DASHBOARD_SESSION');
session_start();
include_once('register.php');
require_once '../config/config.php';
require_once '../includes/header.php';
?>

<style>
    .otp-input {
        font-size: 24px;
        text-align: center;
        letter-spacing: 10px;
        font-weight: bold;
    }

    .otp-info {
        background-color: #d9edf7;
        border: 1px solid #bce8f1;
        color: #31708f;
        padding: 15px;
        border-radius: 4px;
        margin-bottom: 15px;
    }

    @media (prefers-color-scheme: dark) {
        .otp-info {
            background-color: #1e293b;
            border-color: #374151;
            color: #93c5fd;
        }
    }

    .resend-otp-link {
        display: inline-block;
        margin-top: 10px;
        color: #337ab7;
        text-decoration: none;
    }

    .resend-otp-link:hover {
        text-decoration: underline;
    }

    @media (prefers-color-scheme: dark) {
        .resend-otp-link {
            color: #60a5fa;
        }
    }

    .register-link {
        color: #337ab7;
        text-decoration: none;
    }

    .register-link:hover {
        text-decoration: underline;
    }

    @media (prefers-color-scheme: dark) {
        .register-link {
            color: #60a5fa;
        }
    }

    .error-border {
        border-color: #dc3545 !important;
    }

    .error-message {
        color: #dc3545;
        font-size: 12px;
        margin-top: 5px;
        display: none;
    }

    .error-message.show {
        display: block;
    }
</style>

<div id="page-" class="col-md-4 col-md-offset-4">
    <form class="form loginform" method="POST" id="registerForm" onsubmit="return validateForm()">
        <div class="login-panel panel panel-default">
            <div class="panel-heading">Register New Account</div>
            <div class="panel-body">
                <!-- Username Field -->
                <div class="form-group">
                    <label class="control-label">Username</label>
                    <input type="text" name="username" id="username" class="form-control" required="required"
                        value="<?= (isset($_POST['username']) ? htmlspecialchars($_POST['username']) : "") ?>"
                        oninput="validateUsername()">
                    <small class="error-message" id="username-error">Username can only contain letters and numbers (no
                        spaces or special characters)</small>
                </div>

                <!-- Email Field -->
                <div class="form-group">
                    <label class="control-label">Email</label>
                    <input type="email" name="email" id="email" class="form-control" required="required"
                        value="<?= (isset($_POST['email']) ? htmlspecialchars($_POST['email']) : "") ?>"
                        oninput="validateEmail()">
                    <small class="error-message" id="email-error">Please enter a valid email address</small>
                </div>

                <!-- Password Field -->
                <div class="form-group">
                    <label class="control-label">Password</label>
                    <input type="password" name="password" id="password" class="form-control" required="required"
                        minlength="6" oninput="validatePasswordMatch()">
                    <small style="color: #666; display: block; margin-top: 5px;">Minimum 6 characters</small>
                </div>

                <!-- Confirm Password Field -->
                <div class="form-group">
                    <label class="control-label">Confirm Password</label>
                    <input type="password" name="cpassword" id="cpassword" class="form-control" required="required"
                        minlength="6" oninput="validatePasswordMatch()">
                    <small class="error-message" id="password-error">Passwords do not match</small>
                </div>

                <!-- Google reCAPTCHA (hidden in DEV_MODE) -->
                <?php if (!defined('DEV_MODE') || DEV_MODE !== true): ?>
                    <div class="form-group">
                        <div class="g-recaptcha" data-sitekey="6LchFhYsAAAAAK9GfxkXYCDOJ5KQt2ulJy-VpSCM"></div>
                    </div>
                <?php endif; ?>

                <!-- Error Messages from Server -->
                <?php if (isset($error_msg)): ?>
                    <div class="alert alert-danger alert-dismissable fade in">
                        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                        <?= $error_msg ?>
                    </div>
                <?php endif; ?>

                <?php if (isset($err_captcha)): ?>
                    <div class="alert alert-danger alert-dismissable fade in">
                        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                        <?= $err_captcha ?>
                    </div>
                <?php endif; ?>

                <button type="submit" class="btn btn-success btn-block" id="submitBtn">Register</button>

                <div style="text-align: center; margin-top: 15px;">
                    <a href="../dashboard/login.php" class="register-link">Already have an account? Login</a>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
    // Email validation
    function validateEmail() {
        const emailInput = document.getElementById('email');
        const emailError = document.getElementById('email-error');
        const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

        if (emailInput.value && !emailPattern.test(emailInput.value)) {
            emailInput.classList.add('error-border');
            emailError.classList.add('show');
            return false;
        } else {
            emailInput.classList.remove('error-border');
            emailError.classList.remove('show');
            return true;
        }
    }

    // Username validation (alphanumeric only)
    function validateUsername() {
        const usernameInput = document.getElementById('username');
        const usernameError = document.getElementById('username-error');
        const usernamePattern = /^[a-zA-Z0-9]+$/;

        if (usernameInput.value && !usernamePattern.test(usernameInput.value)) {
            usernameInput.classList.add('error-border');
            usernameError.classList.add('show');
            return false;
        } else {
            usernameInput.classList.remove('error-border');
            usernameError.classList.remove('show');
            return true;
        }
    }

    // Password match validation
    function validatePasswordMatch() {
        const password = document.getElementById('password').value;
        const cpassword = document.getElementById('cpassword').value;
        const passwordError = document.getElementById('password-error');
        const cpasswordInput = document.getElementById('cpassword');

        if (cpassword && password !== cpassword) {
            cpasswordInput.classList.add('error-border');
            passwordError.classList.add('show');
            return false;
        } else {
            cpasswordInput.classList.remove('error-border');
            passwordError.classList.remove('show');
            return true;
        }
    }

    // Form validation before submission
    function validateForm() {
        const isEmailValid = validateEmail();
        const isUsernameValid = validateUsername();
        const isPasswordMatch = validatePasswordMatch();

        // Check all validations
        if (!isEmailValid || !isUsernameValid || !isPasswordMatch) {
            return false; // Prevent form submission
        }

        return true; // Allow form submission
    }

    // Add event listeners for real-time validation
    document.addEventListener('DOMContentLoaded', function () {
        document.getElementById('email').addEventListener('blur', validateEmail);
        document.getElementById('username').addEventListener('blur', validateUsername);
        document.getElementById('cpassword').addEventListener('blur', validatePasswordMatch);
    });
</script>

<?php if (!defined('DEV_MODE') || DEV_MODE !== true): ?>
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
<?php endif; ?>

<?php include BASE_PATH . '/includes/footer.php'; ?>