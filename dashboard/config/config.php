<?php

//Note: This file should be included first in every php page.
error_reporting(E_ALL);
ini_set('display_errors', 'On');
define('BASE_PATH', dirname(dirname(__FILE__)));
define('APP_FOLDER', 'simpleadmin');
define('CURRENT_PAGE', basename($_SERVER['REQUEST_URI']));

require_once BASE_PATH . '/lib/MysqliDb/MysqliDb.php';
require_once BASE_PATH . '/helpers/helpers.php';

/*
|--------------------------------------------------------------------------
| DEVELOPMENT MODE CONFIGURATION
|--------------------------------------------------------------------------
| Set to TRUE for local development (OTP will be displayed on screen)
| Set to FALSE for production (OTP will be sent via email/Telegram)
 */
define('DEV_MODE', true); // Change to false in production

/*
|--------------------------------------------------------------------------
| TELEGRAM BOT CONFIGURATION
|--------------------------------------------------------------------------
| Configure your Telegram Bot for OTP delivery
| Get your bot token from @BotFather on Telegram
 */
define('TELEGRAM_BOT_TOKEN', 'YOUR_BOT_TOKEN_HERE'); // Get from @BotFather
define('TELEGRAM_BOT_ENABLED', false); // Set to true to enable Telegram OTP

/*
|--------------------------------------------------------------------------
| DATABASE CONFIGURATION
|--------------------------------------------------------------------------
 */

define('DB_HOST', "localhost");
define('DB_USER', "u676821063_new2");
define('DB_PASSWORD', "!/F:6h[E9");
define('DB_NAME', "u676821063_new2");

/**
 * Get instance of DB object
 */
function getDbInstance() {
	return new MysqliDb(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
}