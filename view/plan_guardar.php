<?php
include ('../conf/funciones.php');
include("../conf/mysql.php");
$cod_usuario = 0;
if (verificar_usuario()){
	$cod_usuario = $_SESSION['cod_usuario'];
	$nombre_pagina = "plan.php";
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
								if(isset($_POST['sigla']) && isset($_POST['descripcion']) && isset($_POST['tipo_plan']) && isset($_POST['precio']) && isset($_POST['precio_total'])){
									$sigla = strtoupper($_POST['sigla']);
									$descripcion = strtoupper(preg_replace('/[^a-zA-Z0-9.,%áéíóúñÁÉÍÓÚÑ\s]/iu', '', $_POST['descripcion']));
									$cod_tipoplan = $_POST['tipo_plan'];
									$precio = $_POST['precio'];
									$precio_total = $_POST['precio_total'];
									$sucursal = $_POST['sucursal'];
									$nivel = $_POST['nivel'];

									if($_POST['tipo'] == "guardar"){
										// INSERT tbl_plan
										$insert_carrera = mysqli_query($con, "INSERT INTO tbl_plan(sigla_plan, descripcion_plan, cod_tipoplan_plan, precio_plan, precio_total_plan, cod_sucursal_plan, cod_nivel_plan) 
											VALUES('$sigla', '$descripcion', $cod_tipoplan, $precio, $precio_total, $sucursal, $nivel)");
										if(mysqli_affected_rows($con) > 0){
											// Obtener el codigo de la tabla
											$cod_tabla = 0;
											$sql_tabla = mysqli_query($con, "SELECT cod_tabla FROM tbl_tabla WHERE nombre_tabla = 'tbl_plan'");
											while ($row_ta = mysqli_fetch_array($sql_tabla)) {
												$cod_tabla = $row_ta['cod_tabla'];
											}
											// Obtener el ultimo cod_plan
											$codigo = 0;
											$sql_plan = mysqli_query($con, "SELECT cod_plan FROM tbl_plan ORDER BY cod_plan DESC LIMIT 0,1");
											while ($row = mysqli_fetch_array($sql_plan)) {
												$codigo = $row['cod_plan'];
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
										$cod_plan = $_POST['cod_plan'];

										// UPDATE tbl_plan
										$update_plan = mysqli_query($con, "UPDATE tbl_plan SET sigla_plan = '$sigla', descripcion_plan = '$descripcion', cod_tipoplan_plan = $cod_tipoplan, 
											precio_plan = $precio, precio_total_plan = $precio_total, cod_sucursal_plan = $sucursal, cod_nivel_plan = $nivel 
											WHERE cod_plan = $cod_plan");
										if(mysqli_affected_rows($con) > 0){
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
									<a href="plan.php">Volver al Inicio</a>
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