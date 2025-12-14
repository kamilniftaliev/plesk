<?php

//If User is logged in the session['dashboard_user_logged_in'] or session['user_logged_in'] will be set to true

//if user is Not Logged in, redirect to appropriate login.php page based on current URL

// Get current URL path
$current_path = $_SERVER['REQUEST_URI'];

// Check if we're in /authid directory
if (strpos($current_path, '/authid') === 0) {
	// We're in authid, check for authid session
	if (!isset($_SESSION['user_logged_in']) || $_SESSION['user_logged_in'] !== TRUE) {
		redirectToLogin('/authid/login.php', true);
	}
} else {
	// We're in dashboard or other area, check for dashboard session
	if (!isset($_SESSION['dashboard_user_logged_in']) || $_SESSION['dashboard_user_logged_in'] !== TRUE) {
		redirectToLogin('/dashboard/login.php', true);
	}
}

?>