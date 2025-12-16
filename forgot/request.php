<?php
session_start();
require_once '../config/config.php';

/**
 * Send password reset code via Telegram
 */
function sendTelegramResetCode($chat_id, $reset_code, $username)
{
	// Check if Telegram is enabled and configured
	if (!defined('TELEGRAM_BOT_ENABLED') || !TELEGRAM_BOT_ENABLED) {
		return false;
	}

	if (!defined('TELEGRAM_BOT_TOKEN') || TELEGRAM_BOT_TOKEN === 'YOUR_BOT_TOKEN_HERE') {
		return false;
	}

	$bot_token = TELEGRAM_BOT_TOKEN;
	$url = "https://api.telegram.org/bot{$bot_token}/sendMessage";

	// Create message with HTML formatting
	$message = "🔐 <b>Password Reset Code</b>\n\n";
	$message .= "Hello <b>" . htmlspecialchars($username) . "</b>,\n\n";
	$message .= "Your password reset code is:\n\n";
	$message .= "🔢 <code>" . htmlspecialchars($reset_code) . "</code>\n\n";
	$message .= "⏰ This code will expire in <b>10 minutes</b>\n";
	$message .= "🔒 Do not share this code with anyone\n\n";
	$message .= "⚠️ If you did not request this code, please ignore this message.";

	// Prepare POST data
	$post_data = [
		'chat_id' => $chat_id,
		'text' => $message,
		'parse_mode' => 'HTML'
	];

	// Send request using cURL
	if (function_exists('curl_init')) {
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($post_data));
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
		curl_setopt($ch, CURLOPT_TIMEOUT, 10);

		$response = curl_exec($ch);
		$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		curl_close($ch);

		if ($http_code === 200) {
			$result = json_decode($response, true);
			return isset($result['ok']) && $result['ok'] === true;
		}
	}

	return false;
}

/**
 * Send password reset code via Email
 */
