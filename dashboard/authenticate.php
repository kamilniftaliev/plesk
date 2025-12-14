<?php

require_once '../config/config.php';
session_name('DASHBOARD_SESSION');
session_start();

// Helper function to redirect to login with return_url
function redirectToLoginWithReturn($base_url = '/dashboard/login.php') {
	$url = $base_url;
	if (isset($_GET['return_url'])) {
		$url .= '?return_url=' . urlencode($_GET['return_url']);
	}
	redirectTo($url);
}

// Handle OTP cancellation
if (isset($_GET['cancel_otp'])) {
	unset($_SESSION['otp_pending']);
	unset($_SESSION['otp_code']);
	unset($_SESSION['otp_email']);
	unset($_SESSION['otp_telegram_chat_id']);
	unset($_SESSION['otp_user_id']);
	unset($_SESSION['otp_username']);
	unset($_SESSION['otp_admin_type']);
	unset($_SESSION['otp_remember']);
	unset($_SESSION['otp_expires']);
	unset($_SESSION['otp_dev_display']); // Clear dev display code
	unset($_SESSION['otp_return_url']); // Clear return URL
	redirectToLoginWithReturn();
}

// Handle OTP resend
if (isset($_GET['resend_otp'])) {
	if (isset($_SESSION['otp_pending']) && $_SESSION['otp_pending'] === TRUE && isset($_SESSION['otp_email'])) {
		// Generate new OTP
		$otp_code = str_pad(rand(0, 9999), 4, '0', STR_PAD_LEFT);
		$_SESSION['otp_code'] = $otp_code;
		$_SESSION['otp_expires'] = time() + 600; // 10 minutes

		// Send OTP email
		$email = $_SESSION['otp_email'];
		$username = $_SESSION['otp_username'] ?? 'User';

		if (defined('DEV_MODE') && DEV_MODE === true) {
			// Development mode - display OTP on screen
			$_SESSION['otp_dev_display'] = $otp_code;
			$_SESSION['otp_success'] = "A new verification code has been generated.";
		} else {
			// Production mode - send via email and/or Telegram
			$email_sent = false;
			$telegram_sent = false;

			// Try to send via email if available
			if (!empty($email)) {
				$email_sent = sendOTPEmail($email, $otp_code, $username);
			}

			// Try to send via Telegram if chat_id is available
			if (isset($_SESSION['otp_telegram_chat_id']) && !empty($_SESSION['otp_telegram_chat_id'])) {
				$telegram_sent = sendTelegramOTP($_SESSION['otp_telegram_chat_id'], $otp_code, $username);
			}

			// Set appropriate success/failure message
			if ($email_sent && $telegram_sent) {
				$_SESSION['otp_success'] = "A new verification code has been sent to your email and Telegram.";
			} elseif ($email_sent) {
				$_SESSION['otp_success'] = "A new verification code has been sent to your email.";
			} elseif ($telegram_sent) {
				$_SESSION['otp_success'] = "A new verification code has been sent to your Telegram.";
			} else {
				$_SESSION['otp_failure'] = "Failed to send verification code. Please try again.";
			}
		}
	}
	redirectToLoginWithReturn();
	exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

	// Handle OTP Verification
	if (isset($_POST['verify_otp'])) {
		$otp_code = filter_input(INPUT_POST, 'otp_code');

		// Validate OTP format
		if (!preg_match('/^\d{4}$/', $otp_code)) {
			$_SESSION['otp_failure'] = "Invalid code format. Please enter 4 digits.";
			redirectToLoginWithReturn();
			exit;
		}

		// Check if OTP session exists
		if (!isset($_SESSION['otp_pending']) || $_SESSION['otp_pending'] !== TRUE) {
			$_SESSION['login_failure'] = "OTP session expired. Please login again.";
			redirectToLoginWithReturn();
			exit;
		}

		// Check if OTP expired (10 minutes)
		if (!isset($_SESSION['otp_expires']) || time() > $_SESSION['otp_expires']) {
			unset($_SESSION['otp_pending']);
			unset($_SESSION['otp_code']);
			unset($_SESSION['otp_email']);
			unset($_SESSION['otp_user_id']);
			unset($_SESSION['otp_username']);
			unset($_SESSION['otp_admin_type']);
			unset($_SESSION['otp_remember']);
			$_SESSION['login_failure'] = "OTP code expired. Please login again.";
			redirectToLoginWithReturn();
			exit;
		}

		// Verify OTP code
		if ($otp_code === $_SESSION['otp_code']) {
			// OTP verified successfully - complete login
			$_SESSION['dashboard_user_logged_in'] = TRUE;
			$_SESSION['name'] = $_SESSION['otp_username'];
			$_SESSION['admin_id'] = $_SESSION['otp_user_id'];
			$_SESSION['admin_type'] = $_SESSION['otp_admin_type'];

			// Handle remember me functionality
			if (isset($_SESSION['otp_remember']) && $_SESSION['otp_remember']) {
				$user_id = $_SESSION['otp_user_id'];
				$series_id = randomString(16);
				$remember_token = getSecureRandomToken(20);
				$encryted_remember_token = password_hash($remember_token, PASSWORD_DEFAULT);

				$expiry_time = date('Y-m-d H:i:s', strtotime(' + 30 days'));
				$expires = strtotime($expiry_time);

				setcookie('series_id', $series_id, $expires, "/");
				setcookie('remember_token', $remember_token, $expires, "/");

				$db = getDbInstance();
				$db->where('id', $user_id);

				$update_remember = array(
					'series_id' => $series_id,
					'remember_token' => $encryted_remember_token,
					'expires' => $expiry_time
				);
				$db->update("user", $update_remember);
			}

			// Clear OTP session data
			unset($_SESSION['otp_pending']);
			unset($_SESSION['otp_code']);
			unset($_SESSION['otp_email']);
			unset($_SESSION['otp_telegram_chat_id']);
			unset($_SESSION['otp_user_id']);
			unset($_SESSION['otp_username']);
			unset($_SESSION['otp_admin_type']);
			unset($_SESSION['otp_remember']);
			unset($_SESSION['otp_expires']);
			unset($_SESSION['otp_dev_display']); // Clear dev display code

			// Get return URL before clearing session
			$return_url = $_SESSION['otp_return_url'] ?? '/dashboard/index.php';
			unset($_SESSION['otp_return_url']); // Clear return URL

			// Redirect to return URL or default index
			redirectTo($return_url);
		} else {
			// Wrong OTP code
			$_SESSION['otp_failure'] = "Invalid verification code. Please try again.";
			redirectToLoginWithReturn();
		}
	}

	// Handle Initial Login (Username/Password)
	$username = filter_input(INPUT_POST, 'username');
	$passwd = filter_input(INPUT_POST, 'passwd');
	$remember = filter_input(INPUT_POST, 'remember');

	// Get DB instance
	$db = getDbInstance();

	$db->where("username", $username);
	$row = $db->get('user');

	if ($db->count >= 1) {

		$db_password = $row[0]['password'];
		$user_id = $row[0]['id'];
		$user_email = $row[0]['email'] ?? null;
		$user_telegram_chat_id = $row[0]['telegram_chat_id'] ?? null;

		if (password_verify($passwd, $db_password)) {

			// Check if user has at least email or Telegram
			if (empty($user_email) && empty($user_telegram_chat_id)) {
				$_SESSION['login_failure'] = "No email or Telegram configured for this account. Please contact administrator.";
				redirectToLoginWithReturn();
				exit;
			}

			// Generate 4-digit OTP
			$otp_code = str_pad(rand(0, 9999), 4, '0', STR_PAD_LEFT);

			// Store OTP and user info in session
			$_SESSION['otp_pending'] = TRUE;
			$_SESSION['otp_code'] = $otp_code;
			$_SESSION['otp_email'] = $user_email;
			$_SESSION['otp_telegram_chat_id'] = $user_telegram_chat_id;
			$_SESSION['otp_user_id'] = $user_id;
			$_SESSION['otp_username'] = $row[0]['username'];
			$_SESSION['otp_admin_type'] = $row[0]['status'];
			$_SESSION['otp_remember'] = $remember ? true : false;
			$_SESSION['otp_expires'] = time() + 600; // OTP expires in 10 minutes

			// Store return URL if provided
			if (isset($_GET['return_url'])) {
				$_SESSION['otp_return_url'] = $_GET['return_url'];
			}

			// Send OTP via email/Telegram or display on screen (dev mode)
			if (defined('DEV_MODE') && DEV_MODE === true) {
				// Development mode - store OTP in session for display on login page
				$_SESSION['otp_dev_display'] = $otp_code;
				redirectToLoginWithReturn();
			} else {
				// Production mode - send via email and/or Telegram
				$email_sent = false;
				$telegram_sent = false;

				// Try to send via email if available
				if (!empty($user_email)) {
					$email_sent = sendOTPEmail($user_email, $otp_code, $username);
				}

				// Try to send via Telegram if chat_id is available
				if (!empty($user_telegram_chat_id)) {
					$telegram_sent = sendTelegramOTP($user_telegram_chat_id, $otp_code, $username);
				}

				// Check if at least one method succeeded
				if ($email_sent || $telegram_sent) {
					// Successfully sent OTP, redirect to OTP verification page
					redirectToLoginWithReturn();
					exit;
				} else {
					// Failed to send via any method
					unset($_SESSION['otp_pending']);
					unset($_SESSION['otp_code']);
					// $_SESSION['login_failure'] = "Failed to send verification code. Please try again or contact administrator.";
					$_SESSION['login_failure'] = $telegram_sent;
					// redirectToLoginWithReturn();
					exit;
				}
			}

		} else {
			$_SESSION['login_failure'] = "Invalid user name or password";
			redirectToLoginWithReturn();
		}

		exit;
	} else {
		$_SESSION['login_failure'] = "Invalid user name or password";
		redirectToLoginWithReturn();
		exit;
	}

} else {
	die('Method Not allowed');
}

