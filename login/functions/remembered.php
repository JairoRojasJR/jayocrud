<?php
function loginUserRemembered ($userDataRemember,$connection) {
  $ipAddress = $userDataRemember['ip_address'];
  $browser = $userDataRemember['browser'];
  $userId = $userDataRemember['user_id'];
  
  $sql = "SELECT * FROM user WHERE id = '$userId' AND remember = 'yes'
					AND ip_address = '$ipAddress' AND browser = '$browser'";
  $rememberedUser = $connection->query($sql);

  if (!empty($rememberedUser)) {
    $rememberedUser = $rememberedUser[0];
    $_SESSION['userData'] = [
      'id' => $rememberedUser['id'],
      'name' => $rememberedUser['name'],
      'lastname' => $rememberedUser['lastname'],
      'username' => $rememberedUser['username'],
      'email' => $rememberedUser['email'],
      'password' => $rememberedUser['password']
    ];
    return 'success';
  }
  return false;
}
?>