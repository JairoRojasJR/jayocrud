<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../phpmailer/Exception.php';
require '../phpmailer/PHPMailer.php';
require '../phpmailer/SMTP.php';


function SendMail($recipient,$subject,$body)
{
  $mail = new PHPMailer(true);
  
  try {
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = USER_MAILER;
    $mail->Password = PASS_MAILER;
    $mail->SMTPSecure = 'tls';
    $mail->Port = 587;

    //Recipients
    $mail->setFrom('xdbable99@gmail.com', 'JayoCrud');
    $mail->addAddress($recipient);

    //Content
    $mail->isHTML(true);
    $mail->CharSet = 'UTF-8';
    $mail->Subject = $subject;
    $mail->Body    = $body;

    $mail->send();
    return ['success' => true, 'error' => null];
  } catch (Exception $e) {
    return ['success' => false, 'error' => "Message could not be sent. Mailer Error: {$mail->ErrorInfo}"];
  }
}
?>