/**
 * Send OTP via Telegram
 *
 * @param string $chat_id Telegram chat ID
 * @param string $otp_code 4-digit OTP code
 * @param string $username Username
 * @return bool True if message sent successfully, false otherwise
 */
function sendTelegramOTP($chat_id, $otp_code, $username)
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
	$message = "🔐 <b>Login Verification Code</b>\n\n";
	$message .= "Hello <b>" . htmlspecialchars($username) . "</b>,\n\n";
	$message .= "Your verification code is:\n\n";
	$message .= "🔢 <code>" . htmlspecialchars($otp_code) . "</code>\n\n";
	$message .= "⏰ This code will expire in <b>10 minutes</b>\n";
	$message .= "🔒 Do not share this code with anyone\n\n";
	$message .= "⚠️ If you did not request this code, please ignore this message.";

	// Prepare POST data
	$post_data = [
		'chat_id' => $chat_id,
		'text' => $message,
		'parse_mode' => 'HTML'
	];

	// Try cURL first if available, otherwise use file_get_contents
	if (function_exists('curl_init')) {
		// Send request using cURL
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

		// Check if request was successful
		if ($http_code === 200) {
			$result = json_decode($response, true);
			return isset($result['ok']) && $result['ok'] === true;
		} else {
			return false;
		}
	} else {
		// Fallback to file_get_contents with stream context
		$options = [
			'http' => [
				'method' => 'POST',
				'header' => 'Content-Type: application/x-www-form-urlencoded',
				'content' => http_build_query($post_data),
				'timeout' => 10,
				'ignore_errors' => true
			],
			'ssl' => [
				'verify_peer' => true,
				'verify_peer_name' => true
			]
		];

		$context = stream_context_create($options);
		$response = @file_get_contents($url, false, $context);

		// Check if request was successful
		if ($response !== false) {
			$result = json_decode($response, true);
			if (isset($result['ok']) && $result['ok'] === true) {
				return true;
			}
		}
	}

	return false;
}

