<?php
use PHPMailer\PHPMailer\PHPMailer;
require 'vendor/autoload.php';
$mail = new PHPMailer;
$mail->isSMTP();
$mail->SMTPDebug = 0;
$mail->Host = 'smtp.hostinger.com';
$mail->Port = 587;
$mail->SMTPAuth = true;
$mail->Username = 'admin@azegsm.com';
$mail->Password = 'Zahr@666';
$mail->setFrom('admin@azegsm.com', 'Admin');
$mail->addReplyTo('admin@azegsm.com', 'Admin');
$mail->addAddress('azegsm.com', 'azegsm.com');
$mail->Subject = 'Testing PHPMailer';
$mail->msgHTML(file_get_contents('message.html'), __DIR__);
$mail->Body = 'This is a plain text message body';
//$mail->addAttachment('test.txt');
if (!$mail->send()) {
echo 'Mailer Error: ' . $mail->ErrorInfo;
} else {
echo 'The email message was sent.';
}
?>