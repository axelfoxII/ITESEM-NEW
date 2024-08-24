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
								$cod_estudiante = 0;

								$carnet = ""; $cod_expedido = 0;
								$nombre = ""; $apellido = "";
								$cod_pais = 0; $cod_departamento = 0;
								$fecha_nac = ""; $cod_sexo = 0;
								$celular = ""; $celular2 = "";
								$correo = ""; $direccion = "";
								$cod_plan = 0;
								$sucursal_est = 0;
								$cod_carrera = 0;
								$nro_titulo = 0; $anho_titulo = 0; $cod_colegio = 0;
								$cod_formallegada = 0;
								$observacion = ""; $cod_nivel = 0;
								$tipo_modalidad = 0; $estado_estudiante = 0;
								$cod_turno = 0;
								if (isset($_GET['cod'])) {
									$cod_estudiante = $_GET['cod'];

									$sql_estudiante = mysqli_query($con, "SELECT cod_persona, nombre_per, apellido_per, carnet_per, cod_expedido_per, cod_pais_per, cod_departamento_per, fecha_nacimiento_per, cod_sexo_per, 
										celular_per, celular2_per, correo_per, direccion_per, cod_plan_est, cod_sucursal_est, cod_carrera_est, 
										nro_titulo_est, anho_titulo_est, cod_colegio_est, cod_formallegada_est, observacion_est, cod_nivel_car, cod_tipoestudiante_est, cod_tipomodalidad_est, cod_turno_est 
										FROM tbl_estudiante, tbl_persona, tbl_carrera 
										WHERE cod_persona_est = cod_persona AND cod_carrera_est = cod_carrera AND cod_estudiante = $cod_estudiante");
									while ($row_e = mysqli_fetch_array($sql_estudiante)) {
										$cod_persona = $row_e['cod_persona'];
										$carnet = $row_e['carnet_per'];
										$cod_expedido = $row_e['cod_expedido_per'];
										$nombre = $row_e['nombre_per'];
										$apellido = $row_e['apellido_per'];
										$cod_pais = $row_e['cod_pais_per'];
										$cod_departamento = $row_e['cod_departamento_per'];
										$fecha_nac = "";
										if($row_e['fecha_nacimiento_per'] != "0000-00-00")
											$fecha_nac = date_format(date_create($row_e['fecha_nacimiento_per']), 'd-m-Y');
										$cod_sexo = $row_e['cod_sexo_per'];
										$celular = $row_e['celular_per'];
										$celular2 = $row_e['celular2_per'];
										$correo = $row_e['correo_per'];
										$direccion = $row_e['direccion_per'];

										$cod_plan = $row_e['cod_plan_est'];
										$sucursal_est = $row_e['cod_sucursal_est'];
										$cod_carrera = $row_e['cod_carrera_est'];
										$nro_titulo = $row_e['nro_titulo_est'];
										$anho_titulo = $row_e['anho_titulo_est'];
										$cod_colegio = $row_e['cod_colegio_est'];
										$cod_formallegada = $row_e['cod_formallegada_est'];
										$observacion = $row_e['observacion_est'];
										$tipo_modalidad = $row_e['cod_tipomodalidad_est'];
										$cod_nivel = $row_e['cod_nivel_car'];
										$tipo_estudiante = $row_e['cod_tipoestudiante_est'];
										$cod_turno = $row_e['cod_turno_est'];
									}
								}
								?>
								<center><h2>Gestión de Estudiantes</h2></center>
								<h4><a href="estudiante.php"><i class="ion-arrow-left-c"></i>Volver Atras</a></h4>
								<form action="estudiante_guardar.php" method="POST">
									<input type="hidden" name="cod_persona" value="<?php echo $cod_persona; ?>">
									<input type="hidden" name="codigo" value="<?php echo $cod_estudiante; ?>">
									<input type="hidden" name="tipo" value="modificar">
									<fieldset>
										<div class="form-group row">
											<div class="col-sm-2">
												<label class="col-form-label text-bold">CARNET</label>
												<input class="form-control" type="text" name="carnet" id="carnet" placeholder="..." required="" value="<?php echo $carnet; ?>">
											</div>
											<div class="col-sm-1">
												<label class="col-form-label text-bold">EXPEDIDO</label>
												<select class="form-control" name="expedido" id="expedido" placeholder="..." required="">
													<?php
													$sql_expedido = mysqli_query($con, "SELECT cod_expedido, sigla_expedido FROM tbl_expedido WHERE cod_expedido = $cod_expedido");
													while ($row_ex = mysqli_fetch_array($sql_expedido)) {
														?>
														<option value="<?php echo $row_ex['cod_expedido']; ?>"><?php echo $row_ex['sigla_expedido']; ?></option>
														<?php
													}
													$sql_expedido = mysqli_query($con, "SELECT cod_expedido, sigla_expedido FROM tbl_expedido WHERE cod_expedido != $cod_expedido");
													while ($row_ex = mysqli_fetch_array($sql_expedido)) {
														?>
														<option value="<?php echo $row_ex['cod_expedido']; ?>"><?php echo $row_ex['sigla_expedido']; ?></option>
														<?php
													}
													?>
												</select>
											</div>
											<div class="col-sm-1">
												<!-- <br> -->
												<!-- <a class="btn btn-primary" id="verificar_ci"><em class="ion-search"></em></a> -->
												<!-- <button class="form-control btn btn-warning">LIMPIAR</button> -->
											</div>
											<div class="col-sm-3">
												<label class="col-form-label text-bold">NOMBRE</label>
												<input class="form-control" type="text" name="nombre" id="nombre" placeholder="..." required="" value="<?php echo $nombre; ?>">
											</div>
											<div class="col-sm-5">
												<label class="col-form-label text-bold">APELLIDOS</label>
												<input class="form-control" type="text" name="apellido" id="apellido" placeholder="..." required="" value="<?php echo $apellido; ?>">
											</div>
										</div>
									</fieldset>

									<fieldset>
										<div class="form-group row">
											<div class="col-sm-3">
												<label class="col-form-label text-bold">PAIS</label>
												<select class="form-control" name="pais" id="pais" placeholder="..." required="">
													<?php
													$sql_pais = mysqli_query($con, "SELECT cod_pais, nombre_pais FROM tbl_pais WHERE cod_pais = $cod_pais");
													while ($row_p = mysqli_fetch_array($sql_pais)) {
														?>
														<option value="<?php echo $row_p['cod_pais']; ?>"><?php echo $row_p['nombre_pais']; ?></option>
														<?php
													}
													$sql_pais = mysqli_query($con, "SELECT cod_pais, nombre_pais FROM tbl_pais WHERE cod_pais != $cod_pais");
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
												<select class="form-control" name="departamento" id="departamento" placeholder="..." required="">
													<?php
													$sql_departamento = mysqli_query($con, "SELECT cod_departamento, nombre_dep FROM tbl_departamento WHERE cod_departamento = $cod_departamento");
													while ($row_d = mysqli_fetch_array($sql_departamento)) {
														?>
														<option value="<?php echo $row_d['cod_departamento']; ?>"><?php echo $row_d['nombre_dep']; ?></option>
														<?php
													}
													$sql_departamento = mysqli_query($con, "SELECT cod_departamento, nombre_dep FROM tbl_departamento WHERE cod_departamento != $cod_departamento");
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
												<input type="text" id="fecha_nac" name="fecha_nac" class="form-control" placeholder="DD-MM-AAAA" maxlength="10" value="<?php echo $fecha_nac; ?>">
											</div>
											<div class="col-sm-2">
												<label class="col-form-label text-bold">SEXO</label>
												<select class="form-control" name="sexo" id="sexo" placeholder="..." required="">
													<?php
													$sql_expedido = mysqli_query($con, "SELECT cod_sexo, nombre_sexo FROM tbl_sexo WHERE cod_sexo = $cod_sexo");
													while ($row_ex = mysqli_fetch_array($sql_expedido)) {
														?>
														<option value="<?php echo $row_ex['cod_sexo']; ?>"><?php echo $row_ex['nombre_sexo']; ?></option>
														<?php
													}
													$sql_expedido = mysqli_query($con, "SELECT cod_sexo, nombre_sexo FROM tbl_sexo WHERE cod_sexo != $cod_sexo");
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
												<input class="form-control" type="text" name="celular" id="celular" placeholder="..." required="" onKeyPress="return numeros(event)" value="<?php echo $celular; ?>">
											</div>
											<div class="col-sm-2">
												<label class="col-form-label text-bold">CELULAR 2</label>
												<input class="form-control" type="text" name="celular2" id="celular2" placeholder="..." onKeyPress="return numeros(event)" value="<?php echo $celular2; ?>">
											</div>
											<div class="col-sm-3">
												<label class="col-form-label text-bold">CORREO</label>
												<input class="form-control" type="mail" name="correo" id="correo" placeholder="mail@example.com" value="<?php echo $correo; ?>">
											</div>
											<div class="col-sm-5">
												<label class="col-form-label text-bold">DIRECCIÓN</label>
												<input class="form-control" type="text" name="direccion" id="direccion" placeholder="..." value="<?php echo $direccion; ?>">
											</div>
										</div>
									</fieldset>
									<hr>
									<fieldset>
										<div class="form-group row">
											<div class="col-sm-4">
												<label class="col-form-label">SUB SEDE</label>
												<select class="form-control select2-all" name="sucursal" id="sucursal" placeholder="..." required="">
													<?php
													$sql_sucursal = mysqli_query($con, "SELECT cod_sucursal, nombre_suc FROM tbl_sucursal 
														WHERE cod_sucursal = $sucursal_est");
													while ($row_s = mysqli_fetch_array($sql_sucursal)) {
														?>
														<option value="<?php echo $row_s['cod_sucursal']; ?>"><?php echo $row_s['nombre_suc']; ?></option>
														<?php
													}

													$sql_sucursal = mysqli_query($con, "SELECT cod_sucursal, nombre_suc FROM tbl_sucursal 
														WHERE cod_sucursal != $sucursal_est");
													while ($row_s = mysqli_fetch_array($sql_sucursal)) {
														?>
														<option value="<?php echo $row_s['cod_sucursal']; ?>"><?php echo $row_s['nombre_suc']; ?></option>
														<?php
													}
													?>
												</select>
											</div>
											<div class="col-sm-2">
												<label class="col-form-label">NIVEL</label>
												<select class="form-control select2-all" name="nivel" id="nivel" placeholder="...">
													<?php
													$sql_nivel = mysqli_query($con, "SELECT cod_nivel, nombre_niv FROM tbl_nivel WHERE cod_nivel = $cod_nivel");
													while ($row_n = mysqli_fetch_array($sql_nivel)) {
														?>
														<option value="<?php echo $row_n['cod_nivel']; ?>"><?php echo $row_n['nombre_niv']; ?></option>
														<?php
													}

													$sql_nivel = mysqli_query($con, "SELECT cod_nivel, nombre_niv FROM tbl_nivel WHERE cod_nivel != $cod_nivel");
													while ($row_n = mysqli_fetch_array($sql_nivel)) {
														?>
														<option value="<?php echo $row_n['cod_nivel']; ?>"><?php echo $row_n['nombre_niv']; ?></option>
														<?php
													}
													?>
												</select>
											</div>
											<div class="col-sm-6">
												<label class="col-form-label">CARRERA</label>
												<select class="form-control select2-all" name="carrera" id="carrera" placeholder="..." required="">
													<?php
													$sql_carrera = mysqli_query($con, "SELECT cod_carrera, nombre_car FROM tbl_carrera 
														WHERE cod_carrera = $cod_carrera");
													while ($row_car = mysqli_fetch_array($sql_carrera)) {
														?>
														<option value="<?php echo $row_car['cod_carrera']; ?>"><?php echo $row_car['nombre_car']; ?></option>
														<?php
													}
													?>
												</select>
											</div>
										</div>
									</fieldset>
									<?php
									if($cod_nivel >= 1 && $cod_nivel <= 2){
									?>
										<fieldset id="div_titulo">
											<div class="form-group row">
												<div class="col-sm-2">
													<label class="col-form-label text-bold">Nro TIT. BACH.</label>
													<input class="form-control" type="text" name="nro_titulo" placeholder="..." value="<?php echo $nro_titulo; ?>">
												</div>
												<div class="col-sm-2">
													<label class="col-form-label text-bold">AÑO TIT. BACH.</label>
													<input class="form-control" type="text" name="anho_titulo" placeholder="..." value="<?php echo $anho_titulo; ?>">
												</div>
											</div>
										</fieldset>
									<?php
									}
									$departamento = 0; $tipocolegio = 0;
									$sql_col = mysqli_query($con, "SELECT cod_tipocolegio_col, cod_departamento_col FROM tbl_colegio WHERE cod_colegio = $cod_colegio");
									while ($row_c = mysqli_fetch_array($sql_col)) {
										$departamento = $row_c['cod_departamento_col'];
										$tipocolegio = $row_c['cod_tipocolegio_col'];
									}
									?>
									<fieldset>
										<div class="form-group row">
											<div class="col-sm-2">
												<label class="col-form-label">DPTO COLEGIO</label>
												<select class="form-control select2-all" name="departamento_col" id="departamento_col" placeholder="..." required="">
													<?php
													$sql_departamento = mysqli_query($con, "SELECT cod_departamento, nombre_dep FROM tbl_departamento 
														WHERE cod_departamento = $departamento");
													while ($row_de = mysqli_fetch_array($sql_departamento)) {
														?>
														<option value="<?php echo $row_de['cod_departamento']; ?>"><?php echo $row_de['nombre_dep']; ?></option>
														<?php
													}
													$sql_departamento = mysqli_query($con, "SELECT cod_departamento, nombre_dep FROM tbl_departamento 
														WHERE cod_departamento != $departamento");
													while ($row_de = mysqli_fetch_array($sql_departamento)) {
														?>
														<option value="<?php echo $row_de['cod_departamento']; ?>"><?php echo $row_de['nombre_dep']; ?></option>
														<?php
													}
													?>
												</select>
											</div>
											<div class="col-sm-2">
												<label class="col-form-label">TIPO COLEGIO</label>
												<select class="form-control select2-all" name="tipo_colegio" id="tipo_colegio" placeholder="..." required="">
													<?php
													$sql_tipcol = mysqli_query($con, "SELECT cod_tipocolegio, nombre_tipcol FROM tbl_tipo_colegio 
														WHERE cod_tipocolegio = $tipocolegio");
													while ($row_tc = mysqli_fetch_array($sql_tipcol)) {
														?>
														<option value="<?php echo $row_tc['cod_tipocolegio']; ?>"><?php echo $row_tc['nombre_tipcol']; ?></option>
														<?php
													}
													$sql_tipcol = mysqli_query($con, "SELECT cod_tipocolegio, nombre_tipcol FROM tbl_tipo_colegio 
														WHERE cod_tipocolegio != $tipocolegio");
													while ($row_tc = mysqli_fetch_array($sql_tipcol)) {
														?>
														<option value="<?php echo $row_tc['cod_tipocolegio']; ?>"><?php echo $row_tc['nombre_tipcol']; ?></option>
														<?php
													}
													?>
												</select>
											</div>
											<div class="col-sm-4">
												<label class="col-form-label">COLEGIO</label>
												<select class="form-control select2-all" name="colegio" id="colegio" placeholder="..." required="">
													<?php
													$sql_colegio = mysqli_query($con, "SELECT cod_colegio, nombre_col, canton_col FROM tbl_colegio 
														WHERE cod_colegio = $cod_colegio");
													while ($row_c = mysqli_fetch_array($sql_colegio)) {
														?>
														<option value="<?php echo $row_c['cod_colegio']; ?>"><?php echo $row_c['nombre_col']." - (".$row_c['canton_col'].")"; ?></option>
														<?php
													}
													?>
												</select>
											</div>
											<div class="col-sm-4">
												<label class="col-form-label">PLAN</label>
												<select class="form-control select2-all" name="plan" id="plan" placeholder="..." required="">
													<?php
													$sql_plan = mysqli_query($con, "SELECT cod_plan, sigla_plan, precio_total_plan FROM tbl_plan 
														WHERE cod_plan = $cod_plan");
													while ($row_p = mysqli_fetch_array($sql_plan)) {
														?>
														<option value="<?php echo $row_p['cod_plan']; ?>"><?php echo $row_p['sigla_plan']." - (".$row_p['precio_total_plan']; ?> Bs.)</option>
														<?php
													}

													$sql = mysqli_query($con, "SELECT cod_plan, sigla_plan, precio_total_plan FROM tbl_plan 
														WHERE estado_plan = 1 AND cod_sucursal_plan = $sucursal_est AND cod_nivel_plan = $cod_nivel ORDER BY sigla_plan");
													while ($row_p = mysqli_fetch_array($sql)) {
														?>
														<option value="<?php echo $row_p['cod_plan']; ?>"><?php echo $row_p['sigla_plan']." - (".$row_p['precio_total_plan']; ?> Bs.)</option>
														<?php
													}
													?>
												</select>
											</div>
										</div>
									</fieldset>

									<fieldset>
										<div class="form-group row">
											<div class="col-sm-4">
												<label class="col-form-label">OBSERVACIÓN</label>
												<input class="form-control" type="text" name="observacion" placeholder="... Sin observación" value="<?php echo $observacion; ?>">
											</div>
											<div class="col-sm-2">
												<label class="col-form-label">FORMA DE LLEGADA</label>
												<select class="form-control select2-all" name="forma_llegada" placeholder="..." required="">
													<?php
													$sql_forma = mysqli_query($con, "SELECT cod_formallegada, nombre_formalle FROM tbl_forma_llegada
														WHERE cod_formallegada = $cod_formallegada");
													while ($row_f = mysqli_fetch_array($sql_forma)) {
														?>
														<option value="<?php echo $row_f['cod_formallegada']; ?>"><?php echo $row_f['nombre_formalle']; ?></option>
														<?php
													}
													$sql_forma = mysqli_query($con, "SELECT cod_formallegada, nombre_formalle FROM tbl_forma_llegada
														WHERE cod_formallegada != $cod_formallegada ORDER BY nombre_formalle");
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
													<?php
													if($cod_turno == 0){
														?><option value="">...</option><?php
													}
													$sql_turno = mysqli_query($con, "SELECT cod_turno, nombre_tur FROM tbl_turno WHERE cod_turno = $cod_turno 
														ORDER BY nombre_tur");
													while ($row_t = mysqli_fetch_array($sql_turno)) {
														?>
														<option value="<?php echo $row_t['cod_turno']; ?>"><?php echo $row_t['nombre_tur']; ?></option>
														<?php
													}
													$sql_turno = mysqli_query($con, "SELECT cod_turno, nombre_tur FROM tbl_turno WHERE cod_turno != $cod_turno 
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
												<label class="col-form-label">MODALIDAD</label>
												<select class="form-control select2-all" name="tipo_modalidad" placeholder="..." required="">
													<?php
													$sql_tipomod = mysqli_query($con, "SELECT cod_tipomodalidad, nombre_tipmod FROM tbl_tipo_modalidad WHERE cod_tipomodalidad = $tipo_modalidad");
													while ($row_tm = mysqli_fetch_array($sql_tipomod)) {
														?>
														<option value="<?php echo $row_tm['cod_tipomodalidad']; ?>"><?php echo $row_tm['nombre_tipmod']; ?></option>
														<?php
													}
													$sql_tipomod = mysqli_query($con, "SELECT cod_tipomodalidad, nombre_tipmod FROM tbl_tipo_modalidad WHERE cod_tipomodalidad != $tipo_modalidad");
													while ($row_tm = mysqli_fetch_array($sql_tipomod)) {
														?>
														<option value="<?php echo $row_tm['cod_tipomodalidad']; ?>"><?php echo $row_tm['nombre_tipmod']; ?></option>
														<?php
													}
													?>
												</select>
											</div>
											<div class="col-sm-2">
												<label class="col-form-label">ESTADO</label>
												<select class="form-control select2-all" name="tipo_estudiante" placeholder="..." required="">
													<?php
													$sql_tipoest = mysqli_query($con, "SELECT cod_tipoestudiante, nombre_tipest FROM tbl_tipo_estudiante 
														WHERE cod_tipoestudiante = $tipo_estudiante");
													while ($row_te = mysqli_fetch_array($sql_tipoest)) {
														?>
														<option value="<?php echo $row_te['cod_tipoestudiante']; ?>"><?php echo $row_te['nombre_tipest']; ?></option>
														<?php
													}
													$sql_tipoest = mysqli_query($con, "SELECT cod_tipoestudiante, nombre_tipest FROM tbl_tipo_estudiante 
														WHERE cod_tipoestudiante != $tipo_estudiante");
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
										<button class="btn btn-primary pull-right" type="submit" <?php if($cod_estudiante == 0){echo "disabled"; } ?> >Guardar el Registro</button>
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