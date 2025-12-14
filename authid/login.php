<?php
session_name('AUTHID_SESSION');
session_start();
$sekarang = date('Y-m-d H:i:s');
require_once '../config/config.php';
$token = bin2hex(openssl_random_pseudo_bytes(16));

$url_prefix = URL_PREFIX ?: '';

if (isset($_SESSION['user_logged_in']) && $_SESSION['user_logged_in'] === TRUE) {
	header('Location:index.php');
}

if (isset($_COOKIE['series_id']) && isset($_COOKIE['remember_token'])) {
	$series_id = filter_var($_COOKIE['series_id']);
	$remember_token = filter_var($_COOKIE['remember_token']);
	$db = getDbInstance();

	$db->where('series_id', $series_id);
	$row = $db->getOne('user');

	if ($db->count >= 1) {

		if (password_verify($remember_token, $row['remember_token'])) {

			$expires = strtotime($row['expires']);

			if (strtotime($sekarang) > $expires) {

				clearAuthCookie();
				header('Location:' . $url_prefix . '/authid/login.php');
				exit;
			}

			$_SESSION['user_logged_in'] = TRUE;
			$_SESSION['admin_type'] = $row['status'];
			$_SESSION['admin_id'] = $row['id'];
			header('Location:index.php');
			exit;
		} else {
			clearAuthCookie();
			header('Location:' . $url_prefix . '/authid/login.php');
			exit;
		}
	} else {
		clearAuthCookie();
		header('Location:' . $url_prefix . '/authid/login.php');
		exit;
	}
}

// Check if we're in OTP verification mode
$otp_mode = isset($_SESSION['otp_pending']) && $_SESSION['otp_pending'] === TRUE;

