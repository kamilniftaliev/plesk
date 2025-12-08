<?php

//If User is logged in the session['dashboard_user_logged_in'] will be set to true

//if user is Not Logged in, redirect to login.php page.
if (!isset($_SESSION['dashboard_user_logged_in'])) {
	header('Location:/dashboard/login.php');
}

?>