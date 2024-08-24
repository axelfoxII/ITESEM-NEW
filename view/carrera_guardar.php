<?php
include ('../conf/funciones.php');
include("../conf/mysql.php");
$cod_usuario = 0;
if (verificar_usuario()){
	$cod_usuario = $_SESSION['cod_usuario'];
	$nombre_pagina = "carrera.php";
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
								if(isset($_POST['carrera']) && isset($_POST['sigla']) && isset($_POST['nivel']) && isset($_POST['sucursal'])){
									$carrera = strtoupper($_POST['carrera']);
									$sigla = strtoupper($_POST['sigla']);
									$nivel = $_POST['nivel'];
									$res_min = $_POST['res_min'];
									$sucursal = $_POST['sucursal'];
									$mensualidad = $_POST['mensualidad'];
									$duracion = $_POST['duracion'];

									if($_POST['tipo'] == "guardar"){
										// INSERT tbl_carrera
										$insert_carrera = mysqli_query($con, "INSERT INTO tbl_carrera(nombre_car, sigla_car, cod_nivel_car, resolucion_ministerial_car, mensualidad_car, duracion_car) 
											VALUES('$carrera', '$sigla', $nivel, '$res_min', $mensualidad, '$duracion')");
										if(mysqli_affected_rows($con) > 0){
											// Obtener el codigo de la tabla
											$cod_tabla = 0;
											$sql_tabla = mysqli_query($con, "SELECT cod_tabla FROM tbl_tabla WHERE nombre_tabla = 'tbl_carrera'");
											while ($row_ta = mysqli_fetch_array($sql_tabla)) {
												$cod_tabla = $row_ta['cod_tabla'];
											}
											// Obtener el ultimo cod_carrera
											$codigo = 0;
											$sql_carrera = mysqli_query($con, "SELECT cod_carrera FROM tbl_carrera ORDER BY cod_carrera DESC LIMIT 0,1");
											while ($row = mysqli_fetch_array($sql_carrera)) {
												$codigo = $row['cod_carrera'];
											}
											// tbl_log
											$sql_log = mysqli_query($con, "INSERT INTO tbl_log(cod_tipolog_log, cod_tabla_log, codigo_log, cod_usuario_log) 
												VALUES(1, $cod_tabla, $codigo, $cod_usuario)");

											// INSERT CARREA-SUCURSAL
											for ($i=0; $i<count($sucursal); $i++){
												// Verificar si la carrera y la sucursal estan en la tabla
												$sql_carsuc = mysqli_query($con, "SELECT cod_carrera_sucursal FROM tbl_carrera_sucursal 
													WHERE cod_carrera_carsuc = $codigo AND cod_sucursal_carsuc = $sucursal[$i] AND estado_carsuc = 1");
												if(mysqli_num_rows($sql_carsuc) == 0){
													$insert_carsuc = mysqli_query($con, "INSERT INTO tbl_carrera_sucursal (cod_carrera_carsuc, cod_sucursal_carsuc) 
														VALUES ($codigo, $sucursal[$i])");
												}
											}
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
										$cod_carrera = $_POST['cod_carrera'];
										$data = 0;

										$delete_carsuc = mysqli_query($con, "UPDATE tbl_carrera_sucursal SET estado_carsuc = 0 
											WHERE cod_carrera_carsuc = $cod_carrera");
										// INSERT CARREA-SUCURSAL
										for ($i=0; $i<count($sucursal); $i++){
											// Verificar si la carrera y la sucursal estan en la tabla
											$sql_carsuc = mysqli_query($con, "SELECT cod_carrera_sucursal FROM tbl_carrera_sucursal 
												WHERE cod_carrera_carsuc = $cod_carrera AND cod_sucursal_carsuc = $sucursal[$i]");
											if(mysqli_num_rows($sql_carsuc) == 0){
												$insert_carsuc = mysqli_query($con, "INSERT INTO tbl_carrera_sucursal (cod_carrera_carsuc, cod_sucursal_carsuc) 
													VALUES ($cod_carrera, $sucursal[$i])");
												$data = 1;
											}else{
												while($row_cs = mysqli_fetch_array($sql_carsuc)){
													$cod_carrera_sucursal = $row_cs['cod_carrera_sucursal'];
													$update_carsuc = mysqli_query($con, "UPDATE tbl_carrera_sucursal SET estado_carsuc = 1 
														WHERE cod_carrera_sucursal = $cod_carrera_sucursal");
													$data = 1;
												}
											}
										}

										// UPDATE tbl_carrera
										$update_carrera = mysqli_query($con, "UPDATE tbl_carrera SET nombre_car = '$carrera', sigla_car = '$sigla', cod_nivel_car = $nivel, 
											resolucion_ministerial_car = '$res_min', mensualidad_car = $mensualidad, duracion_car = '$duracion' WHERE cod_carrera = $cod_carrera");
										if(mysqli_affected_rows($con) > 0 || $data == 1){
											// Obtener el codigo de la tabla
											$cod_tabla = 0;
											$sql_tabla = mysqli_query($con, "SELECT cod_tabla FROM tbl_tabla WHERE nombre_tabla = 'tbl_carrera'");
											while ($row_ta = mysqli_fetch_array($sql_tabla)) {
												$cod_tabla = $row_ta['cod_tabla'];
											}
											// tbl_log
											$sql_log = mysqli_query($con, "INSERT INTO tbl_log(cod_tipolog_log, cod_tabla_log, codigo_log, cod_usuario_log) 
												VALUES(2, $cod_tabla, $cod_carrera, $cod_usuario)");
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
									<a href="carrera.php">Volver al Inicio</a>
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