function sendEmailResetCode($email, $reset_code, $username)
{
	$to = $email;
	$subject = "Password Reset Code";

	// Create HTML email body
	$message = "
	<html>
	<head>
		<style>
			body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
			.container { max-width: 600px; margin: 0 auto; padding: 20px; }
			.header { background-color: #4CAF50; color: white; padding: 20px; text-align: center; }
			.content { background-color: #f9f9f9; padding: 20px; border: 1px solid #ddd; }
			.code { font-size: 32px; font-weight: bold; color: #4CAF50; text-align: center; padding: 20px; background-color: #fff; border: 2px dashed #4CAF50; margin: 20px 0; letter-spacing: 5px; }
			.footer { text-align: center; margin-top: 20px; color: #777; font-size: 12px; }
		</style>
	</head>
	<body>
		<div class='container'>
			<div class='header'>
				<h1>🔐 Password Reset Code</h1>
			</div>
			<div class='content'>
				<p>Hello <strong>" . htmlspecialchars($username) . "</strong>,</p>
				<p>You requested to reset your password. Use the code below to continue:</p>
				<div class='code'>" . htmlspecialchars($reset_code) . "</div>
				<p><strong>⏰ This code will expire in 10 minutes</strong></p>
				<p>🔒 Do not share this code with anyone</p>
				<p>⚠️ If you did not request this code, please ignore this email.</p>
			</div>
			<div class='footer'>
				<p>This is an automated message, please do not reply.</p>
			</div>
		</div>
	</body>
	</html>
	";

	// Set headers for HTML email
	$headers = "MIME-Version: 1.0\r\n";
	$headers .= "Content-type:text/html;charset=UTF-8\r\n";
	$headers .= "From: noreply@yoursite.com\r\n";

	return mail($to, $subject, $message, $headers);
}

// Handle cancel reset request
if (isset($_GET['cancel_reset'])) {
	unset($_SESSION['reset_pending']);
	unset($_SESSION['reset_code']);
	unset($_SESSION['reset_email']);
	unset($_SESSION['reset_username']);
	unset($_SESSION['reset_expires']);
	unset($_SESSION['reset_dev_display']);
	redirectTo('/forgot/index.php');
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	if (isset($_POST['email'])) {
		$email = trim($_POST['email']);

		// Validate email format
		if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
			$_SESSION['reset_error'] = "Please enter a valid email address.";
			redirectTo('/forgot/index.php');
		}

		// Check if user exists with this email
		$db = getDbInstance();
		$db->where('email', $email);
		$user = $db->getOne('user');

		if (!$user) {
			// For security, don't reveal if email exists or not
			$_SESSION['reset_success'] = "If an account exists with this email, a reset code will be sent.";
			redirectTo('/forgot/index.php');
		}

		// Generate 4-digit reset code
		$reset_code = str_pad(rand(0, 9999), 4, '0', STR_PAD_LEFT);

		// Store reset information in session
		$_SESSION['reset_pending'] = true;
		$_SESSION['reset_code'] = $reset_code;
		$_SESSION['reset_email'] = $email;
		$_SESSION['reset_username'] = $user['username'];
		$_SESSION['reset_user_id'] = $user['id'];
		$_SESSION['reset_expires'] = time() + 600; // 10 minutes

		// Get user's Telegram chat ID if available
		$telegram_chat_id = !empty($user['telegram_chat_id']) ? $user['telegram_chat_id'] : null;

		// Store which methods are available
		$_SESSION['reset_email_available'] = !empty($email);
		$_SESSION['reset_telegram_chat_id'] = $telegram_chat_id;

		// Send reset code via email/Telegram or display on screen (dev mode)
		if (defined('DEV_MODE') && DEV_MODE === true) {
			// Development mode - store code in session for display
			$_SESSION['reset_dev_display'] = $reset_code;
			redirectTo('/forgot/index.php');
		} else {
			// Production mode - send via email and/or Telegram
			$email_sent = false;
			$telegram_sent = false;

			// Try to send via email
			if (!empty($email)) {
				$email_sent = sendEmailResetCode($email, $reset_code, $user['username']);
			}

			// Try to send via Telegram if chat_id is available
			if (!empty($telegram_chat_id)) {
				$telegram_sent = sendTelegramResetCode($telegram_chat_id, $reset_code, $user['username']);
			}

			// Check if at least one method succeeded
			if ($email_sent || $telegram_sent) {
				redirectTo('/forgot/index.php');
			} else {
				// Failed to send via any method
				unset($_SESSION['reset_pending']);
				unset($_SESSION['reset_code']);
				$_SESSION['reset_error'] = "Failed to send reset code. Please try again or contact administrator.";
				redirectTo('/forgot/index.php');
			}
		}
	}

	// Handle reset code verification and password change
	if (isset($_POST['reset_code']) && isset($_POST['new_password']) && isset($_POST['confirm_password'])) {
		$submitted_code = trim($_POST['reset_code']);
		$new_password = $_POST['new_password'];
		$confirm_password = $_POST['confirm_password'];

		// Check if reset session is still valid
		if (!isset($_SESSION['reset_pending']) || !$_SESSION['reset_pending']) {
			$_SESSION['reset_error'] = "No active reset session. Please start over.";
			redirectTo('/forgot/index.php');
		}

		// Check if reset code has expired
		if (time() > $_SESSION['reset_expires']) {
			unset($_SESSION['reset_pending']);
			unset($_SESSION['reset_code']);
			$_SESSION['reset_error'] = "Reset code has expired. Please request a new one.";
			redirectTo('/forgot/index.php');
		}

		// Verify reset code
		if ($submitted_code !== $_SESSION['reset_code']) {
			$_SESSION['reset_error'] = "Invalid reset code. Please try again.";
			redirectTo('/forgot/index.php');
		}

		// Validate passwords match
		if ($new_password !== $confirm_password) {
			$_SESSION['reset_error'] = "Passwords do not match.";
			redirectTo('/forgot/index.php');
		}

		// Validate password length
		if (strlen($new_password) < 6) {
			$_SESSION['reset_error'] = "Password must be at least 6 characters long.";
			redirectTo('/forgot/index.php');
		}

		// Update password in database
		$db = getDbInstance();
		$hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

		$db->where('id', $_SESSION['reset_user_id']);
		$result = $db->update('user', ['password' => $hashed_password]);

		if ($result) {
			// Clear reset session
			unset($_SESSION['reset_pending']);
			unset($_SESSION['reset_code']);
			unset($_SESSION['reset_email']);
			unset($_SESSION['reset_username']);
			unset($_SESSION['reset_user_id']);
			unset($_SESSION['reset_expires']);
			unset($_SESSION['reset_dev_display']);

			$_SESSION['reset_success'] = "Password reset successfully! You can now login with your new password.";
			redirectTo('/dashboard/login.php');
		} else {
			$_SESSION['reset_error'] = "Failed to update password. Please try again.";
			redirectTo('/forgot/index.php');
		}
	}
}

// Redirect to forgot password page if accessed directly
redirectTo('/forgot/index.php');
