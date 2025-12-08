<?php

require_once './config/config.php';
session_name('AUTHID_SESSION');
session_start();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	$username = filter_input(INPUT_POST, 'username');
	$passwd = filter_input(INPUT_POST, 'passwd');
	$remember = filter_input(INPUT_POST, 'remember');
	$second_password = filter_input(INPUT_POST, 'second_password');

	// Validate hour password (must match current hour in user's timezone)
	// Use the user's browser timezone if provided, otherwise use server timezone
	$user_timezone = filter_input(INPUT_POST, 'user_timezone') ?: date_default_timezone_get();

	try {
		$dt = new DateTime('now', new DateTimeZone($user_timezone));
		$current_hour = $dt->format('H'); // Get current hour in 24-hour format (00-23)
	} catch (Exception $e) {
		// If timezone is invalid, fall back to server timezone
		$current_hour = date('H');
	}

	if ($second_password !== $current_hour) {
		$_SESSION['login_failure'] = "Invalid second password. Please enter the correct second password.";
		header('Location:login.php');
		exit;
	}

	//echo password_verify('admin', '$2y$10$RnDwpen5c8.gtZLaxHEHDOKWY77t/20A4RRkWBsjlPuu7Wmy0HyBu'); exit;

	//Get DB instance.
	$db = getDbInstance();

	$db->where("username", $username);

	$row = $db->get('user');

	if ($db->count >= 1) {

		$db_password = $row[0]['password'];
		$user_id = $row[0]['id'];

		if (password_verify($passwd, $db_password)) {

			$_SESSION['user_logged_in'] = TRUE;
			$_SESSION['name'] = $row[0]['username'];
			$_SESSION['admin_id'] = $row[0]['id'];
			$_SESSION['admin_type'] = $row[0]['status'];
			//	$_SESSION['admin_sold'] = $row[0]['sold'];
			//	$_SESSION['admin_left'] = $row[0]['left_act'];

			if ($remember) {

				$series_id = randomString(16);
				$remember_token = getSecureRandomToken(20);
				$encryted_remember_token = password_hash($remember_token, PASSWORD_DEFAULT);


				$expiry_time = date('Y-m-d H:i:s', strtotime(' + 30 days'));

				$expires = strtotime($expiry_time);

				setcookie('series_id', $series_id, $expires, "/authid");
				setcookie('remember_token', $remember_token, $expires, "/authid");

				$db = getDbInstance();
				$db->where('id', $user_id);

				$update_remember = array(
					'series_id' => $series_id,
					'remember_token' => $encryted_remember_token,
					'expires' => $expiry_time
				);
				$db->update("user", $update_remember);
			}
			//Authentication successfull redirect user
			header('Location:index.php');

		} else {

			$_SESSION['login_failure'] = "Invalid user name or password";
			header('Location:login.php');
		}

		exit;
	} else {
		$_SESSION['login_failure'] = "Invalid user name or password";
		header('Location:login.php');
		exit;
	}

} else {
	die('Method Not allowed');
}