<?php
include ('../conf/funciones.php');
include("../conf/mysql.php");
$cod_usuario = 0;
if (verificar_usuario()){
	$cod_usuario = $_SESSION['cod_usuario'];
	$nombre_pagina = "submenu.php";
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
								<center><h2>Gestión de Submenús</h2></center>
								<h4><a href="submenu.php"><i class="ion-arrow-left-c"></i>Volver Atras</a></h4>
								<form action="submenu_guardar.php" method="POST">
									<input type="hidden" name="tipo" value="guardar">
									<fieldset>
										<div class="form-group row">
											<div class="col-sm-6">
												<label class="col-form-label">SUBMENÚ</label>
												<input class="form-control" type="text" name="submenu" placeholder="..." required="">
											</div>
											<div class="col-sm-6">
												<label class="col-form-label">ENLACE</label>
												<input class="form-control" type="text" name="enlace" placeholder="..." required="">
											</div>
										</div>
									</fieldset>
									<fieldset>
										<div class="form-group row">
											<div class="col-sm-6">
												<label class="col-form-label">MENÚ</label>
												<select class="form-control select2" name="menu" placeholder="..." required="">
													<option value="">...</option>
													<?php
													$sql_menu = mysqli_query($con, "SELECT cod_menu, nombre_menu FROM tbl_menu 
														WHERE estado_menu = 1 ORDER BY nombre_menu");
													while ($row_m = mysqli_fetch_array($sql_menu)) {
														?>
														<option value="<?php echo $row_m['cod_menu']; ?>"><?php echo $row_m['nombre_menu']; ?></option>
														<?php
													}
													?>
												</select>
											</div>
											<div class="col-sm-6">
												<label class="col-form-label">TIPO SUBMENÚ</label>
												<select class="form-control select2" name="tipo_submenu" placeholder="..." required="">
													<option value="">...</option>
													<?php
													$sql_tipo = mysqli_query($con, "SELECT cod_tiposubmenu, nombre_tiposubm FROM tbl_tipo_submenu 
														WHERE estado_tiposubm = 1");
													while ($row_t = mysqli_fetch_array($sql_tipo)) {
														?>
														<option value="<?php echo $row_t['cod_tiposubmenu']; ?>"><?php echo $row_t['nombre_tiposubm']; ?></option>
														<?php
													}
													?>
												</select>
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