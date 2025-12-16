<?php
session_start();
require_once '../config/config.php';
require_once '../includes/header.php';

// Check if in reset mode (code sent)
$reset_mode = isset($_SESSION['reset_pending']) && $_SESSION['reset_pending'];
?>

<style>
	.resend-reset-link {
		color: #337ab7;
		text-decoration: underline;
	}

	@media (prefers-color-scheme: dark) {
		.resend-reset-link {
			color: #60a5fa;
		}
	}
</style>

<div id="page-" class="col-md-4 col-md-offset-4">
	<?php if (!$reset_mode): ?>
		<!-- Request Reset Code Form -->
		<form class="form loginform" method="POST" action="request.php">
			<div class="login-panel panel panel-default">
				<div class="panel-heading">Forgot Password</div>
				<div class="panel-body">
					<div class="form-group">
						<label class="control-label">Enter your email address</label>
						<input type="email" name="email" class="form-control" required="required"
							placeholder="your@email.com">
						<small style="color: #666; display: block; margin-top: 5px;">
							We'll send you a 4-digit code to reset your password
						</small>
					</div>

					<?php if (isset($_SESSION['reset_error'])): ?>
						<div class="alert alert-danger alert-dismissable fade in">
							<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
							<?php
							echo $_SESSION['reset_error'];
							unset($_SESSION['reset_error']);
							?>
						</div>
					<?php endif; ?>

					<?php if (isset($_SESSION['reset_success'])): ?>
						<div class="alert alert-success alert-dismissable fade in">
							<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
							<?php
							echo $_SESSION['reset_success'];
							unset($_SESSION['reset_success']);
							?>
						</div>
					<?php endif; ?>

					<button type="submit" class="btn btn-success btn-block">Send Reset Code</button>

					<div style="text-align: center; margin-top: 15px;">
						<a href="../dashboard/login.php">Back to Login</a>
					</div>
				</div>
			</div>
		</form>
	<?php else: ?>
		<!-- Reset Code Verification Form -->
		<form class="form loginform" method="POST" action="request.php">
			<div class="login-panel panel panel-default">
				<div class="panel-heading">Reset Your Password</div>
				<div class="panel-body">
					<div style="margin-bottom: 20px; padding: 15px; background-color: #f0f0f0; border-radius: 5px;">
						<?php if (isset($_SESSION['reset_dev_display'])): ?>
							<strong>🔧 DEV MODE:</strong> Your reset code is:
							<div style="font-size: 24px; font-weight: bold; color: #4CAF50; margin-top: 10px; text-align: center;">
								<?php echo htmlspecialchars($_SESSION['reset_dev_display']); ?>
							</div>
							<small style="display: block; margin-top: 10px; color: #666;">
								(In production mode, this code will be sent via
								<?php
								$delivery_methods = [];
								if (!empty($_SESSION['reset_email_available'])) {
									$delivery_methods[] = 'Email';
								}
								if (!empty($_SESSION['reset_telegram_chat_id'])) {
									$delivery_methods[] = 'Telegram';
								}
								echo implode(' and ', $delivery_methods);
								?>)
							</small>
						<?php else: ?>
							A 4-digit reset code has been sent to:
							<div style="margin-top: 10px;">
								<?php if (!empty($_SESSION['reset_email_available'])): ?>
									<div style="margin: 5px 0;">
										📧 <strong>Email:</strong>
										<?php
										$email = $_SESSION['reset_email'];
										// Mask email: show 5 chars at start, 3 dots, 5 chars at end
										if (strlen($email) > 13) {
											$masked_email = substr($email, 0, 5) . '...' . substr($email, -5);
										} else {
											$masked_email = $email;
										}
										echo htmlspecialchars($masked_email);
										?>
									</div>
								<?php endif; ?>
								<?php if (!empty($_SESSION['reset_telegram_chat_id'])): ?>
									<div style="margin: 5px 0;">
										💬 <strong>Telegram:</strong> Check your Telegram app
									</div>
								<?php endif; ?>
							</div>
						<?php endif; ?>
					</div>

					<div class="form-group">
						<label class="control-label">Reset Code</label>
						<input type="text" name="reset_code" class="form-control" required="required"
							pattern="[0-9]{4}" maxlength="4" placeholder="Enter 4-digit code"
							style="font-size: 24px; letter-spacing: 5px; text-align: center;">
					</div>

					<div class="form-group">
						<label class="control-label">New Password</label>
						<input type="password" name="new_password" class="form-control" required="required"
							minlength="6">
					</div>

					<div class="form-group">
						<label class="control-label">Confirm New Password</label>
						<input type="password" name="confirm_password" class="form-control" required="required"
							minlength="6">
					</div>

					<?php if (isset($_SESSION['reset_error'])): ?>
						<div class="alert alert-danger alert-dismissable fade in">
							<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
							<?php
							echo $_SESSION['reset_error'];
							unset($_SESSION['reset_error']);
							?>
						</div>
					<?php endif; ?>

					<?php if (isset($_SESSION['reset_success'])): ?>
						<div class="alert alert-success alert-dismissable fade in">
							<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
							<?php
							echo $_SESSION['reset_success'];
							unset($_SESSION['reset_success']);
							?>
						</div>
					<?php endif; ?>

					<button type="submit" class="btn btn-success btn-block">Reset Password</button>

					<div style="text-align: center; margin-top: 15px;">
						<a href="request.php?cancel_reset=1" class="resend-reset-link">Cancel</a>
					</div>
				</div>
			</div>
		</form>
	<?php endif; ?>
</div>

<?php include BASE_PATH . '/includes/footer.php'; ?>
