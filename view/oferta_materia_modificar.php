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
								<?php
								$cod_oferta = 0;

								if (isset($_GET['cod'])) {
									$cod_oferta = $_GET['cod'];

									$nombre_car = "";
									$nombre_mat = "";
									$cod_turno = 0;
									$cod_docente = 0;
									$sucursal = 0;
									$periodo = 0;
									$aula = 0;
									$cupo_max = 0;
									$tipo_modalidad = 0; $grupo = 0;
									$fecha_inicio = ""; $fecha_fin = "";
									$sql_estudiante = mysqli_query($con, "SELECT nombre_car, nombre_mat, cod_turno_of, cod_docente_of, fecha_inicio_of, fecha_fin_of, 
										cod_sucursal_of, cod_periodo_of, cod_aula_of, cupo_max_of, cod_tipomodalidad_of, cod_grupo_of 
										FROM tbl_oferta_materia, tbl_materia, tbl_carrera 
										WHERE cod_materia_of = cod_materia AND cod_carrera_mat = cod_carrera AND cod_oferta_materia = $cod_oferta");
									while ($row_o = mysqli_fetch_array($sql_estudiante)) {
										$nombre_car = $row_o['nombre_car'];
										$nombre_mat = $row_o['nombre_mat'];
										$cod_turno = $row_o['cod_turno_of'];
										$cod_docente = $row_o['cod_docente_of'];
										$sucursal = $row_o['cod_sucursal_of'];
										$periodo = $row_o['cod_periodo_of'];
										$aula = $row_o['cod_aula_of'];
										$cupo_max = $row_o['cupo_max_of'];
										$tipo_modalidad = $row_o['cod_tipomodalidad_of'];
										$grupo = $row_o['cod_grupo_of'];
										$fecha_inicio = date_format(date_create($row_o['fecha_inicio_of']), "d-m-Y");
										$fecha_fin = date_format(date_create($row_o['fecha_fin_of']), "d-m-Y");
									}
								}
								?>
								<center><h2>Gestión de Ofertas de Materias</h2></center>
								<h4><a href="oferta_materia.php"><i class="ion-arrow-left-c"></i>Volver Atras</a></h4>
								<form action="oferta_materia_guardar.php" method="POST">
									<input type="hidden" name="codigo" value="<?php echo $cod_oferta; ?>">
									<input type="hidden" name="tipo" value="modificar">
									<fieldset>
										<div class="form-group row">
											<div class="col-sm-6">
												<label class="col-form-label">SUCURSAL</label>
												<input type="hidden" name="sucursal" value="<?php echo $sucursal; ?>">
												<select name="sucursal" class="form-control select2-all" required="" disabled="">
													<?php
													$sql_sucursal = mysqli_query($con, "SELECT cod_sucursal, sigla_suc FROM tbl_sucursal 
														WHERE cod_sucursal = $sucursal");
													while($row_us = mysqli_fetch_array($sql_sucursal)){
														?>
														<option value="<?php echo $row_us['cod_sucursal']; ?>"><?php echo $row_us['sigla_suc']; ?></option>
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
												<input type="text" class="form-control" name="carrera" readonly="" required="" value="<?php echo $nombre_car; ?>">
											</div>
											<div class="col-sm-6">
												<label class="col-form-label">MATERIA</label>
												<input type="text" class="form-control" name="materia" readonly="" required="" value="<?php echo $nombre_mat; ?>">
											</div>
										</div>
									</fieldset>

									<fieldset>
										<div class="form-group row">
											<div class="col-sm-2">
												<label class="col-form-label">TIPO PERIODO</label>
												<select class="form-control select2-all" name="tipo_periodo" id="tipo_periodo" placeholder="..." required="">
													<?php
													$cod_tipoperiodo = 0;
													$sql_tipo = mysqli_query($con, "SELECT cod_tipoperiodo, nombre_tipper FROM tbl_tipo_periodo, tbl_periodo 
														WHERE cod_tipoperiodo_peri = cod_tipoperiodo AND cod_periodo = $periodo");
													while ($row_ti = mysqli_fetch_array($sql_tipo)) {
														$cod_tipoperiodo = $row_ti['cod_tipoperiodo'];
														?>
														<option value="<?php echo $row_ti['cod_tipoperiodo']; ?>"><?php echo $row_ti['nombre_tipper']; ?></option>
														<?php
													}

													$sql_tipo = mysqli_query($con, "SELECT cod_tipoperiodo, nombre_tipper FROM tbl_tipo_periodo 
														WHERE cod_tipoperiodo != $cod_tipoperiodo");
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
													<?php
													$cod_gestion = 0;
													$sql_gestion = mysqli_query($con, "SELECT cod_gestion, nombre_gest FROM tbl_gestion, tbl_periodo 
														WHERE cod_gestion_peri = cod_gestion AND cod_periodo = $periodo");
													while ($row_g = mysqli_fetch_array($sql_gestion)) {
														$cod_gestion = $row_g['cod_gestion'];
														?>
														<option value="<?php echo $row_g['cod_gestion']; ?>"><?php echo $row_g['nombre_gest']; ?></option>
														<?php
													}

													$sql_gestion = mysqli_query($con, "SELECT cod_gestion, nombre_gest FROM tbl_gestion WHERE cod_gestion != $cod_gestion");
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
													<?php
													$sql_periodo = mysqli_query($con, "SELECT cod_periodo, nombre_peri, nombre_gest FROM tbl_periodo, tbl_gestion 
														WHERE cod_gestion = cod_gestion_peri AND cod_periodo = $periodo");
													while ($row_p = mysqli_fetch_array($sql_periodo)) {
														?>
														<option value="<?php echo $row_p['cod_periodo']; ?>"><?php echo $row_p['nombre_gest']." - ".$row_p['nombre_peri']; ?></option>
														<?php
													}
													?>
												</select>
											</div>
											<div class="col-sm-5">
												<div class="rel-wrapper ui-datepicker ui-datepicker-popup dp-theme-primary" id="example-datepicker-container-5">
													<div class="input-daterange" id="example-datepicker-5">
														<div class="form-group row">
															<div class="col-6">
																<p>FECHA DE INICIO</p>
																<input class="form-control" type="text" name="fecha_inicio" id="fecha_inicio" placeholder="DD-MM-YYYY" autocomplete="off" required="" value="<?php echo $fecha_inicio; ?>">
															</div>
															<div class="col-6">
																<p>FECHA DE FIN</p>
																<input class="form-control" type="text" name="fecha_fin" id="fecha_fin" placeholder="DD-MM-YYYY" autocomplete="off" required="" value="<?php echo $fecha_fin; ?>">
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
												<select class="form-control select2" name="turno" placeholder="..." required="">
													<?php
													$sql_turno = mysqli_query($con, "SELECT cod_turno, nombre_tur FROM tbl_turno WHERE cod_turno = $cod_turno");
													while ($row_tu = mysqli_fetch_array($sql_turno)) {
														?>
														<option value="<?php echo $row_tu['cod_turno']; ?>"><?php echo $row_tu['nombre_tur']; ?></option>
														<?php
													}
													$sql_turno = mysqli_query($con, "SELECT cod_turno, nombre_tur FROM tbl_turno WHERE cod_turno != $cod_turno");
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
													<?php
													$sql_modalidad = mysqli_query($con, "SELECT cod_tipomodalidad, nombre_tipmod FROM tbl_tipo_modalidad WHERE cod_tipomodalidad = $tipo_modalidad");
													while ($row_mo = mysqli_fetch_array($sql_modalidad)) {
														?>
														<option value="<?php echo $row_mo['cod_tipomodalidad']; ?>"><?php echo $row_mo['nombre_tipmod']; ?></option>
														<?php
													}
													$sql_modalidad = mysqli_query($con, "SELECT cod_tipomodalidad, nombre_tipmod FROM tbl_tipo_modalidad WHERE cod_tipomodalidad != $tipo_modalidad");
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
													<?php
													$sql_grupo = mysqli_query($con, "SELECT cod_grupo, nombre_gru FROM tbl_grupo WHERE cod_grupo = $grupo");
													while ($row_gr = mysqli_fetch_array($sql_grupo)) {
														?>
														<option value="<?php echo $row_gr['cod_grupo']; ?>"><?php echo $row_gr['nombre_gru']; ?></option>
														<?php
													}
													$sql_grupo = mysqli_query($con, "SELECT cod_grupo, nombre_gru FROM tbl_grupo WHERE cod_grupo != $grupo");
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
													<?php
													$sql_aula = mysqli_query($con, "SELECT cod_aula, nombre_au, capacidad_au FROM tbl_aula 
														WHERE cod_aula = $aula");
													while ($row_au = mysqli_fetch_array($sql_aula)) {
														?>
														<option value="<?php echo $row_au['cod_aula']; ?>"><?php echo $row_au['nombre_au']." - (".$row_au['capacidad_au']." Est.)"; ?></option>
														<?php
													}

													$sql_aula = mysqli_query($con, "SELECT cod_aula, nombre_au, capacidad_au FROM tbl_aula 
														WHERE estado_au = 1 AND cod_aula != $aula AND cod_sucursal_au IN (SELECT cod_sucursal FROM tbl_sucursal, tbl_usuario_sucursal 
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
												<input type="text" name="cupo" id="cupo" class="form-control" placeholder="..." value="<?php echo $cupo_max; ?>">
											</div>
										</div>
									</fieldset>

									<fieldset>
										<div class="form-group row">
											<div class="col-sm-6">
												<label class="col-form-label">DOCENTE</label>
												<select class="form-control select2" name="docente" placeholder="..." required="">
													<?php
													$sql_docente = mysqli_query($con, "SELECT cod_docente, sigla_titprof, nombre_per, apellido_per 
														FROM tbl_docente, tbl_persona, tbl_titulo_profesional 
														WHERE cod_persona_doc = cod_persona AND cod_tituloprofesional_doc = cod_tituloprofesional 
														AND cod_docente = $cod_docente");
													while ($row_do = mysqli_fetch_array($sql_docente)) {
														?>
														<option value="<?php echo $row_do['cod_docente']; ?>"><?php echo $row_do['sigla_titprof']." ".$row_do['nombre_per']." ".$row_do['apellido_per']; ?></option>
														<?php
													}
													$sql_docente = mysqli_query($con, "SELECT cod_docente, sigla_titprof, nombre_per, apellido_per 
														FROM tbl_docente, tbl_persona, tbl_titulo_profesional 
														WHERE cod_persona_doc = cod_persona AND cod_tituloprofesional_doc = cod_tituloprofesional 
														AND estado_doc = 1 AND cod_docente != $cod_docente ORDER BY nombre_per, apellido_per");
													while ($row_do = mysqli_fetch_array($sql_docente)) {
														?>
														<option value="<?php echo $row_do['cod_docente']; ?>"><?php echo $row_do['sigla_titprof']." ".$row_do['nombre_per']." ".$row_do['apellido_per']; ?></option>
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

		<script language="JavaScript" type="text/JavaScript">
    	$(document).ready(function(){
    		$('.select2-all').select2({
					placeholder: "..."
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