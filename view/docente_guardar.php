<?php
include ('../conf/funciones.php');
include("../conf/mysql.php");
$cod_usuario = 0;
if (verificar_usuario()){
	$cod_usuario = $_SESSION['cod_usuario'];
	$nombre_pagina = "docente.php";
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
								if(isset($_POST['codigo']) && isset($_POST['titulo_profesional']) && isset($_POST['sucursal'])){
									$cod_persona = $_POST['codigo'];
									$cod_tituloprofesional = $_POST['titulo_profesional'];
									$sucursal = $_POST['sucursal'];

									if($_POST['tipo'] == "guardar"){
										// INSERT tbl_persona
										$insert_persona = mysqli_query($con, "INSERT INTO tbl_docente(cod_persona_doc, cod_tituloprofesional_doc) 
											VALUES($cod_persona, $cod_tituloprofesional)");
										if(mysqli_affected_rows($con) > 0){
											// Obtener el codigo de la tabla
											$cod_tabla = 0;
											$sql_tabla = mysqli_query($con, "SELECT cod_tabla FROM tbl_tabla WHERE nombre_tabla = 'tbl_docente'");
											while ($row_ta = mysqli_fetch_array($sql_tabla)) {
												$cod_tabla = $row_ta['cod_tabla'];
											}
											// Obtener el ultimo cod_docente
											$codigo = 0;
											$sql_docente = mysqli_query($con, "SELECT cod_docente FROM tbl_docente ORDER BY cod_docente DESC LIMIT 0,1");
											while ($row = mysqli_fetch_array($sql_docente)) {
												$codigo = $row['cod_docente'];
											}
											// tbl_log
											$sql_log = mysqli_query($con, "INSERT INTO tbl_log(cod_tipolog_log, cod_tabla_log, codigo_log, cod_usuario_log) 
												VALUES(1, $cod_tabla, $codigo, $cod_usuario)");

											// INSERT CARREA-SUCURSAL
											for ($i=0; $i<count($sucursal); $i++){
												// Verificar si el docente y la sucursal estan en la tabla
												$sql_docsuc = mysqli_query($con, "SELECT cod_docente_sucursal FROM tbl_docente_sucursal 
													WHERE cod_docente_docsuc = $codigo AND cod_sucursal_docsuc = $sucursal[$i] AND estado_docsuc = 1");
												if(mysqli_num_rows($sql_docsuc) == 0){
													$insert_carsuc = mysqli_query($con, "INSERT INTO tbl_docente_sucursal (cod_docente_docsuc, cod_sucursal_docsuc) 
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
										$cod_docente = $_POST['codigo'];
										$data = 0;

										$delete_carsuc = mysqli_query($con, "UPDATE tbl_docente_sucursal SET estado_docsuc = 0 
											WHERE cod_docente_docsuc = $cod_docente");
										// INSERT CARREA-SUCURSAL
										for ($i=0; $i<count($sucursal); $i++){
											// Verificar si el docente y la sucursal estan en la tabla
											$sql_docsuc = mysqli_query($con, "SELECT cod_docente_sucursal FROM tbl_docente_sucursal 
												WHERE cod_docente_docsuc = $cod_docente AND cod_sucursal_docsuc = $sucursal[$i]");
											if(mysqli_num_rows($sql_docsuc) == 0){
												$insert_carsuc = mysqli_query($con, "INSERT INTO tbl_docente_sucursal (cod_docente_docsuc, cod_sucursal_docsuc) 
													VALUES ($cod_docente, $sucursal[$i])");
												$data = 1;
											}else{
												while($row_ds = mysqli_fetch_array($sql_docsuc)){
													$cod_docente_sucursal = $row_ds['cod_docente_sucursal'];
													$update_docsuc = mysqli_query($con, "UPDATE tbl_docente_sucursal SET estado_docsuc = 1 
														WHERE cod_docente_sucursal = $cod_docente_sucursal");
													$data = 1;
												}
											}
										}

										// UPDATE tbl_persona
										$update_persona = mysqli_query($con, "UPDATE tbl_docente SET cod_tituloprofesional_doc = $cod_tituloprofesional 
											WHERE cod_docente = $cod_docente");
										if(mysqli_affected_rows($con) > 0 || $data == 1){
											// Obtener el codigo de la tabla
											$cod_tabla = 0;
											$sql_tabla = mysqli_query($con, "SELECT cod_tabla FROM tbl_tabla WHERE nombre_tabla = 'tbl_docente'");
											while ($row_ta = mysqli_fetch_array($sql_tabla)) {
												$cod_tabla = $row_ta['cod_tabla'];
											}
											// tbl_log
											$sql_log = mysqli_query($con, "INSERT INTO tbl_log(cod_tipolog_log, cod_tabla_log, codigo_log, cod_usuario_log) 
												VALUES(2, $cod_tabla, $cod_docente, $cod_usuario)");
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
									<a href="docente.php">Volver al Inicio</a>
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