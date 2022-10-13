<?php
function Register($userData, $connection)
{
  $inf = [
    'function' => 'Register',
    'success' => false
  ];

  // User Data;
  $name = $userData['name'];
  $lastname = $userData['lastname'];
  $username = $userData['username'];
  $email = $userData['email'];
  $password = $userData['password'];

  // Check if the user already exists
  $sql = "SELECT username FROM user WHERE username = '$username' LIMIT 1";
  $existUsername = $connection->query($sql);

  $sql = "SELECT email FROM user WHERE email LIKE '$email%' LIMIT 1";
  $existEmail = $connection->query($sql);

  if ($existUsername || $existEmail) {
    if ($existUsername && $existEmail) $alreadyExist = 'both';
    else if ($existUsername) $alreadyExist = 'username';
    else if ($existEmail) $alreadyExist = 'email';

    $inf['alreadyExist'] = $alreadyExist;
  } else {
    // Register unconfirmed user
    $date = new DateTime();
    $time = $date->getTimestamp();
    $emailUnconfirmed = $email . '&key=unconfirmed_' . $time . rand(1000, 10000);

    $sql = "INSERT INTO user (name, lastname, username, email, password)
						VALUES ('$name', '$lastname', '$username', '$emailUnconfirmed', '$password')";
    $connection->executedb($sql);

    // Send email to confirm
    $subject = 'Confirmar Usuario';
    $linkRegister = $_SERVER['HTTP_ORIGIN'] . '/sign_up/?emailToConfirm=' . $emailUnconfirmed;
    $body = 'Último paso para registrarse en JayoCrud.' . "\n\n" . 'Si usted no ha solicitado registrarse en el sitio web jayoweb.com ignore este mensaje' . "\n\n" . 'Haga click en el siguiente enlace para confirmar el registro: ' . $linkRegister;

    include('../phpmailer/SendMail.php');
    $statusSendMail = SendMail($email, $subject, $body);

    if ($statusSendMail['success']) $inf['success'] = true;
    else {
      include('../phpmailer/SendMail000wh.php');

      $statusSendMail000wh = SendMail000wh($email, $subject, $body);

      if ($statusSendMail000wh->success) $inf['success'] = true;
      else $inf['error'] = $statusSendMail000wh->error;
    }
  }
  return json_encode($inf);
}
