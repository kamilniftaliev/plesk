<?php
session_name('DASHBOARD_SESSION');
session_start();
include '/includes/konak.php';
require_once '../includes/auth_validate.php';
require 'vendor/autoload.php';
use PHPMailer\PHPMailer\PHPMailer;

// Check permission for this page
requirePermission('transfer_credit');





$statusUser = getCurrentUserType();
$admin_user_id = $_SESSION['admin_id'];
$name = isset($_SESSION['name']) ? $_SESSION['name'] : '';
$db = getDbInstance();
$db->where('id', $admin_user_id);
$row = $db->get('user');
$res = $row[0]['username'];
$cre = $row[0]['credit'];

$name = $name ? strtolower($name) : '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $jumlah = filter_input(INPUT_POST, 'jumlah', FILTER_SANITIZE_SPECIAL_CHARS);
    $uname = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_SPECIAL_CHARS);
    $ispay = filter_input(INPUT_POST, 'ispay', FILTER_SANITIZE_SPECIAL_CHARS);
    $ispay = ($ispay === '1' || $ispay === 1) ? 1 : 0; // Normalize to 0 or 1

    if ($statusUser == 'reseller') {
        if ($cre < $jumlah) {
            $message = "Reseller Name " . $name . "\n";
            $message .= "Failed Refill Credit To " . $uname . "\n";
            $message .= "Reseller Credit Not Enough , Credit Left " . $cre . "\n";
            telebot($message);
            $_SESSION['failure'] = "Failed to Refill Credit , your credit left " . $cre;
            header('location: transfer_credit.php');
            exit;


        }




        $db = getDbInstance();
        $db->where("username", $uname);
        $numTotalreseller = $db->getValue("user", "count(*)");


        if ($numTotalreseller > 0) {

            $db = getDbInstance();
            $db->where("username", $uname);

            $row = $db->get('user');

            $creditawal = $row[0]['credit'];
            $email = $row[0]['email'];
            $usernametujuan = $row[0]['username'];

            if ($usernametujuan == $name) {
                telebot("Cant Refil To your Self " . $name);
                $_SESSION['failure'] = "Cant Transfer Credit To Your Self";
                header('location: transfer_credit.php');
                exit;


            }


            $totalcredit = $creditawal + $jumlah;
            $data_to_update = [
                'credit' => $totalcredit,
            ];

            $db->where("username", $uname);
            $stat = $db->update('user', $data_to_update);
            if ($stat) {
                $_SESSION['success'] = "Refil Credit successfully";
                $sqls = "INSERT INTO penjualancredit (jumlah,email,resellerid,resellername,ispay) VALUES ('$jumlah','$uname','$admin_user_id','$name','$ispay')";


                if (mysqli_query($koneksi, $sqls)) {
                    $creres = $cre - $jumlah;
                    $data_to_update = ['credit' => $creres];
                    $db = getDbInstance();
                    $db->where('id', $admin_user_id);
                    $stat = $db->update('user', $data_to_update);

                    if ($stat) {
                        $db = getDbInstance();
                        $db->where("username", $uname);
                        $row = $db->get('user');
                        $creditfinal = $row[0]['credit'];
                        $message = "Succes Refil Credit " . "\n";
                        $message .= "Resseler name " . $name . "\n";
                        $message .= "Resseler Credit " . $cre . "\n";
                        $message .= "Resseler Credit Left " . $creres . "\n";
                        $message .= "username " . $uname . "\n";
                        $message .= "Last Credit " . $creditawal . "\n";
                        $message .= "Amount " . $jumlah . "\n";
                        $message .= "New Credits User " . $creditfinal . "\n";
                        telebot($message);
                    } else {
                        $message = "Failed Refil Credit" . "\n";
                        $message .= "Resseler name " . $name . "\n";
                        $message .= "username " . $uname . "\n";
                        $message .= "Amount " . $jumlah . "\n";
                        telebot($message);
                    }

                    if ($email != "") {

                        SenDemail($email, $jumlah, $name);

                    }

                } else {
                    $message = "Failed Refil Credit" . "\n";
                    $message .= "Resseler name " . $name . "\n";
                    $message .= "Amount " . $jumlah . "\n";
                    $message .= "Please CHECK...!!!\n";
                    telebot($message);

                }

            } else {
                $_SESSION['failure'] = "Failed to Refill Credit : " . $db->getLastError();
            }



            header('location: transfer_credit.php');
            exit;


        }
        $db = getDbInstance();

        $db->where("email", $uname);
        $numTotalreseller = $db->getValue("user", "count(*)");
        if ($numTotalreseller > 0) {
            $row = $db->get('user');
            $creditawal = $row[0]['credit'];
            $email = $row[0]['email'];


            $usernametujuan = $row[0]['username'];

            if ($usernametujuan == $name) {
                telebot("Cant Refil To your Self " . $name);
                $_SESSION['failure'] = "Cant Transfer Credit To Your Self";
                header('location: transfer_credit.php');
                exit;


            }




            $totalcredit = $creditawal + $jumlah;

            $data_to_update = [
                'credit' => $totalcredit,
            ];
            $db = getDbInstance();
            $db->where("email", $uname);
            $stat = $db->update('user', $data_to_update);



            if ($stat) {
                $_SESSION['success'] = "Refil Credit successfully";
                $sqls = "INSERT INTO penjualancredit (jumlah,email,resellerid,resellername,ispay) VALUES ('$jumlah','$uname','$admin_user_id','$name','$ispay')";
                if (mysqli_query($koneksi, $sqls)) {
                    $creres = $cre - $jumlah;
                    $data_to_update = ['credit' => $creres];
                    $db = getDbInstance();
                    $db->where('id', $admin_user_id);
                    $stat = $db->update('user', $data_to_update);

                    if ($stat) {
                        $db = getDbInstance();
                        $db->where("email", $uname);
                        $row = $db->get('user');
                        $creditfinal = $row[0]['credit'];
                        $message = "Succes Refil Credit " . "\n";
                        $message .= "Resseler name " . $name . "\n";
                        $message .= "Resseler Credit " . $cre . "\n";
                        $message .= "Resseler Credit Left " . $creres . "\n";
                        $message .= "username " . $uname . "\n";
                        $message .= "Last Credit " . $creditawal . "\n";
                        $message .= "Amount " . $jumlah . "\n";
                        $message .= "New Credits User " . $creditfinal . "\n";
                        telebot($message);
                    } else {
                        $message = "Failed Refil Credit" . "\n";
                        $message .= "Resseler name " . $name . "\n";
                        $message .= "username " . $uname . "\n";
                        $message .= "Amount " . $jumlah . "\n";
                        telebot($message);
                    }

                    if ($email != "") {

                        SenDemail($email, $jumlah, $name);

                    }

                } else {
                    $message = "Failed Refil Credit" . "\n";
                    $message .= "Resseler name " . $name . "\n";
                    $message .= "Amount " . $jumlah . "\n";
                    $message .= "Please CHECK...!!!\n";
                    telebot($message);

                }




            } else {
                $_SESSION['failure'] = "Failed to Refill Credit : " . $db->getLastError();
            }

            header('location: transfer_credit.php');
            exit;


        }



    }




    if ($statusUser == 'admin') {



        $uname = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_SPECIAL_CHARS);
        $jumlah = filter_input(INPUT_POST, 'jumlah', FILTER_SANITIZE_SPECIAL_CHARS);

        $pagelimit = 200;

        $resellerid = $admin_user_id;
        $page = 1;


        $db = getDbInstance();
        $select = array('resellerid', 'jumlah');

        $total = 0;

        $db->where('resellerid', $resellerid)->where('ispay', '0');
        $db->pageLimit = $pagelimit;

        $rows = $db->arraybuilder()->paginate('penjualancredit', $page, $select);

        foreach ($rows as $row):
            $total += $row['jumlah'];
        endforeach;











        $db = getDbInstance();
        $db->where("username", $uname);
        $numTotalreseller = $db->getValue("user", "count(*)");


        if ($numTotalreseller > 0) {

            $db = getDbInstance();
            $db->where("username", $uname);

            $row = $db->get('user');

            $creditawal = $row[0]['credit'];
            $email = $row[0]['email'];

            $totalcredit = (int) $creditawal + (int) $jumlah;
            $data_to_update = [
                'credit' => $totalcredit,
            ];

            $db->where("username", $uname);
            $stat = $db->update('user', $data_to_update);
            if ($stat) {
                $_SESSION['success'] = "Refil Credit successfully";
                $sqls = "INSERT INTO penjualancredit (jumlah,email,resellerid,resellername,ispay) VALUES ('$jumlah','$uname','$admin_user_id','$name','$ispay')";
                if (mysqli_query($koneksi, $sqls)) {

                    $db = getDbInstance();
                    $db->where("username", $uname);
                    $row = $db->get('user');
                    $creditfinal = $row[0]['credit'];
                    $message = "Succes Refil Credit" . "\n";
                    $message .= "Resseler name " . $name . "\n";
                    $message .= "username " . $uname . "\n";
                    $message .= "Last Credit " . $creditawal . "\n";

                    $message .= "Amount " . $jumlah . "\n";
                    $message .= "New Credits " . $creditfinal . "\n";
                    telebot($message);
                    if ($email != "") {

                        SenDemail($email, $jumlah, $name);

                    }

                } else {
                    $message = "Failed Refil Credit" . "\n";
                    $message .= "Resseler name " . $name . "\n";
                    $message .= "Amount " . $jumlah . "\n";
                    $message .= "Please CHECK...!!!\n";
                    telebot($message);

                }

            } else {
                $_SESSION['failure'] = "Failed to Refill Credit : " . $db->getLastError();
            }



            header('location: transfer_credit.php');
            exit;


        }





        $db = getDbInstance();
        $db->where('email', $uname);
        $row = $db->getOne('user');
        if ($row == 0) {


            $_SESSION['failure'] = "username/email un registered : " . $db->getLastError();
            header('location: transfer_credit.php');
            exit;
        }
        if ($row > 1) {


            $db = getDbInstance();
            $db->where("email", $uname);
            $row = $db->get('user');
            $creditawal = $row[0]['credit'];

            telebot($creditawal);
            $totalcredit = (int) $creditawal + (int) $jumlah;

            $data_to_update = [
                'credit' => $totalcredit,
            ];
            $db = getDbInstance();
            $db->where("email", $uname);
            $stat = $db->update('user', $data_to_update);



            if ($stat) {
                $_SESSION['success'] = "Refil Credit successfully";
                $sqls = "INSERT INTO penjualancredit (jumlah,email,resellerid,resellername,ispay) VALUES ('$jumlah','$uname','$admin_user_id','$name','$ispay')";
                if (mysqli_query($koneksi, $sqls)) {
                    $db->where("email", $uname);
                    $row = $db->get('user');
                    $creditakhir = $row[0]['credit'];


                    $message = "Succes Refil Credit" . "\n";
                    $message .= "Resseler name " . $name . "\n";
                    $message .= "email " . $uname . "\n";
                    $message .= "Last Credit " . $creditawal . "\n";
                    $message .= "Amount " . $jumlah . "\n";
                    $message .= "New Credit " . $creditakhir . "\n";
                    telebot($message);
                    if ($email != "") {

                        SenDemail($email, $jumlah, $name);

                    }

                } else {
                    $message = "Failed Refil Credit" . "\n";
                    $message .= "Resseler name " . $name . "\n";
                    $message .= "Amount " . $jumlah . "\n";
                    $message .= "Please CHECK...!!!\n";
                    telebot($message);




                }




            } else {
                $_SESSION['failure'] = "Failed to Refill Credit : " . $db->getLastError();
            }

            header('location: transfer_credit.php');
            exit;


        } else {
            $_SESSION['failure'] = "email  registered More Than one";
            header('location: transfer_credit.php');
            exit;


        }


        $_SESSION['failure'] = "username/email un registered : " . $db->getLastError();
        header('location: transfer_credit.php');
        exit;

    }
}


