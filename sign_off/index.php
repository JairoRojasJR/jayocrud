<?php
	include '../emulateEnv.php';
	include '../server/connectiondb.php';
	session_start();

	$connection = new Connection();
	$user_id = (int)$_SESSION['userData']['id'];
	$sql = "UPDATE user SET remember = 'no' WHERE id = $user_id";
	$connection->executedb($sql);
	session_destroy();
	header('location:../login');
?>