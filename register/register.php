<?php
require_once("db-connect.php");

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    extract($_POST);
    $grr = isset($_POST['g-recaptcha-response']) ? $_POST['g-recaptcha-response'] : '';

    if (empty($grr)) {
        $err_captcha = "Kindly check reCaptcha Checkbox before submitting the form.";
    } else {
        // Validate Google reCaptcha
        $secret = "6LchFhYsAAAAABzum3gWF2wjfTSjWcO7m5_kv2JG";
        $status = "user";
        $credit = "0";

        $http_query = http_build_query(["response" => $grr, "secret" => $secret]);
        $ch = curl_init("https://www.google.com/recaptcha/api/siteverify?" . $http_query);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $result = curl_exec($ch);
        curl_close($ch);

        $response = (array) json_decode($result);

        if (isset($response['success']) && $response['success']) {
            
            $checkUsernameQuery = "SELECT * FROM `user` WHERE `username` = '$username'";
            $checkUsernameResult = $conn->query($checkUsernameQuery);
            
            $checkEmailQuery = "SELECT * FROM `user` WHERE `email` = '$email'";
            $checkEmailResult = $conn->query($checkEmailQuery);

            $checkanu = "SELECT * FROM `user` WHERE `email` = '$username'";
            $checkanuResult = $conn->query($checkanu);



            if ($checkUsernameResult->num_rows > 0 ) {
                $error_msg = "Username already exists";
          
            }
            elseif ($checkEmailResult->num_rows > 0) {
                $error_msg = "Email already exists";
                
            }
            elseif ($checkanuResult->num_rows > 0) {
                $error_msg = "username /email already exists";
                
            }
           else {

               $rand = "AH-TOOL-" . generateRandomString(8);
                if ($password === $cpassword) {
                    $hashedPassword = password_hash($password, PASSWORD_DEFAULT); // Hash the password

                    $insertQuery = "INSERT INTO `user` (`username`, `password`, `status`, `email`,apikey)
                                    VALUES ('$username', '$hashedPassword', '$status','$email', '$rand')";

                    $insert = $conn->query($insertQuery);

                    if ($insert) {
                        $message = "Username [" . $username . "] Has Been Registered";
                        telebot($message);
                        $success_msg = "New user registration successful.";
                        header('location: success.html');
                        exit;
                    } else {
                        $error_msg = "There's an error occurred while saving the data: " . $conn->error;
                    }
                } else {
                    $error_msg = "Password does not match.";
                }
            }
        } else {
            $err_captcha = "reCaptcha Validation Failed. <br>";

            if (isset($response['error-codes']) && count($response['error-codes'])) {
                $err_captcha .= "<ul>";

                foreach ($response['error-codes'] as $error) {
                    $err_captcha .= "<li>{$error}</li>";
                }

                $err_captcha .= "</ul>";
            }

            $err_captcha .= "Please check the checkbox.";
        }
    }
}

    function generateRandomString($length = 8) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';

    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[random_int(0, $charactersLength - 1)];
    }

    return $randomString;
}
function telebot($message) {
$token = "76944303:AAF3dNoD07QIcUqony9ti_CPz7i62w32qAE";
$chatid = "-10023834691";
$url = 'https://api.telegram.org/bot76944303:AAF3dNoD07QIcUqony9ti_CPz7i62w32qAE/sendMessage?chat_id=-10023834691&text='.urlencode($message)  ;
$result = file_get_contents($url, false, $context);
if ($result === false) {
    /* Handle error */
}
return $result;
}


?>
