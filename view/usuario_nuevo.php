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
								$cod_persona = 0;
								$nombre = "";
								if (isset($_GET['cod'])) {
									$cod_persona = $_GET['cod'];

									$sql_persona = mysqli_query($con, "SELECT cod_persona, nombre_per, apellido_per, carnet_per FROM tbl_persona 
										WHERE cod_persona = $cod_persona");
									while ($row_p = mysqli_fetch_array($sql_persona)) {
										$cod_persona = $row_p['cod_persona'];
										$nombre = $row_p['nombre_per']." - ".$row_p['apellido_per'];
									}
								}
								?>
								<center><h2>Gestión de Usuarios</h2></center>
								<h4><a href="usuario.php"><i class="ion-arrow-left-c"></i>Volver Atras</a></h4>
								<form action="usuario_guardar.php" method="POST">
									<input type="hidden" name="codigo" value="<?php echo $cod_persona; ?>">
									<input type="hidden" name="tipo" value="guardar">
									<div class="row">
										<div class="col-sm-1"></div>
										<div class="col-sm-11"><h5 class="text-primary">Buscar:</h5></div>
									</div>
									<fieldset>
										<div class="form-group row">
											<div class="col-sm-1"></div>
											<div class="col-sm-3">
												<input class="form-control" type="text" id="buscar_nombre" name="nombre" placeholder="... Por Nombre">
											</div>
											<div class="col-sm-3">
												<input class="form-control" type="text" id="buscar_apellido" name="apellido" placeholder="... Por Apellido">
											</div>
											<div class="col-sm-2">
												<input class="form-control" type="text" id="buscar_carnet" name="carnet" placeholder="... Por Carnet">
											</div>
										</div>
									</fieldset>
									<div class="row">
										<div class="col-sm-1"></div>
										<div class="col-sm-10">
											<div class="card bg-blue-grey-50">
												<div class="card-body">
													<div id="resultado_est" ></div>
												</div>
											</div>
										</div>
									</div>
									<hr width="80%">
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
												<input type="text" name="nombre_us" class="form-control" placeholder="..." required="">
											</div>
											<div class="col-sm-4">
												<label class="col-form-label">CORREO</label>
												<input type="email" name="correo" class="form-control" placeholder="mail@example.com" required="">
											</div>
											<div class="col-sm-3">
												<label class="col-form-label">PERFIL</label>
												<select class="form-control" name="perfil" id="select2-1" placeholder="..." required="">
													<option value="">...</option>
													<?php
													$sql_perfil = mysqli_query($con, "SELECT cod_perfil, nombre_perfil FROM tbl_perfil 
														WHERE estado_perfil = 1 ORDER BY nombre_perfil");
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
										<button class="btn btn-primary pull-right" type="submit" <?php if($cod_persona == 0){echo "disabled"; } ?> >Guardar el Registro</button>
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

		<script language="JavaScript" type="text/JavaScript">
			$(document).ready(function(){
				$('.select2-all').select2({
					placeholder: "..."
				});

				$("#buscar_carnet").keyup(function(e){
					if($(this).val().length > 2){
						carnet = $("#buscar_carnet").val();
						$.ajax({
							type: "POST",
							url: "../class/buscar_usuario_reg.php",
							data: 'carn='+carnet,
							dataType: "html",
							beforeSend: function(){
								$("#resultado_est").empty();
								$("#resultado_est").html("<div class='text-center'><img width='10%' src='img/load.gif'/></div>");
							},
							error: function(){
								alert("Error al buscar los registros");
							},
							success: function(data){
								$("#resultado_est").empty();
								$("#resultado_est").append(data);
							}
						});
					}
				});

				$("#buscar_nombre, #buscar_apellido").keyup(function(e){
					if($(this).val().length > 2){
						nombre = $("#buscar_nombre").val();
						apellido = $("#buscar_apellido").val();
						$.ajax({
							type: "POST",
							url: "../class/buscar_usuario_reg.php",
							data: "nom="+nombre+'&ape='+apellido,
							dataType: "html",
							beforeSend: function(){
								$("#resultado_est").empty();
								$("#resultado_est").html("<div class='text-center'><img width='10%' src='img/load.gif'/></div>");
							},
							error: function(){
								alert("Error al buscar los registros");
							},
							success: function(data){
								$("#resultado_est").empty();
								$("#resultado_est").append(data);
							}
						});
					}
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