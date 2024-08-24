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
		<title>IPAX Studio - SIS</title>
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
								<center><h2>Gestión de Carrera</h2></center>
								<h4><a href="carrera.php"><i class="ion-arrow-left-c"></i>Volver Atras</a></h4>
								<?php
								$cod_carrera = 0;
								if(isset($_GET['cod'])){
									$cod_carrera = $_GET['cod'];

									$cod_nivel = 0;
									$carrera = ""; $sigla = ""; $cod_tipocarrera = "";
									$mensualidad = ""; $duracion = "";
									$sql_carrera = mysqli_query($con, "SELECT * FROM tbl_carrera, tbl_nivel WHERE cod_nivel_car = cod_nivel AND cod_carrera = $cod_carrera");
									while ($row_p = mysqli_fetch_array($sql_carrera)) {
										$carrera = $row_p['nombre_car'];
										$sigla = $row_p['sigla_car'];
										$cod_nivel = $row_p['cod_nivel'];
										$nombre_niv = $row_p['nombre_niv'];
										$res_min = $row_p['resolucion_ministerial_car'];
										$mensualidad = $row_p['mensualidad_car'];
										$duracion = $row_p['duracion_car'];
									}
								}
								?>
								<form action="carrera_guardar.php" method="POST">
									<input type="hidden" name="cod_carrera" value="<?php echo $cod_carrera; ?>">
									<input type="hidden" name="tipo" value="modificar">
									<fieldset>
										<div class="form-group row">
											<div class="col-sm-7">
												<label class="col-form-label">SUB SEDE</label>
												<select multiple data-placeholder="...SUB SEDES" data-minimum-results-for-search="10" tabindex="-1" class="select2 form-control" id="sucursal" name="sucursal[]" >
													<!-- <option value=""></option> -->
													<?php
													$sql_sucursal = mysqli_query($con, "SELECT cod_sucursal, nombre_suc FROM tbl_sucursal 
														WHERE estado_suc = 1");
													while ($row_s = mysqli_fetch_array($sql_sucursal)) {
														$cod_suc = $row_s['cod_sucursal'];
														$selected = "";
														$sql_carsuc = mysqli_query($con, "SELECT cod_carrera_sucursal FROM tbl_carrera_sucursal WHERE cod_carrera_carsuc = $cod_carrera 
															AND cod_sucursal_carsuc = $cod_suc AND estado_carsuc = 1");
														if(mysqli_num_rows($sql_carsuc) > 0)
															$selected = 'selected = ""';
														?>
														<option value="<?php echo $row_s['cod_sucursal']; ?>" <?php echo $selected; ?> ><?php echo $row_s['nombre_suc']; ?></option>
														<?php
													}
													?>
												</select>
											</div>
											<div class="col-sm-5">
												<label class="col-form-label">NIVEL</label>
												<select class="form-control" name="nivel" id="select2-1" placeholder="..." required="">
													<option value="<?php echo $cod_nivel; ?>"><?php echo $nombre_niv; ?></option>
													<?php
													$sql_nivel = mysqli_query($con, "SELECT cod_nivel, nombre_niv FROM tbl_nivel 
														WHERE estado_niv = 1 AND cod_nivel != $cod_nivel");
													while ($row_n = mysqli_fetch_array($sql_nivel)) {
														?>
														<option value="<?php echo $row_n['cod_nivel']; ?>"><?php echo $row_n['nombre_niv']; ?></option>
														<?php
													}
													?>
												</select>
											</div>
										</div>
									</fieldset>
									<fieldset>
										<div class="form-group row">
											<div class="col-sm-6">
												<label class="col-form-label">CARRERA</label>
												<input class="form-control" type="text" name="carrera" required="" value="<?php echo $carrera; ?>">
											</div>
											<div class="col-sm-2">
												<label class="col-form-label">SIGLA</label>
												<input class="form-control" type="text" name="sigla" required="" value="<?php echo $sigla; ?>">
											</div>
											<div class="col-sm-4">
												<label class="col-form-label">RES. MINISTERIAL</label>
												<input class="form-control" type="text" name="res_min" required="" value="<?php echo $res_min; ?>">
											</div>
										</div>
									</fieldset>
									<fieldset>
										<div class="form-group row">
											<div class="col-sm-2">
												<label class="col-form-label">MENSUALIDADES POR AÑO</label>
												<input class="form-control" type="text" name="mensualidad" id="mensualidad" placeholder="..." required="" value="<?php echo $mensualidad; ?>">
											</div>
											<div class="col-sm-4">
												<label class="col-form-label">DURACIÓN</label>
												<input class="form-control" type="text" name="duracion" placeholder="..." required="" value="<?php echo $duracion; ?>">
											</div>
										</div>
									</fieldset>

									<div class="text-center">
										<button class="btn btn-primary pull-right" type="submit">Modificar el Registro</button>
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

				$("#mensualidad").keyup(function (){
					this.value = (this.value + '').replace(/[^0-9]/g, '');
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