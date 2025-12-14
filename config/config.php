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
// define('DEV_MODE', value: false); // Change to false in production
define('DEV_MODE', value: false); // Change to false in production
define('URL_PREFIX', value: ""); // Change to false in production

/*
|--------------------------------------------------------------------------
| TELEGRAM BOT CONFIGURATION
|--------------------------------------------------------------------------
| Configure your Telegram Bot for OTP delivery
| Get your bot token from @BotFather on Telegram
 */
define('TELEGRAM_BOT_TOKEN', '8344834179:AAHraP-5KTdYMXmAOak6KnBAw0zl6Um4Shc'); // Get from @BotFather
// define('TELEGRAM_BOT_TOKEN', '7195967730:AAG8Q8FQkPdaHWGblLDDkZMKT4MkX3wp1c4'); // Get from @BotFather
define('TELEGRAM_BOT_ENABLED', true); // Set to true to enable Telegram OTP


// 2captcha API Configuration
define('TWOCAPTCHA_API_KEY', '7ffb03579cea7566b484f2b342a6ab3a'); // 2captcha.com'dan alacağınız API key'i buraya yazın

// 2captcha API endpoints
define('TWOCAPTCHA_API_URL', 'https://api.2captcha.com');
define('TWOCAPTCHA_CREATE_TASK_URL', TWOCAPTCHA_API_URL . '/createTask');
define('TWOCAPTCHA_GET_RESULT_URL', TWOCAPTCHA_API_URL . '/getTaskResult');


/*
|--------------------------------------------------------------------------
| DATABASE CONFIGURATION
|--------------------------------------------------------------------------
 */

define('DB_HOST', "localhost");

define('DB_USER', "u676821063_new2");
define('DB_PASSWORD', "!/F:6h[E9");
define('DB_NAME', "u676821063_new2");
// define('DB_USER', "admin_kevin");
// define('DB_PASSWORD', "5wq?7fHf_VnegTf2");
// define('DB_NAME', "admin_kevin");

/**
 * Get instance of DB object
 */
function getDbInstance()
{
	return new MysqliDb(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
}