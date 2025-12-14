<?php
require_once '../config/config.php';
session_name('DASHBOARD_SESSION');
session_start();
session_destroy();


if (isset($_COOKIE['series_id']) && isset($_COOKIE['remember_token'])) {
	clearAuthCookie();
}
$url_prefix = URL_PREFIX ?: '';
header('Location:' . $url_prefix . '/dashboard/index.php');
exit;

?>