include './includes/login_header.php';
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
</style>
<div id="page-" class="col-md-4 col-md-offset-4">
	<?php if (!$otp_mode): ?>
		<!-- Login Form -->
		<form class="form loginform" method="POST"
			action="authenticate.php<?php echo isset($_GET['return_url']) ? '?return_url=' . urlencode($_GET['return_url']) : ''; ?>">
			<input type="hidden" name="user_timezone" id="user_timezone" value="">
			<div class="login-panel panel panel-default">
				<div class="panel-heading">Authid Login - Please Sign in</div>
				<div class="panel-body">
					<div class="form-group">
						<label class="control-label">Username</label>
						<input type="text" name="username" class="form-control" required="required">
					</div>
					<div class="form-group">
						<label class="control-label">First Password</label>
						<input type="password" name="passwd" class="form-control" required="required">
					</div>
					<div class="form-group">
						<label class="control-label">Second Password</label>
						<input type="password" name="second_password" class="form-control" required="required">
					</div>
					<div class="checkbox">
						<label>
							<input name="remember" type="checkbox" value="1">Remember Me
						</label>
					</div>
					<?php if (isset($_SESSION['login_failure'])): ?>
						<div class="alert alert-danger alert-dismissable fade in">
							<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
							<?php
							echo $_SESSION['login_failure'];
							unset($_SESSION['login_failure']);
							?>
						</div>
					<?php endif; ?>
					<button type="submit" class="btn btn-success loginField">Login</button>
					<a href="../forgot/index.php">Forgot Password</a>

				</div>
			</div>
		</form>
	<?php else: ?>
		<!-- OTP Verification Form -->
		<form class="form loginform" method="POST"
			action="authenticate.php<?php echo isset($_GET['return_url']) ? '?return_url=' . urlencode($_GET['return_url']) : ''; ?>">
			<div class="login-panel panel panel-default">
				<div class="panel-heading">Verify OTP</div>
				<div class="panel-body">
					<div class="otp-info">
						<strong>Security Check</strong><br>
						<?php if (defined('DEV_MODE') && DEV_MODE === true && isset($_SESSION['otp_dev_display'])): ?>
							<span style="color: #d32f2f; font-weight: bold;">🔧 DEVELOPMENT MODE</span><br>
							Your verification code is:<br>
							<div style="font-size: 32px; font-weight: bold; letter-spacing: 5px; margin: 10px 0; color: #2563eb;">
								<?php echo htmlspecialchars($_SESSION['otp_dev_display']); ?>
							</div>
							<small style="color: #666;">
								(In production, this would be sent to:
								<?php
								$delivery_methods = [];
								if (!empty($_SESSION['otp_email'])) {
									$delivery_methods[] = 'Email: ' . htmlspecialchars($_SESSION['otp_email']);
								}
								if (!empty($_SESSION['otp_telegram_chat_id'])) {
									$delivery_methods[] = 'Telegram (Chat ID: ' . htmlspecialchars($_SESSION['otp_telegram_chat_id']) . ')';
								}
								echo implode(' and ', $delivery_methods);
								?>)
							</small>
						<?php else: ?>
							A 4-digit verification code has been sent to:
							<div style="margin-top: 10px;">
								<?php if (!empty($_SESSION['otp_email'])): ?>
									<div style="margin: 5px 0;">
										📧 <strong>Email:</strong>
										<?php
										$email = $_SESSION['otp_email'];
										// Mask email: show 3 chars at start, 3 dots, 3 chars at end
										if (strlen($email) > 6) {
											$masked_email = substr($email, 0, 3) . '...' . substr($email, -3);
										} else {
											$masked_email = $email; // Show full email if too short
										}
										echo htmlspecialchars($masked_email);
										?>
									</div>
								<?php endif; ?>
								<?php if (!empty($_SESSION['otp_telegram_chat_id'])): ?>
									<div style="margin: 5px 0;">
										💬 <strong>Telegram:</strong> Check your Telegram app
									</div>
								<?php endif; ?>
							</div>
						<?php endif; ?>
					</div>

					<div class="form-group">
						<label class="control-label">Enter 4-Digit Code</label>
						<input type="text" name="otp_code" class="form-control otp-input" maxlength="4" pattern="\d{4}"
							placeholder="0000" required="required" autocomplete="off" autofocus>
						<input type="hidden" name="verify_otp" value="1">
					</div>

					<?php if (isset($_SESSION['otp_failure'])): ?>
						<div class="alert alert-danger alert-dismissable fade in">
							<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
							<?php
							echo $_SESSION['otp_failure'];
							unset($_SESSION['otp_failure']);
							?>
						</div>
					<?php endif; ?>

					<?php if (isset($_SESSION['otp_success'])): ?>
						<div class="alert alert-success alert-dismissable fade in">
							<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
							<?php
							echo $_SESSION['otp_success'];
							unset($_SESSION['otp_success']);
							?>
						</div>
					<?php endif; ?>

					<button type="submit" class="btn btn-success btn-block">Verify Code</button>

					<div style="text-align: center; margin-top: 15px;">
						<a href="authenticate.php?resend_otp=1<?php echo isset($_GET['return_url']) ? '&return_url=' . urlencode($_GET['return_url']) : ''; ?>"
							class="resend-otp-link">Resend Code</a>
						&nbsp;|&nbsp;
						<a href="authenticate.php?cancel_otp=1<?php echo isset($_GET['return_url']) ? '&return_url=' . urlencode($_GET['return_url']) : ''; ?>"
							class="resend-otp-link">Cancel</a>
					</div>
				</div>
			</div>
		</form>
	<?php endif; ?>
</div>
<script>
	// Detect and set user's timezone
	document.addEventListener('DOMContentLoaded', function () {
		<?php if (!$otp_mode): ?>
			var timezone = Intl.DateTimeFormat().resolvedOptions().timeZone;
			var timezoneInput = document.getElementById('user_timezone');
			if (timezoneInput) {
				timezoneInput.value = timezone;
			}
		<?php endif; ?>
	});

	// Auto-submit OTP form when 4 digits are entered
	<?php if ($otp_mode): ?>
		document.addEventListener('DOMContentLoaded', function () {
			var otpInput = document.querySelector('input[name="otp_code"]');
			if (otpInput) {
				otpInput.addEventListener('input', function (e) {
					// Only allow digits
					this.value = this.value.replace(/[^0-9]/g, '');

					// Auto-submit when 4 digits are entered
					if (this.value.length === 4) {
						// Optional: add a small delay for better UX
						setTimeout(function () {
							document.querySelector('.loginform').submit();
						}, 300);
					}
				});
			}
		});
	<?php endif; ?>
</script>
<?php include BASE_PATH . '/includes/footer.php'; ?>