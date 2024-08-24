<?php
include ('../conf/funciones.php');
include("../conf/mysql.php");
$cod_usuario = 0;
if (verificar_usuario()){
	$cod_usuario = $_SESSION['cod_usuario'];
	$nombre_pagina = "persona.php";
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
								if(isset($_POST['nombre']) && isset($_POST['apellido']) && isset($_POST['carnet'])){
									$nombre = strtoupper(preg_replace('/[^a-zA-ZáéíóúñÁÉÍÓÚÑ\s]/iu', '', $_POST['nombre']));
									$apellido = strtoupper(preg_replace('/[^a-zA-ZáéíóúñÁÉÍÓÚÑ\s]/iu','',$_POST['apellido']));
									$carnet = $_POST['carnet'];
									$complemento = 'NULL';
									if(isset($_POST['complemento']))
										$complemento = $_POST['complemento'];
									$cod_expedido = $_POST['expedido'];

									$fechanac = date_format(date_create($_POST['fecha_nac']), "Y-m-d");

									$celular = preg_replace('/[^0-9]/iu', '', $_POST['celular']);
									$celular2 = preg_replace('/[^0-9]/iu', '', $_POST['celular2']);
									$correo = "";
									if(isset($_POST['correo']))
										$correo = $_POST['correo'];
									$sexo = $_POST['sexo'];
									$direccion = "";
									if(isset($_POST['direccion']))
										$direccion = preg_replace('/[^a-zA-Z0-9.,áéíóúñÁÉÍÓÚÑ\s]/iu', '', $_POST['direccion']);
									$pais = $_POST['pais'];
									$departamento = $_POST['departamento'];

									$nombre_fact = $nombre." ".$apellido;
									$nit_fact = $carnet;

									if($_POST['tipo'] == "guardar"){
										// INSERT tbl_persona
										$insert_persona = mysqli_query($con, "INSERT INTO tbl_persona(nombre_per, apellido_per, carnet_per, complemento_carnet_per, cod_expedido_per, 
											fecha_nacimiento_per, cod_pais_per, cod_departamento_per, celular_per, celular2_per, cod_sexo_per, correo_per, direccion_per, nombre_factura_per, nit_factura_per) 
											VALUES('$nombre', '$apellido', '$carnet', '$complemento', $cod_expedido, '$fechanac', $pais, $departamento, '$celular', 
											'$celular2', $sexo, '$correo', '$direccion', '$nombre_fact', '$nit_fact')");
										if(mysqli_affected_rows($con) > 0){
											// Obtener el codigo de la tabla
											$cod_tabla = 0;
											$sql_tabla = mysqli_query($con, "SELECT cod_tabla FROM tbl_tabla WHERE nombre_tabla = 'tbl_persona'");
											while ($row_ta = mysqli_fetch_array($sql_tabla)) {
												$cod_tabla = $row_ta['cod_tabla'];
											}
											// Obtener el ultimo cod_persona
											$codigo = 0;
											$sql_persona = mysqli_query($con, "SELECT cod_persona FROM tbl_persona ORDER BY cod_persona DESC LIMIT 0,1");
											while ($row = mysqli_fetch_array($sql_persona)) {
												$codigo = $row['cod_persona'];
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
										$cod_persona = $_POST['cod_persona'];

										// UPDATE tbl_persona
										$update_persona = mysqli_query($con, "UPDATE tbl_persona SET nombre_per = '$nombre', apellido_per = '$apellido', 
											carnet_per = '$carnet', complemento_carnet_per = '$complemento', cod_expedido_per = $cod_expedido, 
											fecha_nacimiento_per = '$fechanac', cod_pais_per = $pais, cod_departamento_per = $departamento, celular_per = '$celular', 
											celular2_per = '$celular2', cod_sexo_per = $sexo, correo_per = '$correo', direccion_per = '$direccion', 
											nombre_factura_per = '$nombre_fact', nit_factura_per = '$nit_fact' 
											WHERE cod_persona = $cod_persona");
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
									<a href="persona.php">Volver al Inicio</a>
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