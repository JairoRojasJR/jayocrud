<?php
include('../emulateEnv.php');
include('../server/connectiondb.php');
include('./functions/getCookies.php');
session_start();
$connection = new Connection();
$cookies = getCookies();

$haveLoginData = isset($_POST['user']) && isset($_POST['password']);
$IsUserRemembered = isset($cookies->rememberme) && $cookies->rememberme === 'yes';

if (isset($_SESSION['userData'])) header('location:../');

// ===== LOGEAR USER REMEMBERED =====
else if (empty($_POST) && $IsUserRemembered) {
	include('./functions/remembered.php');
	$userDataRemember = [
		'ip_address' => $_SERVER['REMOTE_ADDR'],
		'browser' => $_SERVER['HTTP_USER_AGENT'],
		'user_id' => $cookies->user_id
	];
	$loginStatus = loginUserRemembered($userDataRemember, $connection);
	if ($loginStatus) header('location:../');
}

// ===== FUNTIONALITY RECOVER PASSWORD =====
else if (isset($_POST['userRecover'])) {
	$user = $_POST['userRecover'];
	include('./functions/recoverPassword.php');
	$userRecoverStatus = recoverPassword($user, $connection);
	echo $userRecoverStatus;
}

// ===== FUNTIONALITY LOGIN USER =====

else if ($haveLoginData) {
	$user = $_POST['user'];
	$password = $_POST['password'];
	$rememberUser = isset($cookies->rememberme) ? $cookies->rememberme : 'no';
	include('./functions/loginUser.php');
	$loginStatus = loginUser($user, $password, $rememberUser, $connection);
	echo $loginStatus;
}
?>
<?php if (empty($_POST)) { ?>
	<!DOCTYPE html>
	<html>

	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<link rel="stylesheet" type="text/css" href="../normalize.css">
		<link rel="stylesheet" type="text/css" href="styles.css">
		<link href="https://fonts.googleapis.com/css2?family=Libre+Baskerville&display=swap" rel="stylesheet">
		<script src="https://kit.fontawesome.com/9f3be5f028.js" crossorigin="anonymous"></script>
		<link rel="icon" href="../favicon.ico" type="image/ico">
		<title>Login</title>
	</head>

	<body>
		<main>
			<div id="msgWarn">
				<i class="fa-solid fa-triangle-exclamation form__warn-icon"></i>
				<p class="form__warn-msg"></p>
			</div>
			<div class="main-content">
				<form id="form-login" class="form-login" action="index.php" method="post">
					<h3 class="form__tittle">Iniciar Sesión</h3>
					<div class="form__fields">
						<h4 class="login-default">Cuenta JAYO CRUD</h4>
						<section class="form__field">
							<label class="form__label" for="user">Usuario o correo electrónico</label>
							<div class="form__input-container">
								<input class="form__input" type="text" id="user" name="user" placeholder="tuemail@gmail.com" value="<?php echo isset($userRecover) ? $userRecover : ''; ?>">
							</div>
						</section>
						<section class="form__field">
							<label class="form__label" for="password">Contraseña</label>
							<div id="password" class="form__input-container">
								<input class="form__input" type="password" id="password" name="password">
								<div class="form__view-password">
									<i class="view fa-solid fa-eye-slash"></i>
								</div>
							</div>
						</section>
						<section class="form__field user__options">
							<div id="rememberme" class="user__option">
								<label>
									<input type="checkbox" name="rememberme">
									Recordar
								</label>
							</div>
							<div id="retrieve" class="user__option">
								<p>He olvidado mi contraseña</p>
							</div>
						</section>
						<section class="form__field form__send">
							<input id="send-form" class="form__input-send" type="submit" name="send" value="Entrar">
						</section>
						<div class="forget__background"></div>
						<section class="form__field forget-password">
							<label for="confirm-email" class="forget__title">Recuperar Contraseña</label>
							<div class="form__field">
								<label for="userRecover" class="form__label">Correo electrónico o nombre de usuario</label>
								<input id="userRecover" class="form__input" type="text" name="userRecover" placeholder="Introduzca su correo elec. o nombre de usuario">
								<button id="sendForgetPassword" class="form__input-send">Confirmar</button>
							</div>
							<div class="forget__msgInSend">
								<span><b>Envío exitoso</b>. Acceda al enlace enviado a su correo electrónico para restablecer su contraseña.<br><br>El enlace <b>expira</b> en 10 minutos</span>
								<i class="fa-solid fa-envelope-circle-check"></i>
								<button id="resendCode">Reenviar enlace</button>
								<button id="changeUser">Cambiar de usuario</button>
							</div>
						</section>
						<div id="processing" class="form__processing-background">
							<span>Procesando...</span>
						</div>
					</div>
					<div class="login__options">
						<!-- <p>Prefiero...</p>
					<div id="facebook" class="login__option">Iniciar sesión con Facebook</div>
					<div id="google" class="login__option">Iniciar sesión con Google</div>
					<div id="twitter" class="login__option">Iniciar sesión con Twitter</div> -->
						<p>Aún no estoy Registrado...</p>
						<div id="signUp" class="login__option">Registrarse</div>
					</div>
				</form>
				<div class="form-aside">
					<img src="../images/lobo.jpg">
				</div>
			</div>
		</main>
		<script src="form.js"></script>
	</body>

	</html>
<?php } ?>