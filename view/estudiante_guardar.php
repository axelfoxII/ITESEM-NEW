<?php
include ('../conf/funciones.php');
include("../conf/mysql.php");
$cod_usuario = 0;
if (verificar_usuario()){
	$cod_usuario = $_SESSION['cod_usuario'];
	$nombre_pagina = "estudiante.php";
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
								if(isset($_POST['cod_persona']) && isset($_POST['carrera']) && isset($_POST['plan']) && isset($_POST['forma_llegada'])){
									// PERSONA
									$nombre = strtoupper(preg_replace('/[^a-zA-ZáéíóúñÁÉÍÓÚÑ\s]/iu', '', $_POST['nombre']));
									$apellido = strtoupper(preg_replace('/[^a-zA-ZáéíóúñÁÉÍÓÚÑ\s]/iu','',$_POST['apellido']));
									$carnet = $_POST['carnet'];
									$cod_expedido = $_POST['expedido'];
									$fechanac = "0000-00-00";
									if(isset($_POST['fecha_nac']))
										$fechanac = date_format(date_create($_POST['fecha_nac']), 'Y-m-d');

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

									// PERSONA
									$dato_per = 0;
									$cod_persona = $_POST['cod_persona'];
									if($cod_persona == '0'){
										// INSERT tbl_persona
										$insert_persona = mysqli_query($con, "INSERT INTO tbl_persona(nombre_per, apellido_per, carnet_per, cod_expedido_per, 
											fecha_nacimiento_per, cod_pais_per, cod_departamento_per, celular_per, celular2_per, cod_sexo_per, correo_per, direccion_per, nombre_factura_per, nit_factura_per) 
											VALUES('$nombre', '$apellido', '$carnet', $cod_expedido, '$fechanac', $pais, $departamento, '$celular', 
											'$celular2', $sexo, '$correo', '$direccion', '$nombre_fact', '$nit_fact')");
										if(mysqli_affected_rows($con) > 0){
											// Obtener el codigo de la tabla
											$cod_tabla = 0;
											$sql_tabla = mysqli_query($con, "SELECT cod_tabla FROM tbl_tabla WHERE nombre_tabla = 'tbl_persona'");
											while ($row_ta = mysqli_fetch_array($sql_tabla)) {
												$cod_tabla = $row_ta['cod_tabla'];
											}
											// Obtener el ultimo cod_persona
											$sql_persona = mysqli_query($con, "SELECT cod_persona FROM tbl_persona ORDER BY cod_persona DESC LIMIT 0,1");
											while ($row = mysqli_fetch_array($sql_persona)) {
												$cod_persona = $row['cod_persona'];
											}
											// tbl_log
											$sql_log = mysqli_query($con, "INSERT INTO tbl_log(cod_tipolog_log, cod_tabla_log, codigo_log, cod_usuario_log) 
												VALUES(1, $cod_tabla, $cod_persona, $cod_usuario)");
										}
									}else{
										// MODIFICAR DATOS DE tbl_persona
										// UPDATE tbl_persona
										$update_persona = mysqli_query($con, "UPDATE tbl_persona SET nombre_per = '$nombre', apellido_per = '$apellido', 
											carnet_per = '$carnet', cod_expedido_per = $cod_expedido, 
											fecha_nacimiento_per = '$fechanac', cod_pais_per = $pais, cod_departamento_per = $departamento, celular_per = '$celular', 
											celular2_per = '$celular2', cod_sexo_per = $sexo, correo_per = '$correo', direccion_per = '$direccion', 
											nombre_factura_per = '$nombre_fact', nit_factura_per = '$nit_fact' 
											WHERE cod_persona = $cod_persona");
										if(mysqli_affected_rows($con) > 0)
											$dato_per = 1;
									}

									$cod_carrera = $_POST['carrera'];
									$sucursal_est = $_POST['sucursal'];
									$cod_plan = $_POST['plan'];
									$cod_formallegada = $_POST['forma_llegada'];
									$nro_titulo = '0';
									if (isset($_POST['nro_titulo']) && $_POST['nro_titulo'] != "")
										$nro_titulo = $_POST['nro_titulo'];
									$anho_titulo = '0';
									if (isset($_POST['anho_titulo']) && $_POST['anho_titulo'] != "")
										$anho_titulo = $_POST['anho_titulo'];
									$colegio = $_POST['colegio'];
									$tipo_estudiante = $_POST['tipo_estudiante'];
									$tipo_modalidad = $_POST['tipo_modalidad'];
									$cod_turno = $_POST['turno'];
									$observacion = $_POST['observacion'];

									if($_POST['tipo'] == "guardar"){
										// INSERT tbl_persona
										$insert_persona = mysqli_query($con, "INSERT INTO tbl_estudiante(cod_persona_est, cod_plan_est, cod_sucursal_est, cod_carrera_est, 
											nro_titulo_est, anho_titulo_est, cod_colegio_est, cod_formallegada_est, observacion_est, cod_tipoestudiante_est, cod_tipomodalidad_est, cod_turno_est) 
											VALUES($cod_persona, $cod_plan, $sucursal_est, $cod_carrera, '$nro_titulo', $anho_titulo, $colegio, $cod_formallegada, '$observacion', $tipo_estudiante, $tipo_modalidad, $cod_turno)");
										if(mysqli_affected_rows($con) > 0){
											// Obtener el codigo de la tabla
											$cod_tabla = 0;
											$sql_tabla = mysqli_query($con, "SELECT cod_tabla FROM tbl_tabla WHERE nombre_tabla = 'tbl_estudiante'");
											while ($row_ta = mysqli_fetch_array($sql_tabla)) {
												$cod_tabla = $row_ta['cod_tabla'];
											}
											// Obtener el ultimo cod_estudiante
											$codigo = 0;
											$sql_estudiante = mysqli_query($con, "SELECT cod_estudiante FROM tbl_estudiante ORDER BY cod_estudiante DESC LIMIT 0,1");
											while ($row_es = mysqli_fetch_array($sql_estudiante)) {
												$codigo = $row_es['cod_estudiante'];
											}
											// tbl_log
											$sql_log = mysqli_query($con, "INSERT INTO tbl_log(cod_tipolog_log, cod_tabla_log, codigo_log, cod_usuario_log) 
												VALUES(1, $cod_tabla, $codigo, $cod_usuario)");
											?>
											<div class="text-center">
												<h2 class="text-primary">Registro Guardado.!</h2>
											</div>

											<script>
												location.replace("requisito_registro.php?cod=<?php echo $codigo; ?>");
											</script>
											<?php
										}else{
											?>
											<div class="text-center">
												<h2 class="text-danger">Error al guardar el registro.!</h2>
											</div>
											<?php
										}
									}elseif($_POST['tipo'] == "modificar"){
										$cod_estudiante = $_POST['codigo'];

										// UPDATE tbl_persona
										$update_persona = mysqli_query($con, "UPDATE tbl_estudiante SET cod_carrera_est = $cod_carrera, cod_plan_est = $cod_plan, cod_sucursal_est = $sucursal_est, 
											nro_titulo_est = '$nro_titulo', anho_titulo_est = $anho_titulo, cod_colegio_est = $colegio, cod_formallegada_est = $cod_formallegada, 
											observacion_est = '$observacion', cod_tipoestudiante_est = $tipo_estudiante, cod_tipomodalidad_est = $tipo_modalidad, cod_turno_est = $cod_turno  
											WHERE cod_estudiante = $cod_estudiante");
										if(mysqli_affected_rows($con) > 0 || $dato_per == 1){
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
									<a href="estudiante.php">Volver al Inicio</a>
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

		<!-- <script>
			function requisito(codigo) {
  			location.replace("requisito_guardar.php?cod="+codigo);
			}
		</script>	 -->
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