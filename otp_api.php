<?php
// otp_api.php

// Assuming that the OTP is stored or generated somewhere on the server
$valid_otp = "123000"; // Predefined valid OTP (for demonstration)

// Check if the action is 'validate' and OTP is provided
if (isset($_POST['action']) && $_POST['action'] == 'validate' && isset($_POST['otp']) && isset($_POST['secret_key']) && $_POST['secret_key'] == 'BUTZ6nbmcczkbjaG23ZVWg0GU1ywQi0Q') 
{
    $secret = $_POST['secret_key'];
    $otp = $_POST['otp'];

    // Validate the OTP
    if ($otp == $valid_otp) {
        $response = [
            'status' => 'success',
            'message' => 'OTP Valid Ok.'
        ];
    } else {
        $response = [
            'status' => 'error',
            'message' => 'Invalid OTP.'
        ];
    }
} else {
    // In case of missing parameters
    $response = [
        'status' => 'error',
        'message' => 'Invalid request.'
    ];
}

// Send response as JSON
header('Content-Type: application/json');
echo json_encode($response);
?>  