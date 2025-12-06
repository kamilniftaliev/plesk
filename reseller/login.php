<?php
session_name('RESELLER_SESSION');
session_start();
$sekarang = date('Y-m-d H:i:s');
require_once 'config/config.php';
$token = bin2hex(openssl_random_pseudo_bytes(16));


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
				header('Location:login.php');
				exit;
			}

			$_SESSION['user_logged_in'] = TRUE;
			$_SESSION['admin_type'] = $row['status'];
			$_SESSION['admin_id'] = $row['id'];
			header('Location:index.php');
			exit;
		} else {
			clearAuthCookie();
			header('Location:login.php');
			exit;
		}
	} else {
		clearAuthCookie();
		header('Location:login.php');
		exit;
	}
}

include BASE_PATH . '/includes/login_header.php';
?>
<div id="page-" class="col-md-4 col-md-offset-4">
	<form class="form loginform" method="POST" action="authenticate.php">
		<input type="hidden" name="user_timezone" id="user_timezone" value="">
		<div class="login-panel panel panel-default">
			<div class="panel-heading">Reseller Login - Please Sign in</div>
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
					<input type="text" name="second_password" class="form-control" required="required">
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
</div>
<script>
	// Detect and set user's timezone
	document.addEventListener('DOMContentLoaded', function () {
		var timezone = Intl.DateTimeFormat().resolvedOptions().timeZone;
		document.getElementById('user_timezone').value = timezone;
	});
</script>
<?php include BASE_PATH . '/includes/footer.php'; ?>