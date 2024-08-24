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
								<center><h2>Gestión de Personas</h2></center>
								<h4><a href="persona.php"><i class="ion-arrow-left-c"></i>Volver Atras</a></h4>
								<form action="persona_guardar.php" method="POST">
									<input type="hidden" name="tipo" value="guardar">
									<fieldset>
										<div class="form-group row">
											<div class="col-sm-1"></div>
											<div class="col-sm-5">
												<label class="col-form-label">NOMBRE</label>
												<input class="form-control" type="text" name="nombre" placeholder="..." required="">
											</div>
											<div class="col-sm-5">
												<label class="col-form-label">APELLIDOS</label>
												<input class="form-control" type="text" name="apellido" placeholder="..." required="">
											</div>
										</div>
									</fieldset>

									<fieldset>
										<div class="form-group row">
											<div class="col-sm-1"></div>
											<div class="col-sm-3">
												<label class="col-form-label">CARNET</label>
												<input class="form-control" type="text" name="carnet" placeholder="..." required="">
											</div>
											<div class="col-sm-1">
												<label class="col-form-label">COMPL.</label>
												<input class="form-control" type="text" name="complemento" maxlength="3">
											</div>
											<div class="col-sm-1"></div>
											<div class="col-sm-2">
												<label class="col-form-label">EXPEDIDO</label>
												<select class="form-control select2-all" name="expedido" placeholder="..." required="">
													<option value="">...</option>
													<?php
													$sql_expedido = mysqli_query($con, "SELECT cod_expedido, sigla_expedido FROM tbl_expedido");
													while ($row_ex = mysqli_fetch_array($sql_expedido)) {
														?>
														<option value="<?php echo $row_ex['cod_expedido']; ?>"><?php echo $row_ex['sigla_expedido']; ?></option>
														<?php
													}
													?>
												</select>
											</div>
											<div class="col-sm-3">
												<label class="col-form-label">FECHA DE NAC.</label>
												<input type="text" id="fecha_nac" name="fecha_nac" class="form-control" placeholder="DD-MM-AAAA" required="required" maxlength="10">
											</div>
										</div>
									</fieldset>

									<fieldset>
										<div class="form-group row">
											<div class="col-sm-1"></div>
											<div class="col-sm-3">
												<label class="col-form-label">PAIS</label>
												<select class="form-control select2-all" name="pais" placeholder="..." required="">
													<option value="">...</option>
													<?php
													$sql_pais = mysqli_query($con, "SELECT cod_pais, nombre_pais FROM tbl_pais");
													while ($row_p = mysqli_fetch_array($sql_pais)) {
														?>
														<option value="<?php echo $row_p['cod_pais']; ?>"><?php echo $row_p['nombre_pais']; ?></option>
														<?php
													}
													?>
												</select>
											</div>
											<div class="col-sm-3">
												<label class="col-form-label">DEPARTAMENTO NAC.</label>
												<select class="form-control select2-all" name="departamento" placeholder="..." required="">
													<option value="">...</option>
													<?php
													$sql_departamento = mysqli_query($con, "SELECT cod_departamento, nombre_dep FROM tbl_departamento");
													while ($row_d = mysqli_fetch_array($sql_departamento)) {
														?>
														<option value="<?php echo $row_d['cod_departamento']; ?>"><?php echo $row_d['nombre_dep']; ?></option>
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
											<div class="col-sm-3">
												<label class="col-form-label">CELULAR</label>
												<input class="form-control" type="text" name="celular" placeholder="..." required="" onKeyPress="return numeros(event)">
											</div>
											<div class="col-sm-3">
												<label class="col-form-label">CELULAR 2</label>
												<input class="form-control" type="text" name="celular2" placeholder="..." onKeyPress="return numeros(event)">
											</div>
											<div class="col-sm-4">
												<label class="col-form-label">CORREO</label>
												<input class="form-control" type="mail" name="correo" placeholder="mail@example.com">
											</div>
										</div>
									</fieldset>

									<fieldset>
										<div class="form-group row">
											<div class="col-sm-1"></div>
											<div class="col-sm-2">
												<label class="col-form-label">SEXO</label>
												<select class="form-control select2-all" name="sexo" placeholder="..." required="">
													<option value="">...</option>
													<?php
													$sql_expedido = mysqli_query($con, "SELECT cod_sexo, nombre_sexo FROM tbl_sexo");
													while ($row_ex = mysqli_fetch_array($sql_expedido)) {
														?>
														<option value="<?php echo $row_ex['cod_sexo']; ?>"><?php echo $row_ex['nombre_sexo']; ?></option>
														<?php
													}
													?>
												</select>
											</div>
											<div class="col-sm-4">
												<label class="col-form-label">DIRECCIÓN</label>
												<input class="form-control" type="text" name="direccion" placeholder="...">
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
    <!-- MASK -->
    <script src="vendor/mask/jquery.maskedinput.js" type="text/javascript"></script>
    <!-- App script-->
    <script src="js/app.js"></script>
    <script type="text/javascript">
    	$(function() {
        $.mask.definitions['~'] = "[+-]";
        $("#fecha_nac").mask("99-99-9999",{placeholder:"DD-MM-AAAA"});
        $("input").blur(function() {
            $("#info").html("Unmasked value: " + $(this).mask());
        }).dblclick(function() {
            $(this).unmask();
        });
    	});

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