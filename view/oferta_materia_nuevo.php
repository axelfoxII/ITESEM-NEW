<?php
include ('../conf/funciones.php');
include("../conf/mysql.php");
$cod_usuario = 0;
if (verificar_usuario()){
	$cod_usuario = $_SESSION['cod_usuario'];
	$nombre_pagina = "oferta_materia.php";
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
								<center><h2>Gestión de Ofertas de Materias</h2></center>
								<h4><a href="oferta_materia.php"><i class="ion-arrow-left-c"></i>Volver Atras</a></h4>
								<form action="oferta_materia_guardar.php" method="POST">
									<input type="hidden" name="tipo" value="guardar">
									<fieldset>
										<div class="form-group row">
											<div class="col-sm-3">
												<label class="col-form-label">SUCURSAL</label>
												<select name="sucursal" id="sucursal" class="form-control select2-all" required="">
													<?php
													$sql_sucursal = mysqli_query($con, "SELECT cod_sucursal, sigla_suc FROM tbl_sucursal, tbl_usuario_sucursal 
														WHERE cod_sucursal = cod_sucursal_ususuc AND cod_usuario_ususuc = $cod_usuario AND estado_ususuc = 1");
													while($row_us = mysqli_fetch_array($sql_sucursal)){
														?>
														<option value="<?php echo $row_us['cod_sucursal']; ?>"><?php echo $row_us['sigla_suc']; ?></option>
														<?php
													}
													?>
												</select>
											</div>
											<div class="col-sm-3">
												<label class="col-form-label">NIVEL</label>
												<select class="form-control select2-all" name="nivel" id="nivel" placeholder="..." required="">
													<option value="">...</option>
													<?php
													$sql_nivel = mysqli_query($con, "SELECT cod_nivel, nombre_niv FROM tbl_nivel 
														WHERE estado_niv = 1");
													while ($row_ni = mysqli_fetch_array($sql_nivel)) {
														?>
														<option value="<?php echo $row_ni['cod_nivel']; ?>"><?php echo $row_ni['nombre_niv']; ?></option>
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
												<select class="form-control select2-all" name="carrera" id="carrera" placeholder="..." required="">
													<option value="">...</option>
												</select>
											</div>
											<div class="col-sm-6">
												<label class="col-form-label">MATERIA</label>
												<select class="form-control select2-all" name="materia" id="materia" placeholder="..." required="">
													<option value="">...</option>
												</select>
											</div>
										</div>
									</fieldset>

									<fieldset>
										<div class="form-group row">
											<div class="col-sm-2">
												<label class="col-form-label">TIPO PERIODO</label>
												<select class="form-control select2-all" name="tipo_periodo" id="tipo_periodo" placeholder="..." required="">
													<option value="">...</option>
													<?php
													$sql_tipo = mysqli_query($con, "SELECT cod_tipoperiodo, nombre_tipper FROM tbl_tipo_periodo WHERE estado_tipper = 1");
													while ($row_ti = mysqli_fetch_array($sql_tipo)) {
														?>
														<option value="<?php echo $row_ti['cod_tipoperiodo']; ?>"><?php echo $row_ti['nombre_tipper']; ?></option>
														<?php
													}
													?>
												</select>
											</div>
											<div class="col-sm-2">
												<label class="col-form-label">GESTIÓN</label>
												<select class="form-control select2-all" name="gestion" id="gestion" placeholder="..." required="">
													<option value="">...</option>
													<?php
													$sql_gestion = mysqli_query($con, "SELECT cod_gestion, nombre_gest FROM tbl_gestion");
													while ($row_g = mysqli_fetch_array($sql_gestion)) {
														?>
														<option value="<?php echo $row_g['cod_gestion']; ?>"><?php echo $row_g['nombre_gest']; ?></option>
														<?php
													}
													?>
												</select>
											</div>
											<div class="col-sm-3">
												<label class="col-form-label">PERIODO</label>
												<select class="form-control select2-all" name="periodo" id="periodo" placeholder="..." required="">
													<option value="">...</option>
												</select>
											</div>
											<div class="col-sm-5">
												<div class="rel-wrapper ui-datepicker ui-datepicker-popup dp-theme-primary" id="example-datepicker-container-5">
													<div class="input-daterange" id="example-datepicker-5">
														<div class="form-group row">
															<div class="col-6">
																<p>FECHA DE INICIO</p>
																<input class="form-control" type="text" name="fecha_inicio" id="fecha_inicio" value="" placeholder="DD-MM-YYYY" autocomplete="off" required="">
															</div>
															<div class="col-6">
																<p>FECHA DE FIN</p>
																<input class="form-control" type="text" name="fecha_fin" id="fecha_fin" value="" placeholder="DD-MM-YYYY" autocomplete="off" required="">
															</div>
														</div>
													</div>
												</div>
											</div>
										</div>
									</fieldset>

									<fieldset>
										<div class="form-group row">
											<div class="col-sm-2">
												<label class="col-form-label">TURNO</label>
												<select class="form-control select2-all" name="turno" placeholder="..." required="">
													<option value="">...</option>
													<?php
													$sql_turno = mysqli_query($con, "SELECT cod_turno, nombre_tur FROM tbl_turno");
													while ($row_tu = mysqli_fetch_array($sql_turno)) {
														?>
														<option value="<?php echo $row_tu['cod_turno']; ?>"><?php echo $row_tu['nombre_tur']; ?></option>
														<?php
													}
													?>
												</select>
											</div>
											<div class="col-sm-3">
												<label class="col-form-label">MODALIDAD</label>
												<select class="form-control select2-all" name="tipo_modalidad" id="tipo_modalidad" placeholder="..." required="">
													<option value="">...</option>
													<?php
													$sql_modalidad = mysqli_query($con, "SELECT cod_tipomodalidad, nombre_tipmod FROM tbl_tipo_modalidad");
													while ($row_mo = mysqli_fetch_array($sql_modalidad)) {
														?>
														<option value="<?php echo $row_mo['cod_tipomodalidad']; ?>"><?php echo $row_mo['nombre_tipmod']; ?></option>
														<?php
													}
													?>
												</select>
											</div>
											<div class="col-sm-3">
												<label class="col-form-label">GRUPO</label>
												<select class="form-control select2-all" name="grupo" id="grupo" placeholder="..." required="">
													<option value="">...</option>
													<?php
													$sql_grupo = mysqli_query($con, "SELECT cod_grupo, nombre_gru FROM tbl_grupo");
													while ($row_gr = mysqli_fetch_array($sql_grupo)) {
														?>
														<option value="<?php echo $row_gr['cod_grupo']; ?>"><?php echo $row_gr['nombre_gru']; ?></option>
														<?php
													}
													?>
												</select>
											</div>
											<div class="col-sm-2">
												<label class="col-form-label">AULA</label>
												<select class="form-control select2-all" name="aula" id="aula" placeholder="..." required="">
													<option value="">...</option>
													<?php
													$sql_aula = mysqli_query($con, "SELECT cod_aula, nombre_au, capacidad_au FROM tbl_aula 
														WHERE estado_au = 1 AND cod_sucursal_au IN (SELECT cod_sucursal FROM tbl_sucursal, tbl_usuario_sucursal 
															WHERE cod_sucursal = cod_sucursal_ususuc AND cod_usuario_ususuc = $cod_usuario AND estado_ususuc = 1)");
													while ($row_au = mysqli_fetch_array($sql_aula)) {
														?>
														<option value="<?php echo $row_au['cod_aula']; ?>"><?php echo $row_au['nombre_au']." - (".$row_au['capacidad_au']." Est.)"; ?></option>
														<?php
													}
													?>
												</select>
											</div>
											<div class="col-sm-2">
												<label class="col-form-label">CUPO MAX.</label>
												<input type="text" name="cupo" id="cupo" class="form-control" placeholder="..." value="">
											</div>
										</div>
									</fieldset>

									<fieldset>
										<div class="form-group row">
											<div class="col-sm-6">
												<label class="col-form-label">DOCENTE</label>
												<select class="form-control select2-all" name="docente" placeholder="..." required="">
													<option value="">...</option>
													<?php
													$sql_docente = mysqli_query($con, "SELECT cod_docente, sigla_titprof, nombre_per, apellido_per 
														FROM tbl_docente, tbl_persona, tbl_titulo_profesional 
														WHERE cod_persona_doc = cod_persona AND cod_tituloprofesional_doc = cod_tituloprofesional 
														AND estado_doc = 1 ORDER BY nombre_per, apellido_per");
													while ($row_do = mysqli_fetch_array($sql_docente)) {
														$cod_docente = $row_do['cod_docente'];
														
														$sql_docsuc = mysqli_query($con, "SELECT cod_docente_sucursal FROM tbl_docente_sucursal WHERE cod_docente_docsuc = $cod_docente 
															AND cod_sucursal_docsuc IN (SELECT cod_sucursal FROM tbl_sucursal, tbl_usuario_sucursal 
																WHERE cod_sucursal = cod_sucursal_ususuc AND cod_usuario_ususuc = $cod_usuario AND estado_ususuc = 1) AND estado_docsuc = 1");
														if(mysqli_num_rows($sql_docsuc) > 0){
															?>
															<option value="<?php echo $row_do['cod_docente']; ?>"><?php echo $row_do['sigla_titprof']." ".$row_do['nombre_per']." ".$row_do['apellido_per']; ?></option>
															<?php
														}
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

		<script language="JavaScript" type="text/JavaScript">
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
    			$("#materia").select2('val','...');
    			var cod = $("#carrera").find(':selected').val();
    			$("#materia").load('../class/buscar_select_materia.php?cod='+cod);	
    		});

    		$("#tipo_periodo").change(function(event){
    			$("#periodo").select2('val','...');
    			var tipper = $("#tipo_periodo").find(':selected').val();
    			var gest = $("#gestion").find(':selected').val();
    			$("#periodo").load('../class/buscar_select.php?tipo=periodo&tipper='+tipper+'&gest='+gest);
    		});

    		$("#gestion").change(function(event){
    			$("#periodo").select2('val','...');
    			var tipper = $("#tipo_periodo").find(':selected').val();
    			var gest = $("#gestion").find(':selected').val();
    			$("#periodo").load('../class/buscar_select.php?tipo=periodo&tipper='+tipper+'&gest='+gest);
    		});

    		$("#sucursal").change(function(event){
    			$("#aula").select2('val','...');
    			var suc = $("#sucursal").find(':selected').val();
    			$("#aula").load('../class/buscar_select.php?tipo=aula&suc='+suc);	
    		});

    		$("#aula").change(function(event){
					var aula = $("#aula").find(':selected').val();
					///CARGAR DATOS DESDE UNA CONSULTA EN TEXTBOX CON SELECT Y ENVIO DE VARIABLES
				  $.get('../class/buscar_select.php?tipo=cupo_aula&aula='+aula, function(data) {
						$('#cupo').val(data);
						//OBTENER VALORES DE CONTROLES DEL FORMULARIO
						var maximo = $("#cupo").val();
						///MODIFICAR ATRIBUTOS DE CONTROLES DE FORMULARIO
						$('#cupo').attr("max", maximo);
					});
			  });

    		$("#periodo").change(function(event){
    			var periodo = $("#periodo").val();
    			if (periodo > 0) {
	    			$.ajax({
	    				type: "POST",
	    				url: "../class/buscar_select.php",
	    				data: 'tipo=fechas_periodo&per='+periodo,
	    				dataType: "json",
	    				beforeSend: function(){
	    					$("#fecha_inicio").val("");
	    					$("#fecha_fin").val("");
	    				},
	    				error: function(){
	    					alert("Error al buscar los registros");
	    				},
	    				success: function(data){
	    					$("#fecha_inicio").val(data.fecha_inicio);
	    					$("#fecha_fin").val(data.fecha_fin);
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