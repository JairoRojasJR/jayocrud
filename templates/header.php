<!DOCTYPE html>
<html>

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<script src="https://kit.fontawesome.com/9f3be5f028.js" crossorigin="anonymous"></script>
	<link rel="stylesheet" type="text/css" href="/normalize.css">
	<link rel="stylesheet" type="text/css" href="/templates/styles.css">
	<link rel="stylesheet" type="text/css" href="./styles.css">
	<title><?php echo $pagTitle; ?></title>
</head>

<body>
	<div class="container">
		<header class="header">
			<div class="header__tittle">
				<ul class="header__ul">
					<li class="header__li header__logo">
						<img src="/images/lobo.jpg">
					</li>
					<li id="headerUserName" class="header__li header__tittle-text"></li>
				</ul>
			</div>
			<nav class="nav-header--bg">
				<ul class="header__ul nav__ul">
					<li class="header__li nav__li index">Inicio</li>
					<li class="header__li nav__li proyects">Proyectos</li>
					<li class="header__li nav__li signoff">Cerrar sesión</li>
				</ul>
			</nav>
			<i id="btn-shownav" class="fa-solid fa-bars"></i>
			<div id="navmargin" class="margin-header--sm"></div>
			<nav class="nav-header--sm">
				<ul class="header__ul nav__ul">
					<li class="nav__header">
						<i id="btn-hiddennav" class="fa-solid fa-x nav__close"></i>
						<div class="nav__line">
							<div id="line-username" class="line-username"></div>
						</div>
					</li>
					<li class="header__li nav__li index">Inicio</li>
					<li class="header__li nav__li proyects">Proyectos</li>
					<li class="header__li nav__li signoff">Cerrar sesión</li>
				</ul>
			</nav>
		</header>