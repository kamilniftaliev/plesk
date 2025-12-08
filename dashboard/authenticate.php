<?php



require_once './config/config.php';
session_name('DASHBOARD_SESSION');
session_start();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	$username = filter_input(INPUT_POST, 'username');
	$passwd = filter_input(INPUT_POST, 'passwd');
	$remember = filter_input(INPUT_POST, 'remember');

	//echo password_verify('admin', '$2y$10$RnDwpen5c8.gtZLaxHEHDOKWY77t/20A4RRkWBsjlPuu7Wmy0HyBu'); exit;

	//Get DB instance.
	$db = getDbInstance();

	$db->where("username", $username);

	$row = $db->get('user');

	// echo "<h2>Debug: \$row variable contents:</h2>";
	// echo "<h3>Type of \$row: " . gettype($row) . "</h3>";
	// echo "<h3>Size of \$row: " . (is_array($row) || is_object($row) ? count($row) : (is_string($row) ? strlen($row) : 'N/A')) . "</h3>";
	// echo "<pre>";
	// print_r($row);
	// echo "</pre>";
	// echo "<h3>Keys and Values:</h3>";
	// if (is_array($row)) {
	// 	foreach ($row as $key => $value) {
	// 		echo "<strong>Key:</strong> " . htmlspecialchars($key) . " => <strong>Value:</strong> ";
	// 		if (is_array($value)) {
	// 			echo "<pre>" . print_r($value, true) . "</pre>";
	// 		} else {
	// 			echo htmlspecialchars($value) . "<br>";
	// 		}
	// 	}
	// } else {
	// 	echo "Row is not an array: " . var_export($row, true);
	// }
	// exit;

	if ($db->count >= 1) {

		$db_password = $row[0]['password'];
		$user_id = $row[0]['id'];

		if (password_verify($passwd, $db_password)) {

			$_SESSION['dashboard_user_logged_in'] = TRUE;
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