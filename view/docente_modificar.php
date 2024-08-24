<?php
include ('../conf/funciones.php');
include("../conf/mysql.php");
$cod_usuario = 0;
if (verificar_usuario()){
	$cod_usuario = $_SESSION['cod_usuario'];
	$nombre_pagina = "docente.php";
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
								$cod_docente = 0;

								$nombre = ""; $carnet = "";
								$cod_tituloprofesional = 0;
								if (isset($_GET['cod'])) {
									$cod_docente = $_GET['cod'];
									$sql_docente = mysqli_query($con, "SELECT nombre_per, apellido_per, carnet_per, cod_tituloprofesional_doc 
										FROM tbl_docente, tbl_persona 
										WHERE cod_persona_doc = cod_persona AND cod_docente = $cod_docente");
									while ($row_d = mysqli_fetch_array($sql_docente)) {
										$nombre = $row_d['nombre_per']." - ".$row_d['apellido_per'];
										$carnet = " - ".$row_d['carnet_per'];
										$cod_tituloprofesional = $row_d['cod_tituloprofesional_doc'];
									}
								}
								?>
								<center><h2>Gestión de Estudiantes</h2></center>
								<h4><a href="docente.php"><i class="ion-arrow-left-c"></i>Volver Atras</a></h4>
								<form action="docente_guardar.php" method="POST">
									<input type="hidden" name="codigo" value="<?php echo $cod_docente; ?>">
									<input type="hidden" name="tipo" value="modificar">
									<fieldset>
										<div class="form-group row">
											<div class="col-sm-2"></div>
											<div class="col-sm-8">
												<label class="col-form-label">NOMBRE</label>
												<input class="form-control" type="text" name="nombre" placeholder="..." readonly="" value="<?php echo $nombre.$carnet; ?>">
											</div>
										</div>
									</fieldset>
									<fieldset>
										<div class="form-group row">
											<div class="col-sm-2"></div>
											<div class="col-sm-4">
												<label class="col-form-label">SUB SEDE</label>
												<select multiple data-placeholder="...SUB SEDES" data-minimum-results-for-search="10" tabindex="-1" class="select2-all form-control" id="sucursal" name="sucursal[]" >
													<!-- <option value=""></option> -->
													<?php
													$sql_sucursal = mysqli_query($con, "SELECT cod_sucursal, sigla_suc FROM tbl_sucursal 
														WHERE estado_suc = 1");
													while ($row_s = mysqli_fetch_array($sql_sucursal)) {
														$cod_suc = $row_s['cod_sucursal'];
														$selected = "";
														$sql_carsuc = mysqli_query($con, "SELECT cod_docente_sucursal FROM tbl_docente_sucursal WHERE cod_docente_docsuc = $cod_docente 
															AND cod_sucursal_docsuc = $cod_suc AND estado_docsuc = 1");
														if(mysqli_num_rows($sql_carsuc) > 0)
															$selected = 'selected = ""';
														?>
														<option value="<?php echo $row_s['cod_sucursal']; ?>" <?php echo $selected; ?> ><?php echo $row_s['sigla_suc']; ?></option>
														<?php
													}
													?>
												</select>
											</div>
											<div class="col-sm-4">
												<label class="col-form-label">TIPO PROF.</label>
												<select class="form-control" name="titulo_profesional" id="select2-1" placeholder="..." required="">
													<?php
													$sql_titulo = mysqli_query($con, "SELECT cod_tituloprofesional, nombre_titprof FROM tbl_titulo_profesional 
														WHERE cod_tituloprofesional = $cod_tituloprofesional");
													while ($row_ti = mysqli_fetch_array($sql_titulo)) {
														?>
														<option value="<?php echo $row_ti['cod_tituloprofesional']; ?>"><?php echo $row_ti['nombre_titprof']; ?></option>
														<?php
													}
													$sql_titulo = mysqli_query($con, "SELECT cod_tituloprofesional, nombre_titprof FROM tbl_titulo_profesional 
														WHERE cod_tituloprofesional != $cod_tituloprofesional AND estado_titprof = 1 
														ORDER BY nombre_titprof");
													while ($row_ti = mysqli_fetch_array($sql_titulo)) {
														?>
														<option value="<?php echo $row_ti['cod_tituloprofesional']; ?>"><?php echo $row_ti['nombre_titprof']; ?></option>
														<?php
													}
													?>
												</select>
											</div>
										</div>
									</fieldset>
									<div class="text-center">
										<button class="btn btn-primary pull-right" type="submit" <?php if($cod_docente == 0){echo "disabled"; } ?> >Guardar el Registro</button>
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
    					url: "../class/buscar_docente_reg.php",
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
    					url: "../class/buscar_docente_reg.php",
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

    		$("#select2-1").change(function(event){
    			$("#select2-2").select2('val','...');
    			var cod = $("#select2-1").find(':selected').val();
    			$("#select2-2").load('../class/buscar_select_carrera.php?cod='+cod);	
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