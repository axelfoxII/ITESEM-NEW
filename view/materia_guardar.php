<?php
include ('../conf/funciones.php');
include("../conf/mysql.php");
$cod_usuario = 0;
if (verificar_usuario()){
	$cod_usuario = $_SESSION['cod_usuario'];
	$nombre_pagina = "materia.php";
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
								if(isset($_POST['materia']) && isset($_POST['sigla']) && isset($_POST['carrera'])){
									$materia = strtoupper(preg_replace('/[^a-zA-Z0-9áéíóúñÁÉÍÓÚÑ(),\s]/iu', '', $_POST['materia']));
									$sigla = strtoupper($_POST['sigla']);
									$cod_carrera = $_POST['carrera'];
									$prerequisito = 0;
									if(isset($_POST['prerequisito']) && $_POST['prerequisito'] != "")
										$prerequisito = $_POST['prerequisito'];
									$estructura = 0;
									if(isset($_POST['estructura']) && $_POST['estructura'] != "")
										$estructura = $_POST['estructura'];
									$cod_tipomateria = $_POST['tipo_materia'];
									$horas = 0;
									if(isset($_POST['horas']) && $_POST['horas'] != "")
										$horas = $_POST['horas'];
									$contenido = $_POST['contenido'];

									if($_POST['tipo'] == "guardar"){
										// OBTENER EL NUMERO DE LA MATERIA
										$numero_mat = 0;
										$sql_mat = mysqli_query($con, "SELECT cod_materia FROM tbl_materia WHERE cod_carrera_mat = $cod_carrera");
										$numero_mat = mysqli_num_rows($sql_mat) + 1;

										// INSERT tbl_materia
										$insert_materia = mysqli_query($con, "INSERT INTO tbl_materia (nombre_mat, sigla_mat, cod_carrera_mat, cod_prerequisito_mat, cod_estructura_materia_mat, cod_tipomateria_mat, horas_mat, contenido_mat, numero_mat) 
											VALUES('$materia', '$sigla', $cod_carrera, $prerequisito, $estructura, $cod_tipomateria, $horas, $contenido, $numero_mat)");
										if(mysqli_affected_rows($con) > 0){
											// Obtener el codigo de la tabla
											$cod_tabla = 0;
											$sql_tabla = mysqli_query($con, "SELECT cod_tabla FROM tbl_tabla WHERE nombre_tabla = 'tbl_materia'");
											while ($row_ta = mysqli_fetch_array($sql_tabla)) {
												$cod_tabla = $row_ta['cod_tabla'];
											}
											// Obtener el ultimo cod_materia
											$codigo = 0;
											$sql_materia = mysqli_query($con, "SELECT cod_materia FROM tbl_materia ORDER BY cod_materia DESC LIMIT 0,1");
											while ($row = mysqli_fetch_array($sql_materia)) {
												$codigo = $row['cod_materia'];
											}
											// tbl_log
											$sql_log = mysqli_query($con, "INSERT INTO tbl_log(cod_tipolog_log, cod_tabla_log, codigo_log, cod_usuario_log) 
												VALUES(1, $cod_tabla, $codigo, $cod_usuario)");
											?>
											<div class="text-center">
												<h2 class="text-primary">Registro Guardado.!</h2>
											</div>
											<?php
										}else{
											?>
											<div class="text-center">
												<h2 class="text-danger">Error al guardar el registro.!</h2>
											</div>
											<?php
										}
									}elseif($_POST['tipo'] == "modificar"){
										$cod_materia = $_POST['cod_materia'];

										// UPDATE tbl_carrera
										$update_carrera = mysqli_query($con, "UPDATE tbl_materia SET nombre_mat = '$materia', sigla_mat = '$sigla', cod_carrera_mat = $cod_carrera, 
											cod_prerequisito_mat = $prerequisito, cod_estructura_materia_mat = $estructura, cod_tipomateria_mat = $cod_tipomateria, horas_mat = $horas, 
											contenido_mat = $contenido WHERE cod_materia = $cod_materia");
										if(mysqli_affected_rows($con) > 0){
											// Obtener el codigo de la tabla
											$cod_tabla = 0;
											$sql_tabla = mysqli_query($con, "SELECT cod_tabla FROM tbl_tabla WHERE nombre_tabla = 'tbl_materia'");
											while ($row_ta = mysqli_fetch_array($sql_tabla)) {
												$cod_tabla = $row_ta['cod_tabla'];
											}
											// tbl_log
											$sql_log = mysqli_query($con, "INSERT INTO tbl_log(cod_tipolog_log, cod_tabla_log, codigo_log, cod_usuario_log) 
												VALUES(2, $cod_tabla, $cod_materia, $cod_usuario)");
											?>
											<div class="text-center">
												<h2 class="text-primary">Registro Modificado.!</h2>
											</div>
											<?php
										}else{
											?>
											<div class="text-center">
												<h2 class="text-danger">No se ha modificado el registro.!</h2>
											</div>
											<?php
										}
									}

								}
								?>
								<div class="text-center">
									<a href="materia.php">Volver al Inicio</a>
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