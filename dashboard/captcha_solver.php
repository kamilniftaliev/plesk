<?php
session_name('DASHBOARD_SESSION');
session_start();
require_once './config/config.php';
require_once 'includes/auth_validate.php';

header('Content-Type: application/json');

$TWOCAPTCHA_API_KEY = '7ffb03579cea7566b484f2b342a6ab3a';

// Handle captcha submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'submit') {
  // Check if captcha image file was uploaded
  if (!isset($_FILES['captcha_image']) || $_FILES['captcha_image']['error'] !== UPLOAD_ERR_OK) {
    echo json_encode(['success' => false, 'error' => 'No captcha image uploaded']);
    exit;
  }

  if (function_exists('curl_init')) {
    // Get the uploaded file
    $uploaded_file = $_FILES['captcha_image']['tmp_name'];

    // Upload to 2captcha using multipart/form-data
    $post_data = [
      'method' => 'post',
      'key' => $TWOCAPTCHA_API_KEY,
      'file' => new CURLFile($uploaded_file, $_FILES['captcha_image']['type'], 'captcha.jpg')
    ];

    $ch = curl_init('https://2captcha.com/in.php');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);

    $response = curl_exec($ch);
    curl_close($ch);

    if (!$response) {
      echo json_encode(['success' => false, 'error' => 'Failed to submit to 2captcha']);
      exit;
    }

    // Parse response: "OK|123456" or "ERROR_..."
    $parts = explode('|', trim($response));
    if ($parts[0] === 'OK' && isset($parts[1])) {
      echo json_encode([
        'success' => true,
        'captcha_id' => $parts[1]
      ]);
    } else {
      echo json_encode([
        'success' => false,
        'error' => $response
      ]);
    }
  } else {
    echo json_encode(['success' => false, 'error' => 'cURL not available']);
  }
  exit;
}

// Handle captcha result retrieval
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['action']) && $_GET['action'] === 'get') {
  $captcha_id = $_GET['captcha_id'] ?? '';

  if (empty($captcha_id)) {
    echo json_encode(['success' => false, 'error' => 'Captcha ID required']);
    exit;
  }

  $url = "https://2captcha.com/res.php?key=$TWOCAPTCHA_API_KEY&action=get&id=$captcha_id";

  if (function_exists('curl_init')) {
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);

    $response = curl_exec($ch);
    curl_close($ch);

    if (!$response) {
      echo json_encode(['success' => false, 'error' => 'Failed to get result from 2captcha']);
      exit;
    }

    // Parse response: "OK|CODE" or "CAPCHA_NOT_READY" or "ERROR_..."
    $parts = explode('|', trim($response));
    if ($parts[0] === 'OK' && isset($parts[1])) {
      echo json_encode([
        'success' => true,
        'code' => $parts[1]
      ]);
    } elseif (trim($response) === 'CAPCHA_NOT_READY') {
      echo json_encode([
        'success' => false,
        'pending' => true,
        'message' => 'Captcha not ready yet'
      ]);
    } else {
      echo json_encode([
        'success' => false,
        'error' => $response
      ]);
    }
  } else {
    echo json_encode(['success' => false, 'error' => 'cURL not available']);
  }
  exit;
}

echo json_encode(['success' => false, 'error' => 'Invalid request']);
