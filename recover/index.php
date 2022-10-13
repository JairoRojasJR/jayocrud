<?php
include('../emulateEnv.php');
include '../server/connectiondb.php';
session_start();
$connection = new Connection();
$keyValidated = false;

if (isset($_GET['key'])) {
	include('validateKey.php');
	$statusValidateKey = ValidateKey($_GET['key'], $connection);

	if ($statusValidateKey['success']) {
		$userRecover = $statusValidateKey['userRecover'];
		$keyValidated = true;
		$_SESSION['userRecover'] = $userRecover;
	} else {
		$error = $statusValidateKey['error'];
		$msgError = 'Enlace <b>' . $error . '</b><br>' .  ($error === 'expirado' ? 'Por favor solicite uno nuevo' : 'Pruebe a solicitar uno nuevo<br><br>Si ha solicitado un código anteriormente, debe acceder al último código que se haya enviado a su correo eléctrónico');
	}
} else if (isset($_POST['password'])) {
	$newPassword = $_POST['password'];
	$userRecover = $_SESSION['userRecover'];

	$sql = "UPDATE user SET password = '$newPassword', password_recover = '' WHERE username = '$userRecover' OR email = '$userRecover'";
	$connection->executedb($sql);
	
	session_destroy();
	echo json_encode(['success' => true, 'user' => $userRecover]);
} else $msgError = 'Por favor solicite un código para recuperar su contraseña';

?>
<?php if (empty($_POST)) { ?>
	<!DOCTYPE html>
	<html>

	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<script src="https://kit.fontawesome.com/9f3be5f028.js" crossorigin="anonymous"></script>
		<link rel="stylesheet" type="text/css" href="../normalize.css">
		<link rel="stylesheet" type="text/css" href="styles.css">
		<link href="https://fonts.googleapis.com/css2?family=Libre+Baskerville&display=swap" rel="stylesheet">
		<title>Recuperar Contraseña</title>
	</head>

	<body>

		<?php if (!$keyValidated) { ?>
			<main>
				<div class="form__recover-result--background">
					<span>Procesando...</span>
				</div>
				<div class="msgErrorContainer">
					<div class="form__recover-result">
						<span class="result-msg"><?php print_r($msgError); ?></span>
						<i class="fa-solid"></i>
						<button id="loginBack">Volver al Login</button>
					</div>
				</div>
			</main>
			<script src="receptingcode.js"></script>
		<?php } ?>

		<?php if ($keyValidated) { ?>
			<main>
				<div id="msgWarn">
					<i class="fa-solid fa-triangle-exclamation form__warn-icon"></i>
					<p class="form__warn-msg">Asegurese de introducir una contraseña, que sea válida y que ambos campos coincidan</p>
				</div>
				<form id="form" autocomplete="off">
					<div class="form__header">
						<span class="form__tittle">JAYO CRUD</span>
						<span>Cambiar Contraseña</span>
					</div>
					<div class="form__fields">
						<div class="form__field">
							<label for="user" class="form__label label-user">User</label>
							<label for="password" id="user" class="form__label"><?php echo $userRecover; ?></label>
							<p class="form__msg-invalid"></p>
						</div>
						<div class="form__field">
							<label for="password" class="form__label">Nueva Contraseña</label>
							<div class="form__input-container password">
								<input id="password" class="form__input" type="password" name="password" placeholder="Introduzca su nueva contraseña">
								<div class="form__status">
									<i class="form__status-cross fa-solid"></i>
									<i class="view fa-solid fa-eye-slash"></i>
								</div>
							</div>
							<p class="form__msg-invalid"></p>
						</div>
						<div class="form__field">
							<label for="confirmPassword" class="form__label">Confirmar Contraseña</label>
							<div class="form__input-container confirm-password">
								<input id="confirmPassword" class="form__input" type="password" name="confirmPassword">
								<div class="form__status" autocomplete="nope">
									<i class="form__status-cross fa-solid"></i>
									<i class="view fa-solid fa-eye-slash"></i>
								</div>
							</div>
							<p class="form__msg-invalid"></p>
						</div>
					</div>
					<div class="form__send">
						<button id="sendNewPassword" class="form__input-send">Cambiar Contraseña</button>
					</div>
					<div class="form__recover-result--background">
						<span>Procesando...</span>
					</div>
					<div class="form__recover-result">
						<span class="result-msg"></span>
						<i class="fa-solid"></i>
						<button id="loginBack">Volver al Login</button>
					</div>
				</form>
			</main>
			<script src="app.js"></script>
		<?php } ?>
	</body>

	</html>
<?php }; ?>