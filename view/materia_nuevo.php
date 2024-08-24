<?php
include ('../conf/funciones.php');
include("../conf/mysql.php");
$cod_usuario = 0;
if (verificar_usuario()){
	$cod_usuario = $_SESSION['cod_usuario'];
	$nombre_pagina = "materia.php";
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
								<center><h2>Gestión de Materias</h2></center>
								<h4><a href="materia.php"><i class="ion-arrow-left-c"></i>Volver Atras</a></h4>
								<form action="materia_guardar.php" method="POST">
									<input type="hidden" name="tipo" value="guardar">
									<fieldset>
										<div class="form-group row">
											<div class="col-sm-6">
												<label class="col-form-label">SUB SEDE</label>
												<select class="form-control select2-all" name="sucursal" id="sucursal" placeholder="..." required="">
													<option value=""></option>
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

									<fieldset>
										<div class="form-group row">
											<div class="col-sm-5">
												<label class="col-form-label">NIVEL</label>
												<select class="form-control select2-all" name="nivel" id="nivel" placeholder="..." required="">
													<option value="">...</option>
													<?php
													$sql_nivel = mysqli_query($con, "SELECT cod_nivel, nombre_niv FROM tbl_nivel 
														WHERE estado_niv = 1");
													while ($row_n = mysqli_fetch_array($sql_nivel)) {
														?>
														<option value="<?php echo $row_n['cod_nivel']; ?>"><?php echo $row_n['nombre_niv']; ?></option>
														<?php
													}
													?>
												</select>
											</div>
											<div class="col-sm-7">
												<label class="col-form-label">CARRERA</label>
												<select class="form-control select2-all" name="carrera" id="carrera" placeholder="..." required="">
													<option value="">...</option>
												</select>
											</div>
										</div>
									</fieldset>

									<fieldset>
										<div class="form-group row">
											<div class="col-sm-5">
												<label class="col-form-label">MATERIA</label>
												<input class="form-control" type="text" name="materia" placeholder="..." required="">
											</div>
											<div class="col-sm-2">
												<label class="col-form-label">SIGLA</label>
												<input class="form-control" type="text" name="sigla" placeholder="..." required="">
											</div>
											<div class="col-sm-5">
												<label class="col-form-label">PRE REQUISITO</label>
												<select class="form-control select2-all" name="prerequisito" id="prerequisito" placeholder="...">
													<option value="0">*SIN PRE-REQUISITO*</option>
												</select>
											</div>
										</div>
									</fieldset>

									<fieldset>
										<div class="form-group row">
											<div class="col-sm-3">
												<label class="col-form-label">ESTRUCTURA</label>
												<select class="form-control select2-all" name="estructura" id="estructura" placeholder="...">
													<option value="">...</option>
													<?php
													$sql_estructura = mysqli_query($con, "SELECT cod_estructura_materia, nombre_estmat FROM tbl_estructura_materia");
													while ($row_em = mysqli_fetch_array($sql_estructura)) {
														?>
														<option value="<?php echo $row_em['cod_estructura_materia']; ?>"><?php echo $row_em['nombre_estmat']; ?></option>
														<?php
													}
													?>
												</select>
											</div>
											<div class="col-sm-3">
												<label class="col-form-label">TIPO MATERIA</label>
												<select class="form-control select2-all" name="tipo_materia" placeholder="..." required="">
													<option value="">...</option>
													<?php
													$sql_tipo = mysqli_query($con, "SELECT cod_tipomateria, nombre_tipomat FROM tbl_tipo_materia 
														WHERE estado_tipomat = 1");
													while ($row_ti = mysqli_fetch_array($sql_tipo)) {
														?>
														<option value="<?php echo $row_ti['cod_tipomateria']; ?>"><?php echo $row_ti['nombre_tipomat']; ?></option>
														<?php
													}
													?>
												</select>
											</div>
											<div class="col-sm-3">
												<label class="col-form-label">HORAS SEMANALES</label>
												<input class="form-control" type="text" name="horas" placeholder="...">
											</div>
											<div class="col-sm-3">
												<?php
												$contenido_mat = 1;
												$sql_contenido = mysqli_query($con, "SELECT MAX(contenido_mat) as contenido FROM tbl_materia");
												if(mysqli_num_rows($sql_contenido) > 0){
													while($row_c = mysqli_fetch_array($sql_contenido)){
														$contenido_mat = $row_c['contenido'] + 1;
													}
												}
												?>
												<label class="col-form-label">CONTENIDO</label>
												<input type="text" class="form-control" name="contenido" id="contenido" value="<?php echo $contenido_mat; ?>" required="">
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

				$("#nivel").change(function(event){
    			$("#carrera").select2('val','...');
    			var cod = $("#nivel").find(':selected').val();
    			var suc = $("#sucursal").find(':selected').val();
    			$("#carrera").load('../class/buscar_select_carrera.php?cod='+cod+'&suc='+suc);	
    		});

    		$("#sucursal").change(function(event){
    			$("#carrera").select2('val','...');
    			var cod = $("#nivel").find(':selected').val();
    			var suc = $("#sucursal").find(':selected').val();
    			$("#carrera").load('../class/buscar_select_carrera.php?cod='+cod+'&suc='+suc);	
    		});

    		$("#carrera").change(function(event){
    			$("#prerequisito").select2('val','...');
    			var cod = $("#carrera").find(':selected').val();
    			$("#prerequisito").load('../class/buscar_select.php?tipo=prerequisito&cod='+cod);	
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