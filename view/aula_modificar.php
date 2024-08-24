<?php
include ('../conf/funciones.php');
include("../conf/mysql.php");
$cod_usuario = 0;
if (verificar_usuario()){
	$cod_usuario = $_SESSION['cod_usuario'];
	$nombre_pagina = "aula.php";
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
								<center><h2>Gestión de Aulas</h2></center>
								<h4><a href="aula.php"><i class="ion-arrow-left-c"></i>Volver Atras</a></h4>
								<?php
								$cod_aula = 0;
								if(isset($_GET['cod'])){
									$cod_aula = $_GET['cod'];

									$cod_sucursal = ""; $nombre_suc = "";
									$nombre_au = ""; $capacidad = "";
									$sql_aula = mysqli_query($con, "SELECT nombre_au, capacidad_au, cod_sucursal, nombre_suc 
										FROM tbl_aula, tbl_sucursal 
										WHERE cod_sucursal = cod_sucursal_au AND cod_aula = $cod_aula");
									while ($row_a = mysqli_fetch_array($sql_aula)) {
										$cod_sucursal = $row_a['cod_sucursal'];
										$nombre_suc = $row_a['nombre_suc'];
										$nombre_au = $row_a['nombre_au'];
										$capacidad = $row_a['capacidad_au'];
									}
								}
								?>
								<form action="aula_guardar.php" method="POST">
									<input type="hidden" name="tipo" value="modificar">
									<input type="hidden" name="cod_aula" value="<?php echo $cod_aula; ?>">
									<fieldset>
										<div class="form-group row">
											<div class="col-sm-3"></div>
											<div class="col-sm-6">
												<label class="col-form-label">SUB SEDE</label>
												<select class="form-control select2-all" name="sucursal" id="sucursal" placeholder="..." required="">
													<option value="<?php echo $cod_sucursal; ?>"><?php echo $nombre_suc; ?></option>
													<?php
													$sql_sucursal = mysqli_query($con, "SELECT cod_sucursal, nombre_suc FROM tbl_sucursal 
														WHERE estado_suc = 1 AND cod_sucursal != $cod_sucursal");
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
									<fieldset>
										<div class="form-group row">
											<div class="col-sm-3"></div>
											<div class="col-sm-4">
												<label class="col-form-label">AULA</label>
												<input class="form-control" type="text" name="aula" placeholder="..." required="" value="<?php echo $nombre_au; ?>">
											</div>
											<div class="col-sm-2">
												<label class="col-form-label">CAPACIDAD</label>
												<input class="form-control" type="text" name="capacidad" placeholder="..." required="" value="<?php echo $capacidad; ?>">
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