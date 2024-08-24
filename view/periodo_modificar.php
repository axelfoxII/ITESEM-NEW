<?php
include ('../conf/funciones.php');
include("../conf/mysql.php");
$cod_usuario = 0;
if (verificar_usuario()){
	$cod_usuario = $_SESSION['cod_usuario'];
	$nombre_pagina = "periodo.php";
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
		<!-- Datepicker-->
    <link rel="stylesheet" href="vendor/bootstrap-datepicker/dist/css/bootstrap-datepicker3.css">
		<!-- Select2-->
    <link rel="stylesheet" href="vendor/select2/dist/css/select2.css">
    <!-- ColorPicker-->
    <link rel="stylesheet" href="vendor/bootstrap-colorpicker/dist/css/bootstrap-colorpicker.css">
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
								<center><h2>Gestión de Periodos</h2></center>
								<h4><a href="periodo.php"><i class="ion-arrow-left-c"></i>Volver Atras</a></h4>
								<?php
								$cod_periodo = 0;
								if(isset($_GET['cod'])){
									$cod_periodo = $_GET['cod'];

									$sigla = ""; $descripcion = ""; $cod_tipoplan = "";
									$precio = 0; $precio_total = 0;
									$sql_plan = mysqli_query($con, "SELECT * FROM tbl_periodo WHERE cod_periodo = $cod_periodo");
									while ($row_p = mysqli_fetch_array($sql_plan)) {
										$nombre_peri = $row_p['nombre_peri'];
										$cod_gestion_peri = $row_p['cod_gestion_peri'];
										$cod_tipoperiodo_peri = $row_p['cod_tipoperiodo_peri'];
										$fecha_ini = $fecha_fin = date_format(date_create($row_p['fecha_ini_peri']), "d-m-Y");
										$fecha_fin = $fecha_fin = date_format(date_create($row_p['fecha_fin_peri']), "d-m-Y");
									}
								}
								?>
								<form action="periodo_guardar.php" method="POST">
									<input type="hidden" name="cod_periodo" value="<?php echo $cod_periodo; ?>">
									<input type="hidden" name="tipo" value="modificar">
									<fieldset>
										<div class="form-group row">
											<div class="col-sm-3"></div>
											<div class="col-sm-6">
												<label class="col-form-label">TIPO PERIODO</label>
												<select class="form-control select2-all" name="tipo_periodo" placeholder="..." required="">
													<?php
													$sql_tipo = mysqli_query($con, "SELECT cod_tipoperiodo, nombre_tipper FROM tbl_tipo_periodo 
														WHERE cod_tipoperiodo = $cod_tipoperiodo_peri");
													while ($row_ti = mysqli_fetch_array($sql_tipo)) {
														?>
														<option value="<?php echo $row_ti['cod_tipoperiodo']; ?>"><?php echo $row_ti['nombre_tipper']; ?></option>
														<?php
													}
													$sql_tipo = mysqli_query($con, "SELECT cod_tipoperiodo, nombre_tipper FROM tbl_tipo_periodo 
														WHERE cod_tipoperiodo != $cod_tipoperiodo_peri");
													while ($row_ti = mysqli_fetch_array($sql_tipo)) {
														?>
														<option value="<?php echo $row_ti['cod_tipoperiodo']; ?>"><?php echo $row_ti['nombre_tipper']; ?></option>
														<?php
													}
													?>
												</select>
											</div>
										</div>
									</fieldset>

									<fieldset>
										<div class="form-group row">
											<div class="col-sm-3"></div>
											<div class="col-sm-3">
												<label class="col-form-label">GESTIÓN</label>
												<select class="form-control select2-all" name="gestion" placeholder="..." required="">
													<?php
													$sql_gestion = mysqli_query($con, "SELECT cod_gestion, nombre_gest FROM tbl_gestion 
														WHERE cod_gestion = $cod_gestion_peri");
													while ($row_g = mysqli_fetch_array($sql_gestion)) {
														?>
														<option value="<?php echo $row_g['cod_gestion']; ?>"><?php echo $row_g['nombre_gest']; ?></option>
														<?php
													}
													$sql_gestion = mysqli_query($con, "SELECT cod_gestion, nombre_gest FROM tbl_gestion 
														WHERE cod_gestion != $cod_gestion_peri");
													while ($row_g = mysqli_fetch_array($sql_gestion)) {
														?>
														<option value="<?php echo $row_g['cod_gestion']; ?>"><?php echo $row_g['nombre_gest']; ?></option>
														<?php
													}
													?>
												</select>
											</div>
											<div class="col-sm-3">
												<label class="col-form-label">NOMBRE PERIODO</label>
												<select class="form-control select2-all" name="nombre" placeholder="..." required="">
													<option value="<?php echo $nombre_peri; ?>"><?php echo $nombre_peri; ?></option>
													<option value="AÑO">AÑO</option>
													<option value="1ºSEMESTRE">1ºSEMESTRE</option>
													<option value="2ºSEMESTRE">2ºSEMESTRE</option>
													<option value="1º - Ene">1º - Ene</option>
													<option value="2º - Feb">2º - Feb</option>
													<option value="3º - Mar">3º - Mar</option>
													<option value="4º - Abr">4º - Abr</option>
													<option value="5º - May">5º - May</option>
													<option value="6º - Jun">6º - Jun</option>
													<option value="7º - Jul">7º - Jul</option>
													<option value="8º - Ago">8º - Ago</option>
													<option value="9º - Sep">9º - Sep</option>
													<option value="10º - Oct">10º - Oct</option>
													<option value="11º - Nov">11º - Nov</option>
													<option value="12º - Di">12º - Dic</option>
													<option value="VERANO">VERANO</option>
													<option value="INVIERNO">INVIERNO</option>
												</select>
											</div>
										</div>
									</fieldset>

									<fieldset>
										<div class="form-group row">
											<div class="col-sm-3"></div>
											<div class="col-sm-6">
												<div class="rel-wrapper ui-datepicker ui-datepicker-popup dp-theme-primary" id="example-datepicker-container-5">
													<div class="input-daterange" id="example-datepicker-5">
														<div class="form-group row">
															<div class="col-6">
																<p>FECHA DE INICIO</p>
																<input class="form-control" type="text" name="fecha_inicio" value="<?php echo $fecha_ini; ?>" placeholder="DD-MM-YYYY" autocomplete="off" required="">
															</div>
															<div class="col-6">
																<p>FECHA DE FIN</p>
																<input class="form-control" type="text" name="fecha_fin" value="<?php echo $fecha_fin; ?>" placeholder="DD-MM-YYYY" autocomplete="off" required="">
															</div>
														</div>
													</div>
												</div>
											</div>
										</div>
									</fieldset>

									<div class="text-center">
										<button class="btn btn-primary pull-right" type="submit">Guardar el Registro</button>
									</div>
								</form>
							</div>
						</div>
					</div>
				</section>
			</main>
		</div>
		<!-- End Search template-->
		<?php include('ajuste.php'); ?>
		<!-- End Settings template-->
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
    <!-- Datepicker-->
    <script src="vendor/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>
    <!-- Select2-->
    <script src="vendor/select2/dist/js/select2.js"></script>
    <!-- Clockpicker-->
    <script src="vendor/clockpicker/dist/bootstrap-clockpicker.js"></script>
    <!-- ColorPicker-->
    <script src="vendor/bootstrap-colorpicker/dist/js/bootstrap-colorpicker.js"></script>
    <!-- jQuery Form Validation-->
    <script src="vendor/jquery-validation/dist/jquery.validate.js"></script>
    <script src="vendor/jquery-validation/dist/additional-methods.js"></script>
    <!-- App script-->
    <script src="js/app.js"></script>

    <script>
    	$(document).ready(function(){
    		$('.select2-all').select2({
					placeholder: "..."
				});
    	});
    </script>
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