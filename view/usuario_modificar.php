<?php
include ('../conf/funciones.php');
include("../conf/mysql.php");
$cod_usuario = 0;
if (verificar_usuario()){
	$cod_usuario = $_SESSION['cod_usuario'];
	$nombre_pagina = "usuario.php";
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
								<?php
								$cod_usuario = 0;

								$nombre = "";
								$nombre_us = "";
								$correo = "";
								$cod_perfil = 0;
								if (isset($_GET['cod'])) {
									$cod_usuario = $_GET['cod'];

									$sql_usuario = mysqli_query($con, "SELECT nombre_per, apellido_per, nombre_us, correo_us, cod_perfil_us 
										FROM tbl_usuario, tbl_persona 
										WHERE cod_persona_us = cod_persona AND cod_usuario = $cod_usuario");
									while ($row_u = mysqli_fetch_array($sql_usuario)) {
										$nombre = $row_u['nombre_per']." - ".$row_u['apellido_per'];
										$nombre_us = $row_u['nombre_us'];
										$correo = $row_u['correo_us'];
										$cod_perfil = $row_u['cod_perfil_us'];
									}
								}
								?>
								<center><h2>Gestión de Usuarios</h2></center>
								<h4><a href="usuario.php"><i class="ion-arrow-left-c"></i>Volver Atras</a></h4>
								<form action="usuario_guardar.php" method="POST">
									<input type="hidden" name="codigo" value="<?php echo $cod_usuario; ?>">
									<input type="hidden" name="tipo" value="modificar">
									<fieldset>
										<div class="form-group row">
											<div class="col-sm-1"></div>
											<div class="col-sm-6">
												<label class="col-form-label">NOMBRE</label>
												<input class="form-control" type="text" name="nombre" placeholder="..." readonly="" value="<?php echo $nombre; ?>">
											</div>
										</div>
									</fieldset>
									<fieldset>
										<div class="form-group row">
											<div class="col-sm-1"></div>
											<div class="col-sm-3">
												<label class="col-form-label">USUARIO</label>
												<input type="text" name="nombre_us" class="form-control" placeholder="..." required="" value="<?php echo $nombre_us; ?>">
											</div>
											<div class="col-sm-4">
												<label class="col-form-label">CORREO</label>
												<input type="email" name="correo" class="form-control" placeholder="mail@example.com" required="" value="<?php echo $correo; ?>">
											</div>
											<div class="col-sm-3">
												<label class="col-form-label">PERFIL</label>
												<select class="form-control" name="perfil" id="select2-1" placeholder="..." required="">
													<?php
													$sql_perfil = mysqli_query($con, "SELECT cod_perfil, nombre_perfil FROM tbl_perfil 
														WHERE cod_perfil = $cod_perfil");
													while ($row_p = mysqli_fetch_array($sql_perfil)) {
														?>
														<option value="<?php echo $row_p['cod_perfil']; ?>"><?php echo $row_p['nombre_perfil']; ?></option>
														<?php
													}
													$sql_perfil = mysqli_query($con, "SELECT cod_perfil, nombre_perfil FROM tbl_perfil 
														WHERE estado_perfil = 1 AND cod_perfil != $cod_perfil ORDER BY nombre_perfil");
													while ($row_p = mysqli_fetch_array($sql_perfil)) {
														?>
														<option value="<?php echo $row_p['cod_perfil']; ?>"><?php echo $row_p['nombre_perfil']; ?></option>
														<?php
													}
													?>
												</select>
											</div>
										</div>
									</fieldset>

									<fieldset>
										<div class="form-group row">
											<div class="col-sm-1"></div>
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
														$sql_carsuc = mysqli_query($con, "SELECT cod_usuario_sucursal FROM tbl_usuario_sucursal WHERE cod_usuario_ususuc = $cod_usuario 
															AND cod_sucursal_ususuc = $cod_suc AND estado_ususuc = 1");
														if(mysqli_num_rows($sql_carsuc) > 0)
															$selected = 'selected = ""';
														?>
														<option value="<?php echo $row_s['cod_sucursal']; ?>" <?php echo $selected; ?> ><?php echo $row_s['nombre_suc']; ?></option>
														<?php
													}
													?>
												</select>
											</div>
										</div>
									</fieldset>

									<div class="text-center">
										<button class="btn btn-primary pull-right" type="submit" <?php if($cod_usuario == 0){echo "disabled"; } ?> >Guardar el Registro</button>
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