$db = getDbInstance();
$db->where('id', $admin_user_id);

$admin_account = $db->getOne("user");

require_once '../includes/header.php';

?>
<div id="page-wrapper">

    <div class="row">
        <h1 class="page-header">Reseller Panel</h1>
    </div>
    <?php include_once 'includes/flash_messages.php'; ?>
    <form class="well form-horizontal" action="" method="post" id="contact_form" enctype="multipart/form-data">
        <?php include_once '../forms/transfer_credit_form.php'; ?>
    </form>
</div>




<?php

function telebot($message)
{
    $token = "7983294324:AAEHkVlUNGdOnW-OCUd6S-uZ1IGnjz79nls";
    $chatid = "6694680335";
    $url = 'https://api.telegram.org/bot7983294324:AAEHkVlUNGdOnW-OCUd6S-uZ1IGnjz79nls/sendMessage?chat_id=6694680335&text=' . urlencode($message);
    $result = file_get_contents($url, false, $context);
    if ($result === false) {
        /* Handle error */
    }
    return $result;
}


function SenDemail($email, $amount, $reseller)
{


    $mail = new PHPMailer;
    $mail->isSMTP();
    $mail->SMTPDebug = 0;
    $mail->Host = 'smtp.hostinger.com';
    $mail->Port = 587;
    $mail->SMTPAuth = true;
    $mail->Username = 'admin@azegsm.com';
    $mail->Password = 'okok';
    $mail->setFrom('admin@azegsm.com', 'Credit AzeGsm');
    $mail->addReplyTo('admin@azegsm.com', 'Credit Azegsm');
    $mail->addAddress($email, 'Customer');
    $mail->Subject = 'Credit Refill';
    $mail->msgHTML(file_get_contents('message.html'), __DIR__);
    $mail->Body = htmlspecialchars('Your Account ' . $email . ' on AzeGsm Auth Has Been top upped ' . $amount . ' Credit' . " Sold By " . $reseller);
    //$mail->addAttachment('test.txt');
    if (!$mail->send()) {
        echo 'Mailer Error: ' . $mail->ErrorInfo;
    } else {






    }

}

include_once '../includes/footer.php'; ?>