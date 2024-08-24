<?php
include ('../conf/funciones.php');
include("../conf/mysql.php");
$cod_usuario = 0;
if (verificar_usuario()){
	$cod_usuario = $_SESSION['cod_usuario'];
	$nombre_pagina = "articulo.php";
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
								$cod_articulo = 0;
								if (isset($_GET['cod'])) {
									$cod_articulo = $_GET['cod'];

									$nombre = "";
									$precio = "";
									$cod_tipoarticulo = 0;
									$sucursal = 0;
									$sql_articulo = mysqli_query($con, "SELECT * FROM tbl_articulo WHERE cod_articulo = $cod_articulo");
									while ($row_a = mysqli_fetch_array($sql_articulo)) {
										$nombre = $row_a['nombre_art'];
										$precio = $row_a['precio_art'];
										$cod_tipoarticulo = $row_a['cod_tipoarticulo_art'];
										$sucursal = $row_a['cod_sucursal_art'];
									}
								}
								?>
								<center><h2>Gestión de Artículos</h2></center>
								<h4><a href="articulo.php"><i class="ion-arrow-left-c"></i>Volver Atras</a></h4>
								<form action="articulo_guardar.php" method="POST">
									<input type="hidden" name="cod_articulo" value="<?php echo $cod_articulo; ?>">
									<input type="hidden" name="tipo" value="modificar">
									<fieldset>
										<div class="form-group row">
											<div class="col-sm-1"></div>
											<div class="col-sm-7">
												<label class="col-form-label">NOMBRE</label>
												<input class="form-control" type="text" name="nombre" placeholder="..." required="" value="<?php echo $nombre; ?>">
											</div>
											<div class="col-sm-3">
												<label class="col-form-label">PRECIO</label>
												<input class="form-control" type="number" name="precio" placeholder="0.00" required="" onKeyPress="return numeros(event)" value="<?php echo $precio; ?>">
											</div>
										</div>
									</fieldset>

									<fieldset>
										<div class="form-group row">
											<div class="col-sm-1"></div>
											<div class="col-sm-4">
												<label class="col-form-label">TIPO ARTÍCULO</label>
												<select class="form-control select2-all" name="tipo_articulo" placeholder="..." required="">
													<?php
													$sql_tipo = mysqli_query($con, "SELECT cod_tipoarticulo, nombre_tipoart FROM tbl_tipo_articulo 
														WHERE cod_tipoarticulo = $cod_tipoarticulo");
													while ($row_ti = mysqli_fetch_array($sql_tipo)) {
														?>
														<option value="<?php echo $row_ti['cod_tipoarticulo']; ?>"><?php echo $row_ti['nombre_tipoart']; ?></option>
														<?php
													}
													$sql_tipo = mysqli_query($con, "SELECT cod_tipoarticulo, nombre_tipoart FROM tbl_tipo_articulo 
														WHERE cod_tipoarticulo != $cod_tipoarticulo");
													while ($row_ti = mysqli_fetch_array($sql_tipo)) {
														?>
														<option value="<?php echo $row_ti['cod_tipoarticulo']; ?>"><?php echo $row_ti['nombre_tipoart']; ?></option>
														<?php
													}
													?>
												</select>
											</div>
											<div class="col-sm-6">
												<label class="col-form-label">SUB SEDE</label>
												<select class="form-control select2-all" name="sucursal" placeholder="..." required="">
													<?php
													$sql_sucursal = mysqli_query($con, "SELECT cod_sucursal, nombre_suc FROM tbl_sucursal 
														WHERE cod_sucursal = $sucursal");
													while ($row_s = mysqli_fetch_array($sql_sucursal)) {
														?>
														<option value="<?php echo $row_s['cod_sucursal']; ?>"><?php echo $row_s['nombre_suc']; ?></option>
														<?php
													}

													$sql_sucursal = mysqli_query($con, "SELECT cod_sucursal, nombre_suc FROM tbl_sucursal 
														WHERE estado_suc = 1 AND cod_sucursal != $sucursal");
													while ($row_s = mysqli_fetch_array($sql_sucursal)) {
														?>
														<option value="<?php echo $row_s['cod_sucursal']; ?>"><?php echo $row_s['nombre_suc']; ?></option>
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