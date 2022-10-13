<?php

function recoverPassword($user, $connection)
{
  $inf = [
    'function' => 'recoverPassword',
    'success' => false
  ];

  $sql = "SELECT id, name, lastname, username, email, count_code_sends
          FROM user WHERE email = '$user' OR username = '$user'";
  $queryUser = $connection->query($sql);

  if (!empty($queryUser)) {
    $userData = $queryUser[0];
    $codesSent = $userData['count_code_sends'];
    $codesSent = $codesSent ? explode(':', $codesSent) : null;

    $queryDay = $codesSent ? $codesSent[0] : null;
    $queryCount = $codesSent ? (int) $codesSent[1] : 0;

    $currentDay = date('D');
    if ($currentDay === $queryDay && $queryCount >= 3) $inf['limitExceeded'] = true;
    else {
      $queryCount = $currentDay !== $queryDay ? 0 : $queryCount;
      // Datos del Usuario
      $id = (int)$userData['id'];
      $name = $userData['name'];
      $lastname = $userData['lastname'];
      $username = $userData['username'];
      $email = $userData['email'];

      // Creacion del link para la recuperacion
      $date = new DateTime();
      $time = $date->getTimestamp();
      $timeToMinutes = $time / 60;
      $expires = $timeToMinutes + 10;

      $key = rand(1000, 10000) . '_' . $time . '_' . rand(1000, 10000);
      $key .= '_u' . $user . 'u_' . rand(1000, 10000) . 'exp_' . $expires;

      $linkRecoverPassword = $_SERVER['HTTP_ORIGIN'] . '/recover/?key=' . $key;

      $sql = "UPDATE user SET password_recover = '$key' WHERE id = $id AND email = '$email'";
      $connection->executedb($sql);

      // Envio de email con el link
      include('../phpmailer/SendMail.php');
      $subject = 'Recuperación de Contraseña';
      $body = 'Hola ' . $name . ' ' . $lastname . ' has solicitado un enlace para poder restablecer la contraseña, si no has sido tú ignora este mensaje. El enlace expira en 10 minutos desde que se solicitó' . "\n\n" . 'Haga click en el siguiente link para restablecer su contraseña: ' . $linkRecoverPassword;

      $statusSendMail = SendMail($email, $subject, $body);

      function setUpdateCount($currentDay, $queryCount, $id, $email, $connection)
      {
        $updateCount = $currentDay . ':' . ($queryCount + 1);
        $sql = "UPDATE user SET count_code_sends = '$updateCount' WHERE id = $id AND email = '$email'";
        $connection->query($sql);
      }

      if ($statusSendMail['success']) {
        setUpdateCount($currentDay, $queryCount, $id, $email, $connection);
        $inf['success'] = true;
      } else {
        include('../phpmailer/SendMail000wh.php');
        
        $statusSendMail000wh = SendMail000wh($email, $subject, $body);
       
        if ($statusSendMail000wh->success) {
          setUpdateCount($currentDay, $queryCount, $id, $email, $connection);
          $inf['success'] = true;
        } else $inf['error'] = $statusSendMail000wh->error;
      }
    }
  } else $inf['userNotRegister'] = true;
  return json_encode($inf);
}
