<?php
include ('../conf/funciones.php');
include("../conf/mysql.php");
$cod_usuario = 0;
if (verificar_usuario()){
	$cod_usuario = $_SESSION['cod_usuario'];
	$nombre_pagina = "perfil_sistema.php";
	// Verificar el privilegio de la pagina
	$sql_pagina = mysqli_query($con, "SELECT cod_privilegio FROM tbl_submenu, tbl_privilegio, tbl_usuario 
		WHERE cod_submenu = cod_submenu_priv AND cod_perfil_priv = cod_perfil_us AND estado_priv = 1 
		AND cod_usuario = $cod_usuario AND enlace_subm = '$nombre_pagina'");
	if(mysqli_num_rows($sql_pagina) > 0){
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
		<meta name="description" content="Bootstrap Admin Template">
		<meta name="keywords" content="app, responsive, jquery, bootstrap, dashboard, admin">
		<link rel="shortcut icon" href="img/logo.png">
		<title>ITESEM - SIS</title>
		<!-- Vendor styles-->
		<!-- Animate.CSS-->
		<link rel="stylesheet" href="vendor/animate.css/animate.css">
		<!-- Bootstrap-->
		<link rel="stylesheet" href="vendor/bootstrap/dist/css/bootstrap.min.css">
		<!-- Ionicons-->
		<link rel="stylesheet" href="vendor/ionicons/css/ionicons.css">
		<!-- Material Colors-->
		<link rel="stylesheet" href="vendor/material-colors/dist/colors.css">
		<!-- Application styles-->
		<link rel="stylesheet" href="css/app.css">
	</head>
	<body class="theme-default">
		<div class="layout-container">
			<?php include('menu.php'); ?>
			<!-- Main section-->
			<main class="main-container">
				<!-- Page content-->
				<section class="section-container">
					<div class="container-fluid">
						<div class="cardbox">
							<div class="cardbox-body">
								<?php
								if(isset($_POST['cod_perfil']) && isset($_POST['submenu'])){
									$cod_perfil = $_POST['cod_perfil'];
									$contador = 0;

									// Desactivar los privilegios
									$update_resetear = mysqli_query($con, "UPDATE tbl_privilegio SET estado_priv = 0 WHERE cod_perfil_priv = $cod_perfil");

									foreach($_POST['submenu'] as $cod_submenu){
										// Verificar si ya esta el privilegio en la tabla
										$cod_privilegio = 0;
										$sql_privilegio = mysqli_query($con, "SELECT cod_privilegio FROM tbl_privilegio 
											WHERE cod_perfil_priv = $cod_perfil AND cod_submenu_priv = $cod_submenu");
										if (mysqli_num_rows($sql_privilegio) > 0) {
											while ($row_priv = mysqli_fetch_array($sql_privilegio)) {
												$cod_privilegio = $row_priv['cod_privilegio'];
												// UPDATE estado_priv = 1
												$update_privilegio = mysqli_query($con, "UPDATE tbl_privilegio SET estado_priv = 1 WHERE cod_privilegio = $cod_privilegio");
												$contador = 1;
											}
										}else{
											// INSERT tbl_privilegio
											$insert_privilegio = mysqli_query($con, "INSERT INTO tbl_privilegio (cod_perfil_priv, cod_submenu_priv) 
												VALUES($cod_perfil, $cod_submenu)");
											$contador = 1;
										}
									}

									if($contador > 0){
										?>
										<div class="text-center">
											<h2 class="text-primary">Privilegios Actualizados.!</h2>
										</div>
										<?php
									}else{
										?>
										<div class="text-center">
											<h2 class="text-danger">Error al guardar los privilegios.!</h2>
										</div>
										<?php
									}
								}
								?>
								<div class="text-center">
									<a href="perfil_sistema.php">Volver al Inicio</a>
								</div>

							</div>
						</div>
					</div>
				</section>
			</main>
		</div>
		<!-- End Search template-->
		<?php include('ajuste.php'); ?>
		<!-- Modernizr-->
		<script src="vendor/modernizr/modernizr.custom.js"></script>
		<!-- jQuery-->
		<script src="vendor/jquery/dist/jquery.js"></script>
		<!-- Bootstrap-->
		<script src="vendor/popper.js/dist/umd/popper.min.js"></script>
		<script src="vendor/bootstrap/dist/js/bootstrap.js"></script>
		<!-- Material Colors-->
		<script src="vendor/material-colors/dist/colors.js"></script>
		<!-- Screenfull-->
		<script src="vendor/screenfull/dist/screenfull.js"></script>
		<!-- jQuery Localize-->
		<script src="vendor/jquery-localize/dist/jquery.localize.js"></script>
		<!-- Sparkline-->
		<script src="vendor/jquery-sparkline/jquery.sparkline.js"></script>
		<!-- jQuery Knob charts-->
		<script src="vendor/jquery-knob/js/jquery.knob.js"></script>
		<!-- App script-->
		<script src="js/app.js"></script>
	</body>
</html>
<?php
	}else{
		header('Location:inicio.php');
	}
}else {
	header('Location:../inicio.html');
}
?>