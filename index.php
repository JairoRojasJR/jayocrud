<?php
include './emulateEnv.php';
include 'server/connectiondb.php';
session_start();
$connection = new Connection();

if (isset($_SESSION['userData'])) {
	$userData = $_SESSION['userData'];
	$userid = (int)$userData['id'];

	$sql = "SELECT name, lastname FROM user WHERE id = $userid";
	$queryUser = $connection->query($sql);

	if ($queryUser) {
		$userData = $queryUser[0];
		$name = $userData['name'];
		$lastname = $userData['lastname'];
		include('proyects/functions/getProyects.php');
		$proyects = GetProyects($userid, $connection);
	} else {
		session_destroy();
		header("location:./login/");
	}
} else header('location:login/');

$pagTitle = 'Inicio - JayoCrud';
?>
<?php include('templates/header.php'); ?>
<?php if (isset($_SESSION['userValidated'])) { ?>
	<div class="newuser">
		<div class="newuser__background"></div>
		<div class="newuser__card">
			<span>Registro exitoso.<br>Bienvenid@ <?php echo $name . ' ' . $lastname; ?></span>
			<div class="status__container">
				<i class="status__icon fa-solid fa-circle-check"></i>
			</div>
		</div>
	</div>
	<?php unset($_SESSION['userValidated']); ?>
<?php }; ?>
<div class="card welcome-container">
	<p class="welcome">Bienvenid@</p>
	<p class="welcome-name"></p>
</div>
<div class="tittle-description">
	<img src="images/carpeta.png">
	<h3>Mis Proyectos</h3>
</div>
<div class="card no-content" style="display: <?php echo !$proyects ? 'flex' : 'none'; ?>">
	<p>Ningún proyecto agregado</p>
</div>
<div class="card-container">
	<?php foreach ($proyects as $proyect) { ?>
		<div class="card main-cards">
			<div class="card-img">
				<img src="images/userproyects/<?php echo $proyect['image']; ?>">
			</div>
			<div class="card-body">
				<h4 class="card-tittle"><?php echo $proyect['name']; ?></h4>
				<p class="card-description"><?php echo $proyect['description']; ?> </p>
			</div>
		</div>
	<?php } ?>
</div>
<?php include('templates/footer.php'); ?>