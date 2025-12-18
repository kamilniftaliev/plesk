<?php

// Include config first (which includes permissions.php)
require_once __DIR__ . '/../config/config.php';

//If User is logged in the session['user_logged_in'] will be set to true

//if user is Not Logged in, redirect to login.php page

// Check if user is logged in
if (!isset($_SESSION['user_logged_in']) || $_SESSION['user_logged_in'] !== TRUE) {
	redirectToLogin('/dashboard/login.php', true);
}
