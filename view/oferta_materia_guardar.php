<?php
include ('../conf/funciones.php');
include("../conf/mysql.php");
$cod_usuario = 0;
if (verificar_usuario()){
	$cod_usuario = $_SESSION['cod_usuario'];
	$nombre_pagina = "oferta_materia.php";
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
								if(isset($_POST['sucursal']) && isset($_POST['materia']) && isset($_POST['turno']) && isset($_POST['docente']) && isset($_POST['periodo']) && isset($_POST['aula']) && isset($_POST['fecha_inicio']) && isset($_POST['fecha_fin']) && isset($_POST['cupo'])){
									$sucursal = $_POST['sucursal'];
									$cod_materia = $_POST['materia'];
									$cod_turno = $_POST['turno'];
									$cod_docente = $_POST['docente'];
									$periodo = $_POST['periodo'];
									$aula = $_POST['aula'];
									$cupo = $_POST['cupo'];
									$tipo_modalidad = $_POST['tipo_modalidad'];
									$grupo = $_POST['grupo'];
									$fecha_inicio = date_format(date_create($_POST['fecha_inicio']), "Y-m-d");
									$fecha_fin = date_format(date_create($_POST['fecha_fin']), "Y-m-d");

									if($_POST['tipo'] == "guardar"){
										// Verificar si la oferta ya esta registrada
										$sql_oferta = mysqli_query($con, "SELECT cod_oferta_materia FROM tbl_oferta_materia 
											WHERE cod_materia_of = $cod_materia AND cod_periodo_of = $periodo AND cod_turno_of = $cod_turno 
											AND cod_docente_of = $cod_docente AND cod_docente_of > 1 AND estado_of = 1 AND cod_grupo_of = $grupo");
										if(mysqli_num_rows($sql_oferta) == 0){
											// INSERT tbl_oferta_materia
											$insert_persona = mysqli_query($con, "INSERT INTO tbl_oferta_materia (cod_materia_of, cod_periodo_of, cod_docente_of, fecha_inicio_of, 
												fecha_fin_of, cod_turno_of, cod_aula_of, cupo_max_of, cod_tipomodalidad_of, cod_grupo_of, cod_sucursal_of) 
												VALUES($cod_materia, $periodo, $cod_docente, '$fecha_inicio', '$fecha_fin', $cod_turno, $aula, $cupo, $tipo_modalidad, $grupo, $sucursal)");
											if(mysqli_affected_rows($con) > 0){
												//Obtener el ultimo cod_oferta_materia
												$cod_oferta_materia = 0;
												$sql_oferta = mysqli_query($con, "SELECT cod_oferta_materia FROM tbl_oferta_materia WHERE estado_of = 1 
													ORDER BY cod_oferta_materia DESC LIMIT 0,1");
												while ($row_of = mysqli_fetch_array($sql_oferta)) {
													$cod_oferta_materia = $row_of['cod_oferta_materia'];
												}

												// Obtener el codigo de la tabla
												$cod_tabla = 0;
												$sql_tabla = mysqli_query($con, "SELECT cod_tabla FROM tbl_tabla WHERE nombre_tabla = 'tbl_oferta_materia'");
												while ($row_ta = mysqli_fetch_array($sql_tabla)) {
													$cod_tabla = $row_ta['cod_tabla'];
												}
												// tbl_log
												$sql_log = mysqli_query($con, "INSERT INTO tbl_log(cod_tipolog_log, cod_tabla_log, codigo_log, cod_usuario_log) 
													VALUES(1, $cod_tabla, $cod_oferta_materia, $cod_usuario)");
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
										}else{
											?>
											<div class="text-center">
												<h2 class="text-danger">Error al guardar el registro.!<br>La oferta ya está registrada.</h2>
											</div>
											<?php
										}
									}elseif($_POST['tipo'] == "modificar"){
										$cod_oferta = $_POST['codigo'];

										// UPDATE tbl_persona
										$update_persona = mysqli_query($con, "UPDATE tbl_oferta_materia SET cod_turno_of = $cod_turno, cod_docente_of = $cod_docente, 
											cod_periodo_of = $periodo, cod_aula_of = $aula, cupo_max_of = $cupo, fecha_inicio_of = '$fecha_inicio', fecha_fin_of = '$fecha_fin', 
											cod_tipomodalidad_of = $tipo_modalidad, cod_grupo_of = $grupo 
											WHERE cod_oferta_materia = $cod_oferta");
										if(mysqli_affected_rows($con) > 0){
											// Obtener el codigo de la tabla
											$cod_tabla = 0;
											$sql_tabla = mysqli_query($con, "SELECT cod_tabla FROM tbl_tabla WHERE nombre_tabla = 'tbl_oferta_materia'");
											while ($row_ta = mysqli_fetch_array($sql_tabla)) {
												$cod_tabla = $row_ta['cod_tabla'];
											}
											// tbl_log
											$sql_log = mysqli_query($con, "INSERT INTO tbl_log(cod_tipolog_log, cod_tabla_log, codigo_log, cod_usuario_log) 
												VALUES(2, $cod_tabla, $cod_oferta, $cod_usuario)");
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
									<a href="oferta_materia.php">Volver al Inicio</a>
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