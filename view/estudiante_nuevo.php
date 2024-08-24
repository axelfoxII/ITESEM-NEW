<?php
include ('../conf/funciones.php');
include("../conf/mysql.php");
$cod_usuario = 0;
if (verificar_usuario()){
	$cod_usuario = $_SESSION['cod_usuario'];
	$nombre_pagina = "estudiante.php";
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
		<!-- Alertify -->
    <link rel="stylesheet" href="vendor/alertifyjs/css/alertify.css" />
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
								<center><h2 class="text-primary text-bold">Gestión de Estudiantes</h2></center>
								<h4><a href="estudiante.php"><i class="ion-arrow-left-c"></i>Volver Atras</a></h4>
								<form action="estudiante_guardar.php" method="POST">
									<input type="hidden" name="cod_persona" id="cod_persona" value="0">
									<input type="hidden" name="tipo" value="guardar">
									<hr>
									<fieldset>
										<div class="form-group row">
											<div class="col-sm-2">
												<label class="col-form-label text-bold">CARNET</label>
												<input class="form-control" type="text" name="carnet" id="carnet" placeholder="..." required="">
											</div>
											<div class="col-sm-1">
												<label class="col-form-label text-bold">EXPEDIDO</label>
												<select class="form-control" name="expedido" id="expedido" placeholder="..." required="">
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
											<div class="col-sm-1">
												<br>
												<a class="btn btn-primary" id="verificar_ci"><em class="ion-search"></em></a>
												<!-- <button class="form-control btn btn-warning">LIMPIAR</button> -->
											</div>
											<div class="col-sm-3">
												<label class="col-form-label text-bold">NOMBRE</label>
												<input class="form-control" type="text" name="nombre" id="nombre" placeholder="..." required="" disabled>
											</div>
											<div class="col-sm-5">
												<label class="col-form-label text-bold">APELLIDOS</label>
												<input class="form-control" type="text" name="apellido" id="apellido" placeholder="..." required="" disabled>
											</div>
										</div>
									</fieldset>

									<fieldset>
										<div class="form-group row">
											<div class="col-sm-3">
												<label class="col-form-label text-bold">PAIS</label>
												<select class="form-control" name="pais" id="pais" placeholder="..." required="" disabled>
													<option value="0">...</option>
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
												<label class="col-form-label text-bold">DEPARTAMENTO NAC.</label>
												<select class="form-control" name="departamento" id="departamento" placeholder="..." required="" disabled>
													<option value="0">...</option>
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
											<div class="col-sm-3">
												<label class="col-form-label text-bold">FECHA DE NAC.</label>
												<input type="text" id="fecha_nac" name="fecha_nac" class="form-control" placeholder="DD-MM-AAAA" required="required" maxlength="10" disabled>
											</div>
											<div class="col-sm-2">
												<label class="col-form-label text-bold">SEXO</label>
												<select class="form-control" name="sexo" id="sexo" placeholder="..." required="" disabled>
													<option value="0">...</option>
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
										</div>
									</fieldset>

									<fieldset>
										<div class="form-group row">
											<div class="col-sm-2">
												<label class="col-form-label text-bold">CELULAR</label>
												<input class="form-control" type="text" name="celular" id="celular" placeholder="..." required="" onKeyPress="return numeros(event)" disabled>
											</div>
											<div class="col-sm-2">
												<label class="col-form-label text-bold">CELULAR 2</label>
												<input class="form-control" type="text" name="celular2" id="celular2" placeholder="..." onKeyPress="return numeros(event)" disabled>
											</div>
											<div class="col-sm-3">
												<label class="col-form-label text-bold">CORREO</label>
												<input class="form-control" type="mail" name="correo" id="correo" placeholder="mail@example.com" disabled>
											</div>
											<div class="col-sm-5">
												<label class="col-form-label text-bold">DIRECCIÓN</label>
												<input class="form-control" type="text" name="direccion" id="direccion" placeholder="..." disabled>
											</div>
										</div>
									</fieldset>
									<hr>
									<fieldset>
										<div class="form-group row">
											<div class="col-sm-4">
												<label class="col-form-label text-bold">SUB SEDE</label>
												<select class="form-control select2-all" name="sucursal" id="sucursal" placeholder="..." required="" disabled>
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
											<div class="col-sm-2">
												<label class="col-form-label text-bold">NIVEL</label>
												<select class="form-control select2-all" name="nivel" id="nivel" placeholder="..." required="" disabled>
													<option value="">...</option>
													<?php
													$sql_nivel = mysqli_query($con, "SELECT cod_nivel, nombre_niv FROM tbl_nivel");
													while ($row_n = mysqli_fetch_array($sql_nivel)) {
														?>
														<option value="<?php echo $row_n['cod_nivel']; ?>"><?php echo $row_n['nombre_niv']; ?></option>
														<?php
													}
													?>
												</select>
											</div>
											<div class="col-sm-6">
												<label class="col-form-label text-bold">CARRERA</label>
												<select class="form-control select2-all" id="carrera" name="carrera" placeholder="..." required="" disabled>
												</select>
											</div>
										</div>
									</fieldset>

									<div class="row" id="detalle_est"></div>

									<fieldset id="div_titulo" style="display: none;">
										<div class="form-group row">
											<div class="col-sm-2">
												<label class="col-form-label text-bold">Nro TIT. BACH.</label>
												<input class="form-control" type="text" name="nro_titulo" placeholder="...">
											</div>
											<div class="col-sm-2">
												<label class="col-form-label text-bold">AÑO TIT. BACH.</label>
												<input class="form-control" type="text" name="anho_titulo" placeholder="...">
											</div>
										</div>
									</fieldset>

									<fieldset>
										<div class="form-group row">
											<div class="col-sm-2">
												<label class="col-form-label text-bold">DPTO COLEGIO</label>
												<select class="form-control select2-all" name="departamento_col" id="departamento_col" placeholder="..." required="">
													<option value="">...</option>
													<?php
													$sql_departamento = mysqli_query($con, "SELECT cod_departamento, nombre_dep FROM tbl_departamento");
													while ($row_de = mysqli_fetch_array($sql_departamento)) {
														?>
														<option value="<?php echo $row_de['cod_departamento']; ?>"><?php echo $row_de['nombre_dep']; ?></option>
														<?php
													}
													?>
												</select>
											</div>
											<div class="col-sm-2">
												<label class="col-form-label text-bold">TIPO COLEGIO</label>
												<select class="form-control select2-all" name="tipo_colegio" id="tipo_colegio" placeholder="..." required="">
													<option value="">...</option>
													<?php
													$sql_tipcol = mysqli_query($con, "SELECT cod_tipocolegio, nombre_tipcol FROM tbl_tipo_colegio");
													while ($row_tc = mysqli_fetch_array($sql_tipcol)) {
														?>
														<option value="<?php echo $row_tc['cod_tipocolegio']; ?>"><?php echo $row_tc['nombre_tipcol']; ?></option>
														<?php
													}
													?>
												</select>
											</div>
											<div class="col-sm-4">
												<label class="col-form-label text-bold">COLEGIO</label>
												<select class="form-control select2-all" name="colegio" id="colegio" placeholder="..." required="">
													<option value="">...</option>
												</select>
											</div>
											<div class="col-sm-4">
												<label class="col-form-label text-bold">PLAN ECONÓMICO</label>
												<select class="form-control select2-all" name="plan" id="plan" placeholder="..." required="">
													<option value="">...</option>
												</select>
											</div>
										</div>
									</fieldset>

									<fieldset>
										<div class="form-group row">
											<div class="col-sm-4">
												<label class="col-form-label text-bold">OBSERVACIÓN</label>
												<input class="form-control" type="text" name="observacion" placeholder="... Sin observación">
											</div>
											<div class="col-sm-2">
												<label class="col-form-label text-bold">FORMA DE LLEGADA</label>
												<select class="form-control select2-all" name="forma_llegada" placeholder="..." required="">
													<option value="">...</option>
													<?php
													$sql_forma = mysqli_query($con, "SELECT cod_formallegada, nombre_formalle FROM tbl_forma_llegada 
														ORDER BY nombre_formalle");
													while ($row_f = mysqli_fetch_array($sql_forma)) {
														?>
														<option value="<?php echo $row_f['cod_formallegada']; ?>"><?php echo $row_f['nombre_formalle']; ?></option>
														<?php
													}
													?>
												</select>
											</div>
											<div class="col-sm-2">
												<label class="col-form-label text-bold">TURNO</label>
												<select class="form-control select2-all" name="turno" placeholder="..." required="">
													<option value="">...</option>
													<?php
													$sql_turno = mysqli_query($con, "SELECT cod_turno, nombre_tur FROM tbl_turno 
														ORDER BY nombre_tur");
													while ($row_t = mysqli_fetch_array($sql_turno)) {
														?>
														<option value="<?php echo $row_t['cod_turno']; ?>"><?php echo $row_t['nombre_tur']; ?></option>
														<?php
													}
													?>
												</select>
											</div>
											<div class="col-sm-2">
												<label class="col-form-label text-bold">MODALIDAD</label>
												<select class="form-control select2-all" name="tipo_modalidad" placeholder="..." required="">
													<option value="">...</option>
													<?php
													$sql_tipomod = mysqli_query($con, "SELECT cod_tipomodalidad, nombre_tipmod FROM tbl_tipo_modalidad");
													while ($row_tm = mysqli_fetch_array($sql_tipomod)) {
														?>
														<option value="<?php echo $row_tm['cod_tipomodalidad']; ?>"><?php echo $row_tm['nombre_tipmod']; ?></option>
														<?php
													}
													?>
												</select>
											</div>
											<div class="col-sm-2">
												<label class="col-form-label text-bold">ESTADO</label>
												<select class="form-control select2-all" name="tipo_estudiante" placeholder="..." required="">
													<?php
													$sql_tipoest = mysqli_query($con, "SELECT cod_tipoestudiante, nombre_tipest FROM tbl_tipo_estudiante 
														WHERE cod_tipoestudiante = 1");
													while ($row_te = mysqli_fetch_array($sql_tipoest)) {
														?>
														<option value="<?php echo $row_te['cod_tipoestudiante']; ?>"><?php echo $row_te['nombre_tipest']; ?></option>
														<?php
													}
													?>
												</select>
											</div>
										</div>
									</fieldset>
									<div class="text-center">
										<button class="btn btn-primary pull-right" id="btn_registrar" type="submit">Guardar el Registro</button>
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
    <!-- MASK -->
    <script src="vendor/mask/jquery.maskedinput.js" type="text/javascript"></script>
    <!-- Alertify -->
    <script src="vendor/alertifyjs/alertify.js"></script>

    <style>
    	#verificar_ci{
    		color: #FFF;
    		font-size: 18px;
    	}
    </style>

    <script language="JavaScript" type="text/JavaScript">
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

    		$("#verificar_ci").click(function(){
    			var carnet = $("#carnet").val();

    			$.ajax({
						type: "POST",
						url: "../class/buscar_estudiante_reg.php",
						data: "funcion=verificar_ci&carnet="+carnet,
						dataType: "json",
						error: function(){ alert("Error en la peticion AJAX - Verificar CI"); },
						success: function(data){
							$("#nombre").prop('disabled', false);
							$("#apellido").prop('disabled', false);
							$("#fecha_nac").prop('disabled', false);
							$("#celular").prop('disabled', false);
							$("#celular2").prop('disabled', false);
							$("#correo").prop('disabled', false);
							$("#direccion").prop('disabled', false);
							$("#sexo").prop('disabled', false);
							$("#pais").prop('disabled', false);
							$("#departamento").prop('disabled', false);
							$("#sucursal").prop('disabled', false);
							$("#nivel").prop('disabled', false);
							$("#carrera").prop('disabled', false);
							if(data.cod_persona == '0'){
								$("#cod_persona").val('0');
								$("#nombre").val('');
								$("#apellido").val('');
								$("#fecha_nac").val('');
								$("#celular").val('');
								$("#celular2").val('');
								$("#correo").val('');
								$("#direccion").val('');
								$('#sexo option[value="0"]').attr("selected", "selected");
								$('#pais option[value="0"]').attr("selected", "selected");
								$('#departamento option[value="0"]').attr("selected", "selected");
								$('#expedido option[value=""]').attr("selected", "selected");
							}else{
								alertify.notify('<div class="text-white">CARNET YA REGISTRADO</div>', 'success', 10).dismissOthers();
								$("#cod_persona").val(data.cod_persona);
								$("#nombre").val(data.nombre);
								$("#apellido").val(data.apellido);
								$("#fecha_nac").val(data.fecha_nac);
								$("#celular").val(data.celular);
								$("#celular2").val(data.celular2);
								$("#correo").val(data.correo);
								$("#direccion").val(data.direccion);
								$('#sexo option[value="'+data.sexo+'"]').attr("selected", "selected");
								$('#pais option[value="'+data.pais+'"]').attr("selected", "selected");
								$('#departamento option[value="'+data.departamento+'"]').attr("selected", "selected");
								$('#expedido option[value="'+data.expedido+'"]').attr("selected", "selected");
							}
						}
					});
    		});


				$("#tipo_colegio").change(function(event){
    			$("#colegio").select2('val','...');
    			var cod_t = $("#tipo_colegio").find(':selected').val();
    			var cod_d = $("#departamento_col").find(':selected').val();
    			$("#colegio").load('../class/buscar_select.php?tipo=colegio&cod_t='+cod_t+'&cod_d='+cod_d);
    		});

    		$("#departamento_col").change(function(event){
    			$("#colegio").select2('val','...');
    			var cod_t = $("#tipo_colegio").find(':selected').val();
    			var cod_d = $("#departamento_col").find(':selected').val();
    			$("#colegio").load('../class/buscar_select.php?tipo=colegio&cod_t='+cod_t+'&cod_d='+cod_d);
    		});

    		$("#sucursal").change(function(event){
    			$("#plan").select2('val','...');
    			var cod_s = $("#sucursal").find(':selected').val();
    			var cod_n = $("#nivel").find(':selected').val();
    			$("#plan").load('../class/buscar_select.php?tipo=plan&cod_s='+cod_s+'&cod_n='+cod_n);
    		});

    		$("#nivel").change(function(event){
    			$("#plan").select2('val','...');
    			var cod_s = $("#sucursal").find(':selected').val();
    			var cod_n = $("#nivel").find(':selected').val();
    			$("#plan").load('../class/buscar_select.php?tipo=plan&cod_s='+cod_s+'&cod_n='+cod_n);

    			if(cod_n >= 1 && cod_n <= 2){
    				$('#div_titulo').show();
    			}else{
    				$('#div_titulo').hide();
    			}
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
    			if (cod != "") {
    				$("#carrera").load('../class/buscar_select_carrera.php?cod='+cod+'&suc='+suc);
    			}
    		});

    		$("#carrera").change(function(event){
    			var carrera = $("#carrera").find(':selected').val();
    			var cod_persona = $("#cod_persona").val();
    			if(cod_persona != 0 && cod_persona != '0' && carrera > 0){
    				$.ajax({
							type: "POST",
							url: "../class/buscar_estudiante_reg.php",
							data: "funcion=verificar_est&cod_persona="+cod_persona+"&carrera="+carrera,
							dataType: "html",
							error: function(){ alert("Error en la peticion AJAX - Verificar CI"); },
							success: function(data){
								if(data == '0'){
									$("#btn_registrar").prop('disabled', false);
									$("#detalle_est").html('');
								}else{
									$("#detalle_est").html(data);
									$("#btn_registrar").prop('disabled', true);
								}
							}
						});
    			}else{
    				$("#detalle_est").html('');
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