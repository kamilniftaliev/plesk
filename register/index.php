<?php
include_once('register.php');
$currentURL = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";


if (str_contains($currentURL, 'frp62')) {
  header("location:https://ah-tool.com/index.php");
}
session_start();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Cache-Control" content="no-store, no-cache, must-revalidate, max-age=0">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="0">
    <title> AH-TOOL User Registeration</title>
 
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.6.1.min.js" integrity="sha256-o88AwQnZB+VDvE9tvIXrMQaPlFFSUTR+nldQm1LuPXQ=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-OERcA2EqjJCMA+/3y+gxIOqMEjwtxJY7qPCqsdltbNJuaOe923+mo//f6V8Qbsw3" crossorigin="anonymous"></script>
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    <style>
        html, body{
            min-height:100%;
            width:100%;
        }
        .img-holder {
            text-align: center;
            height: 20vw;
            border: 1px solid;
            width: 100%;
            display: flex;
            justify-content: center;
            align-items: center;
            background: black;
        }
        .img-holder > img{
            max-height:calc(100%);
            max-width:calc(100%);
            object-fit:scale-down;
            object-position:center center;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary bg-gradient">
        <div class="container">
            <a class="navbar-brand" href="./">Register New User</a>
            <div>
                <a href="https://miazetool.com" class="text-light fw-bolder h6 text-decoration-none" target="_blank"></a>
            </div>
        </div>
    </nav>
    
    <div class="container-fluid px-5 my-3">
        <div class="col-lg-6 col-md-8 col-sm-12 mx-auto">
            <div class="card rounded-0 shadow">
                <div class="card-body">
                    <div class="container-fluid">
                        <?php if(isset($_SESSION['success_msg'])): ?>
                        <div class="alert alert-success rounded-0">
                            <?= $_SESSION['success_msg'] ?>
                        </div>
                        <style>
        .password-mismatch {
            border: 2px solid red;
        }
    </style>
                        <?php unset($_SESSION); ?>
                        <?php endif; ?>
                        <?php if(isset($error_msg)): ?>
                        <div class="alert alert-danger rounded-0">
                            <?= $error_msg ?>
                        </div>
                        
                        <?php unset($error_msg); ?>
                        <?php endif; ?>
                        
                        <form action="" method="POST">
                           
                            <div class="mb-3">
                                <label for="Username" class="form-label">Username</label>
                                <input type="text" class="form-control rounded-0" id="username" name="username" value="<?= (isset($_POST['username']) ? $_POST['username'] : "") ?>" required>
                            </div>
                            
                            
                            <div class="mb-3">
                                <label for="Username" class="form-label">Email</label>
                                <input type="email" class="form-control rounded-0" id="email" name="email" value="<?= (isset($_POST['email']) ? $_POST['email'] : "") ?>" required>
                            </div>
                            
                            
                            
                            <div class="mb-3">
                                <label for="middle_name" class="form-label">Password</label>
                                <input type="password" class="form-control rounded-0" id="password" name="password" value="<?= (isset($_POST['password']) ? $_POST['password'] : "") ?>" required>
                            </div>
                            <div class="mb-3">
                                <label for="last_name" class="form-label">Confirm Password</label>
                                <input type="password" class="form-control rounded-0" id="cpasswrod"  name="cpassword" value="<?= (isset($_POST['cpassword']) ? $_POST['cpassword'] : "") ?>" required>
                            </div>
                            <div class="mb-3">
                                <div class="g-recaptcha" data-sitekey="6LchFhYsAAAAAK9GfxkXYCDOJ5KQt2ulJy-VpSCM"></div>
                            </div>
                            <?php if(isset($err_captcha)): ?>
                            <div class="alert alert-danger rounded-0 mb-3">
                                <?= $err_captcha ?>
                            </div>
                            <?php unset($err_captcha); ?>
                            <?php endif; ?>
                            <div class="mb-3 d-grid">
                                <button class="btn btn-primary btn-block rounded-pill">Register</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>