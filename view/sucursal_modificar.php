<?php
include ('../conf/funciones.php');
include("../conf/mysql.php");
$cod_usuario = 0;
if (verificar_usuario()){
	$cod_usuario = $_SESSION['cod_usuario'];
	$nombre_pagina = "sucursal.php";
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
								<center><h2>Gestión de Sub Sedes</h2></center>
								<h4><a href="sucursal.php"><i class="ion-arrow-left-c"></i>Volver Atras</a></h4>
								<?php
								$cod_sucursal = 0;
								if(isset($_GET['cod'])){
									$cod_sucursal = $_GET['cod'];

									$nombre = "";
									$nit = "";
									$telefono = "";
									$direccion = "";
									$municipio = "";
									$sigla = "";
									$codigo_siat = "";
									$sql_sucursal = mysqli_query($con, "SELECT * FROM tbl_sucursal WHERE cod_sucursal = $cod_sucursal");
									while ($row_s = mysqli_fetch_array($sql_sucursal)) {
										$nombre = $row_s['nombre_suc'];
										$nit = $row_s['nit_suc'];
										$telefono = $row_s['telefono_suc'];
										$direccion = $row_s['direccion_suc'];
										$municipio = $row_s['municipio_suc'];
										$sigla = $row_s['sigla_suc'];
										$codigo_siat = $row_s['codigo_siat_suc'];
									}
								}
								?>
								<form action="sucursal_guardar.php" method="POST">
									<input type="hidden" name="cod_sucursal" value="<?php echo $cod_sucursal; ?>">
									<input type="hidden" name="tipo" value="modificar">
									<fieldset>
										<div class="form-group row">
											<div class="col-sm-7">
												<label class="col-form-label">NOMBRE SUB SEDE</label>
												<input class="form-control" type="text" name="nombre" placeholder="..." required="" value="<?php echo $nombre; ?>">
											</div>
											<div class="col-sm-5">
												<label class="col-form-label">NIT</label>
												<input class="form-control" type="text" name="nit" placeholder="..." required="" value="<?php echo $nit; ?>">
											</div>
										</div>
									</fieldset>
									<fieldset>
										<div class="form-group row">
											<div class="col-sm-5">
												<label class="col-form-label">TELEFONO</label>
												<input class="form-control" type="text" name="telefono" placeholder="..." required="" value="<?php echo $telefono; ?>">
											</div>
											<div class="col-sm-7">
												<label class="col-form-label">DIRECCION</label>
												<input class="form-control" type="text" name="direccion" placeholder="..." required="" value="<?php echo $direccion; ?>">
											</div>
										</div>
									</fieldset>
									<fieldset>
										<div class="form-group row">
											<div class="col-sm-6">
												<label class="col-form-label">MUNICIPIO</label>
												<input class="form-control" type="text" name="municipio" placeholder="..." required="" value="<?php echo $municipio; ?>">
											</div>
											<div class="col-sm-4">
												<label class="col-form-label">SIGLA</label>
												<input class="form-control" type="text" name="sigla" placeholder="..." required="" value="<?php echo $sigla; ?>">
											</div>
											<div class="col-sm-2">
												<label class="col-form-label">CODIGO SIAT</label>
												<input class="form-control" type="text" name="codigo_siat" placeholder="..." required="" value="<?php echo $codigo_siat; ?>">
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