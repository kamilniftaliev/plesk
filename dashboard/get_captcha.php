<?php
session_name('DASHBOARD_SESSION');
session_start();
require_once '../includes/auth_validate.php';

// Generate captcha URL with timestamp
$timestamp = isset($_GET['t']) ? $_GET['t'] : time();
$random = isset($_GET['v']) ? $_GET['v'] : mt_rand() / mt_getrandmax();

$captcha_url = "https://buy.mi.com/en/other/getimage?_=" . $timestamp . "&v=" . $random;

// Cookie file path - unique per user session
$cookie_file = sys_get_temp_dir() . '/mi_cookies_' . session_id() . '.txt';

// Check if cURL is available
if (function_exists('curl_init')) {
    $ch = curl_init($captcha_url);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);

    // Cookie handling - save cookies from server
    curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie_file);
    curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie_file);

    // Headers to mimic browser
    $headers = [
        "User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36",
        "Accept: image/avif,image/webp,image/apng,image/svg+xml,image/*,*/*;q=0.8",
        "Accept-Language: en-US,en;q=0.9",
        "Referer: https://buy.mi.com/",
        "Accept-Encoding: gzip, deflate"
    ];

    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_ENCODING, "");

    $image_data = curl_exec($ch);
    $content_type = curl_getinfo($ch, CURLINFO_CONTENT_TYPE);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

    curl_close($ch);

    // Store cookie file path in session for later use
    $_SESSION['mi_cookie_file'] = $cookie_file;

    if ($http_code === 200 && $image_data) {
        // Set appropriate headers
        header('Content-Type: ' . $content_type);
        header('Cache-Control: no-cache, must-revalidate');
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
        echo $image_data;
    } else {
        // Return error image
        header('Content-Type: text/plain');
        http_response_code(500);
        echo 'Failed to load captcha';
    }
} else {
    // Fallback to file_get_contents
    $context = stream_context_create([
        'http' => [
            'method' => 'GET',
            'header' => "User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36\r\n" .
                "Accept: image/avif,image/webp,image/apng,image/*,*/*;q=0.8\r\n" .
                "Referer: https://buy.mi.com/\r\n",
            'timeout' => 10,
            'ignore_errors' => true
        ],
        'ssl' => [
            'verify_peer' => false,
            'verify_peer_name' => false
        ]
    ]);

    $image_data = @file_get_contents($captcha_url, false, $context);

    if ($image_data !== false) {
        header('Content-Type: image/png');
        header('Cache-Control: no-cache, must-revalidate');
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
        echo $image_data;
    } else {
        header('Content-Type: text/plain');
        http_response_code(500);
        echo 'Failed to load captcha';
    }
}
exit;
