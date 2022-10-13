<?php
include '../emulateEnv.php';
include('../server/connectiondb.php');
session_start();
$connection = new Connection();

if (isset($_SESSION['userData'])) header('location:../');
else if ($_POST) {
	// User Data
	$userData = [
		'name' => $_POST['name'],
		'lastname' => $_POST['lastname'],
		'username' => $_POST['username'],
		'email' => $_POST['email'],
		'password' => $_POST['password']
	];

	include('register.php');
	$registerStatus = Register($userData, $connection);
	echo $registerStatus;
}
else if (isset($_GET['emailToConfirm'])) {
	$email = $_GET['emailToConfirm'];
	$key = $_GET['key'];
	$emailUnconfirmed = $email . '&key=' . $key;

	$sql = "SELECT id, name, lastname, username, password
					FROM user WHERE email = '$emailUnconfirmed' LIMIT 1";
	$matchingKey = $connection->query($sql);

	if ($matchingKey) {
		$userData = $matchingKey[0];
		$id = (int)$userData['id'];
		
		$sql = "UPDATE user SET email = '$email' WHERE id = $id";
		$connection->executedb($sql);

		$_SESSION['userData'] = array_filter($userData, function ($k) {
			return gettype($k) == 'string';
		}, ARRAY_FILTER_USE_KEY);

		$_SESSION['userData']['email'] = $email;
		$_SESSION['userValidated'] = true;
		
		header('location:../');
	}
}
?>

<?php if (!$_POST) { ?>
	<!DOCTYPE html>
	<html>

	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<link rel="stylesheet" type="text/css" href="../normalize.css">
		<link rel="stylesheet" type="text/css" href="styles.css">
		<link href="https://fonts.googleapis.com/css2?family=Libre+Baskerville&display=swap" rel="stylesheet">
		<script src="https://kit.fontawesome.com/9f3be5f028.js" crossorigin="anonymous"></script>
		<title>Validación de Formulario</title>
	</head>

	<body>
		<main>
			<div id="msgWarn">
				<i class="fa-solid fa-triangle-exclamation form__warn-icon"></i>
				<p class="form__warn-msg">Asegurese de rellenar correctamente el formulario y aceptar los términos y condiciones</p>
			</div>
			<div id="alreadyExists">
				<i class="fa-solid fa-triangle-exclamation form__warn-icon"></i>
				<p class="form__warn-msg"></p>
			</div>
			<div class="main-content">
				<form action="index.php" class="form-login" method="post" autocomplete="nope">
					<h3 class="form__tittle">Registrarse</h3>
					<div class="form__fields">
						<div class="form__field">
							<label class="form__label" for="name">Nombre</label>
							<div class="form__input-container">
								<input class="form__input" type="text" id="name" name="name" placeholder="Tu Nombre">
								<i class="form__status form__status-invalid fa-solid"></i>
							</div>
							<p class="form__msg-invalid"></p>
						</div>
						<div class="form__field">
							<label class="form__label" for="lastname">Apellido</label>
							<div class="form__input-container">
								<input class="form__input" type="text" id="lastname" name="lastname" placeholder="Tu Apellido">
								<i class="form__status form__status-valid fa-solid"></i>
							</div>
							<p class="form__msg-invalid"></p>
						</div>
						<div class="form__field">
							<label class="form__label" for="username">Nombre de usuario</label>
							<div class="form__input-container">
								<input class="form__input" type="text" id="username" name="username" placeholder="Ejemplo_99">
								<i class="form__status form__status-cross fa-solid"></i>
							</div>
							<p class="form__msg-invalid"></p>
						</div>
						<div class="form__field">
							<label class="form__label" for="email">Correo electrónico</label>
							<div class="form__input-container">
								<input id="email" class="form__input" type="email" name="email" placeholder="email@gmail.com">
								<i class="form__status form__status-cross fa-solid"></i>
							</div>
							<p class="form__msg-invalid"></p>
						</div>
						<div class="form__field">
							<label class="form__label" for="password">Contraseña</label>
							<div class="form__input-container">
								<input class="form__input" type="password" id="password" name="password" autocomplete="new-password">
								<div class="form__status">
									<i class="form__status-cross fa-solid"></i>
									<i class="view fa-solid fa-eye-slash"></i>
								</div>
							</div>
							<p class="form__msg-invalid"></p>
						</div>
						<div class="form__field">
							<label class="form__label" for="confirm-password">Confirmar contraseña</label>
							<div class="form__input-container">
								<input class="form__input" type="password" id="confirmPassword" name="confirmPassword">
								<div class="form__status">
									<i class="form__status-cross fa-solid"></i>
									<i class="view fa-solid fa-eye-slash"></i>
								</div>
							</div>
							<p class="form__msg-invalid"></p>
						</div>
						<div class="form__terms">
							<input id="terms" type="checkbox" id="terms" name="terms">
							<label for="terms">Aceptar términos y condiciones</label>
						</div>
						<div class="form__send">
							<input id="send-form" class="form__input-send" type="submit" name="send" value="Registrarse">
						</div>
					</div>
				</form>
				<div id="processing">
					<span>Procesando...</span>
				</div>
				<div class="sendLink">
					<div class="sendLink__background"></div>
					<div class="sendLink__card">
						<span></span>
						<i class="status__icon fa-solid"></i>
					</div>
				</div>
				<div class="form-aside">
					<img src="../images/lobo.jpg">
				</div>
			</div>
		</main>
		<script src="form.js"></script>
	</body>

	</html>
<?php } ?>