<?php
require_once './config/config.php';
session_name('DASHBOARD_SESSION');
session_start();
session_destroy();


if(isset($_COOKIE['series_id']) && isset($_COOKIE['remember_token'])){
	clearAuthCookie();
}
echo 'Status : Session logged out successfully. <br>';

 ?>