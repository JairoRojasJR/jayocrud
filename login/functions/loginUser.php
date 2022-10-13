<?php
function loginUser($user, $password, $rememberUser, $connection)
{
  $inf = [
    'function' => 'loginUser',
    'success' => false,
    'error' => null
  ];

  $sql = "SELECT id, name, lastname, username, email, password
          FROM user WHERE (email = '$user' OR username = '$user') AND password = '$password'";
  $query = $connection->query($sql);

  if (!empty($query)) {
    $query = $query[0];

    $_SESSION['userData'] = array_filter($query, function ($k) {
      return gettype($k) == 'string';
    }, ARRAY_FILTER_USE_KEY);

    $user_id = $query['id'];

    if ($rememberUser === 'yes') {
      $ip_address = $_SERVER['REMOTE_ADDR'];
      $browser = $_SERVER['HTTP_USER_AGENT'];
      $sql = "UPDATE user SET remember = 'yes', ip_address = '$ip_address', browser = '$browser'
              WHERE id = '$user_id'";
    } else $sql = "UPDATE user SET remember = 'no' WHERE id = '$user_id'";

    $connection->executedb($sql);
    $inf['success'] = true;
    $inf['user_id'] = $query['id'];
  } else $inf['error'] = 'Usuario o contraseña incorrecto';
  return json_encode($inf);
}
