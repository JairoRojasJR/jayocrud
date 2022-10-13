<?php
include '../emulateEnv.php';
include '../server/connectiondb.php';
include '../consolelog.php';

session_start();
$connection = new Connection();
$userData = isset($_SESSION['userData']) ? $_SESSION['userData'] : null;

if ($userData) {
	$email = $userData['email'];
	$userid = $userData['id'];

	$adminPermissions = isset($_SESSION['adminPermissions']) ? $_SESSION['adminPermissions'] : false;
	
	if ($email !== 'xdbable@gmail.com')  $adminPermissions = true;
	else if (isset($_POST['password'])) {
		if ($_POST['password'] === PASS_ADMIN_JAYOCRUD)	$_SESSION['adminPermissions'] = true;
		else $_SESSION['adminPermissions'] = false;
		echo json_encode(['allow' => $_SESSION['adminPermissions']]);
	}

	if ($adminPermissions) {
		if (isset($_POST['insert'])) {
			include('functions/insert.php');
			Insert($userid, $connection);
		} else if (isset($_POST['delete'])) {
			include('functions/delete.php');
			Delete($userid, $connection);
		} else if (isset($_POST['update'])) {
			include('functions/update.php');
			Update($userid, $connection);
		}
	} else if ($_POST && !isset($_POST['password'])) echo json_encode(['notAllowed' => true]);
	
	include('functions/getProyects.php');
	$proyects = GetProyects($userData['id'], $connection);
}
else header('location:../login');
$pagTitle = 'Proyectos';
?>
<?php if (!$_POST && $userData) { ?>
	<?php include '../templates/header.php'; ?>
	<?php if ($userData['email'] === 'xdbable@gmail.com' && !$adminPermissions) { ?>
		<div id="msgSuccessContainer">
			<i class="fa-solid fa-circle-check form__success-icon"></i>
			<p id="msgSuccess" class="form__success-msg"></p>
		</div>
		<div class="adminPassContainer">
			<div class="adminPassBackground"></div>
			<div class="adminPassCard">
				<p>La <b>administración</b> de los <b>proyectos</b> subidos en esta cuenta están <b>reservados</b> para el admin.</p>
				<p>Si eres un visitante puedes visitar la página de proyectos, pero <b>no</b> puedes <b>agregar</b>, <b>editar</b> <b>ni borrar</b> los proyectos.</p>
				<p><b>Gracias por tu visita.</b></p>
				<button id="visit" class="form__input-visit">Visitar</button>
				<label for="passwordAdmin">Contraseña modo admin:</label>
				<input id="passwordAdmin" class="form__input" type="password">
				<button id="sendPasswordAdmin" class="form__input-send" type="submit">Enviar</button>
			</div>
		</div>
	<?php }; ?>
	<div id="msgWarnContainer">
		<i class="fa-solid fa-triangle-exclamation form__warn-icon"></i>
		<p id="msgWarn" class="form__warn-msg"></p>
	</div>
	<div class="main">
		<form id="formProyects" class="form-proyects" method="POST" enctype="multipart/form-data">
			<div class="form__fields">
				<div class="form__field">
					<label for="name">Nombre del Proyecto</label>
					<input id="name" type="text" name="name">
				</div>
				<div class="form__field">
					<label for="image">Imagen</label>
					<label id="btnSelectImage" for="image" class="btnSelectImage">
						<span>Selecciona una imagen</span>
						<div class="image-change">Cambiar</div>
					</label>
					<input id="image" type="file" name="image" accept="image/*">
					<div class="image-container"></div>
				</div>
				<div class="form__field">
					<label for="description">Descripción</label>
					<textarea id="description" name="description"></textarea>
				</div>
			</div>
			<div class="form__send">
				<input id="btnSend" class="btn-send <?php if (!$adminPermissions) echo 'disable'; ?>" type="submit" value="Agregar" <?php if (!$adminPermissions) echo 'style="opacity:.5;cursor: not-allowed;z-index:0"'; ?>>
			</div>
		</form>
		<div class="modal">
			<form id="modal-formProyects" class="form-proyects modal__form-proyects" enctype="multipart">
				<div class="form__title">Editar</div>
				<div class="form__fields">
					<div class="form__field">
						<label for="modal-name">Nombre del Proyecto</label>
						<input id="modal-name" type="text" name="name">
					</div>
					<div class="form__field">
						<label for="modal-image">Imagen</label>
						<label id="btnModalSelectImage" for="modal-image" class="btnSelectImage">
							<span>Selecciona una imagen</span>
							<div class="image-change">Cambiar</div>
						</label>
						<input id="modal-image" type="file" name="image" accept="image/*">
						<div class="image-container"></div>
					</div>
					<div class="form__field">
						<label for="modal-description">Descripción</label>
						<textarea id="modal-description" name="description"></textarea>
					</div>
				</div>
				<div class="form__actions">
					<button class="btn btn-save <?php if (!$adminPermissions) echo 'disable'; ?>" <?php if (!$adminPermissions) echo 'style="opacity:.5;cursor: not-allowed;z-index:0"'; ?>>Guardar</button>
					<button class="btn btn-cancel">Cancelar</button>
				</div>
				<input id="proyectid" type="text" name="proyectid" style="display:none">
			</form>
		</div>
		<div class="proyects-container">
			<table id="tableProyects" class="table-proyects">
				<thead>
					<tr>
						<th class="th">Id</th>
						<th class="th">Nombre</th>
						<th class="th">Imagen</th>
						<th class="th">Descripción</th>
						<th class="th">Acciones</th>
					</tr>
				</thead>
				<tbody id="bodyTableProyects">
					<?php foreach ($proyects as $proyect) { ?>
						<tr>
							<td class="td"><?php echo $proyect['id']; ?></td>
							<td class="td"><?php echo $proyect['name']; ?></td>
							<td class="td">
								<img src="../images/userproyects/<?php echo $proyect['image']; ?>" nameimg="<?php echo $proyect['image']; ?>">
							</td>
							<td class="td"><?php echo $proyect['description']; ?></td>
							<td id="<?php echo $proyect['id']; ?>" class="td">
								<div class="actions edit">Editar</div>
								<div class="actions delete <?php if (!$adminPermissions) echo 'disable'; ?>" <?php if (!$adminPermissions) echo 'style="opacity:.5;cursor: not-allowed;z-index:0"'; ?>>Borrar</div>
							</td>
						</tr>
					<?php }; ?>
				</tbody>
			</table>
			<div id="notProyects" class="no-content" style="display:<?php if (empty($proyects)) echo 'flex';
																															else 'none' ?>">
				<span>No hay ningún proyecto</span>
			</div>
		</div>
	</div>
	<?php include '../templates/footer.php'; ?>
<?php }; ?>