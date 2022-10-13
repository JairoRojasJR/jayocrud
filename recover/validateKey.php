<?php
function ValidateKey($key, $connection)
{
	$inf = [
		'function' => 'ValidateKey',
		'success' => false,
		'error' => null
	];

	$sql = "SELECT password_recover FROM user WHERE password_recover = '$key'";
	$matchingKey = $connection->query($sql);

	if (!empty($matchingKey)) {
		$user = explode('_u', $key)[1];
		$user = explode('u_', $user)[0];

		$expires = explode('exp_', $key)[1];

		$date = new DateTime();
		$time = $date->getTimestamp();
		$currentTimeMin = $time / 60;

		if ($expires - $currentTimeMin > 0) {
			$inf['success'] = true;
			$inf['userRecover'] = $user;
		} else $inf['error'] = 'expirado';
	} else $inf['error'] = 'inválido';
	return $inf;
}