/**
 * Send OTP verification email
 *
 * @param string $email Recipient email address
 * @param string $otp_code 4-digit OTP code
 * @param string $username Username
 * @return bool True if email sent successfully, false otherwise
 */
function sendOTPEmail($email, $otp_code, $username)
{
	$subject = "Your Login Verification Code";

	$message = "
	<html>
	<head>
		<style>
			body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
			.container { max-width: 600px; margin: 0 auto; padding: 20px; }
			.header { background-color: #4CAF50; color: white; padding: 20px; text-align: center; border-radius: 5px 5px 0 0; }
			.content { background-color: #f9f9f9; padding: 30px; border: 1px solid #ddd; border-radius: 0 0 5px 5px; }
			.otp-code { font-size: 32px; font-weight: bold; letter-spacing: 10px; text-align: center; padding: 20px; background-color: #fff; border: 2px dashed #4CAF50; margin: 20px 0; border-radius: 5px; }
			.warning { color: #d32f2f; font-size: 14px; margin-top: 20px; }
			.footer { text-align: center; margin-top: 20px; font-size: 12px; color: #777; }
		</style>
	</head>
	<body>
		<div class='container'>
			<div class='header'>
				<h2>Login Verification</h2>
			</div>
			<div class='content'>
				<p>Hello <strong>" . htmlspecialchars($username) . "</strong>,</p>
				<p>You are attempting to log in to your dashboard. Please use the verification code below to complete your login:</p>

				<div class='otp-code'>" . htmlspecialchars($otp_code) . "</div>

				<p><strong>Important:</strong></p>
				<ul>
					<li>This code will expire in <strong>10 minutes</strong></li>
					<li>Do not share this code with anyone</li>
					<li>If you did not request this code, please ignore this email</li>
				</ul>

				<p class='warning'>⚠️ If you did not attempt to log in, please secure your account immediately.</p>
			</div>
			<div class='footer'>
				<p>This is an automated message. Please do not reply to this email.</p>
			</div>
		</div>
	</body>
	</html>
	";

	$headers = "MIME-Version: 1.0" . "\r\n";
	$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
	$headers .= "From: Dashboard <noreply@" . $_SERVER['HTTP_HOST'] . ">" . "\r\n";

	// Send email
	return mail($email, $subject, $message, $headers);
}
