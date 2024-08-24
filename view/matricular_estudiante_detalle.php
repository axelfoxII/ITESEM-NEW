<?php
include ('../conf/funciones.php');
include("../conf/mysql.php");
$cod_usuario = 0;
if (verificar_usuario()){
	$cod_usuario = $_SESSION['cod_usuario'];
	$nombre_pagina = "matricular_estudiante.php";
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
		<link rel="stylesheet" href="vendor/alertifyjs/css/alertify.css">
		<!-- Sweet Alert-->
    <link rel="stylesheet" href="vendor/sweetalert/dist/sweetalert.css">
    <style>
    	.ion-plus-circled{
    		font-size: 18px;
    	}
    	.ion-plus-circled:hover{
    		font-size: 20px;
    		cursor: pointer;
    	}
    </style>
	</head>
	<body class="theme-default">
		<div class="layout-container">
			<?php include('menu.php'); ?>
			<!-- Main section-->
			<main class="main-container">
				<!-- Page content-->
				<section class="section-container">
					<div class="container-fluid">
						<h4><a href="matricular_estudiante.php"><i class="ion-arrow-left-c"></i>Volver Atras</a></h4>
						<div class="cardbox">
							<div class="pb-1 bg-danger"></div>
							<div class="cardbox-body">
								<?php
								$cod_estudiante = 0;

								$nombre = ""; $carnet = "";
								$sigla_plan = "";
								$nombre_suc = 0;
								$cod_carrera = 0; $nombre_car = "";
								$nombre_niv = "";
								$estado_estudiante = 0;
								$sucursal_est = 0;
								if (isset($_GET['cod'])) {
									$cod_estudiante = $_GET['cod'];

									$sql_estudiante = mysqli_query($con, "SELECT nombre_per, apellido_per, carnet_per, sigla_plan, nombre_suc, cod_carrera_est, nombre_car, 
										resolucion_ministerial_car, nombre_niv, cod_tipoestudiante_est, cod_sucursal 
										FROM tbl_estudiante, tbl_persona, tbl_carrera, tbl_sucursal, tbl_plan, tbl_nivel 
										WHERE cod_persona_est = cod_persona AND cod_carrera_est = cod_carrera AND cod_sucursal_est = cod_sucursal AND cod_plan_est = cod_plan 
										AND cod_nivel_car = cod_nivel AND cod_estudiante = $cod_estudiante");
									while ($row_e = mysqli_fetch_array($sql_estudiante)) {
										$nombre = $row_e['nombre_per']." ".$row_e['apellido_per'];
										$carnet = $row_e['carnet_per'];
										$sigla_plan = $row_e['sigla_plan'];
										$nombre_suc = $row_e['nombre_suc'];
										$cod_carrera = $row_e['cod_carrera_est'];
										$nombre_niv = $row_e['nombre_niv'];
										$tipo_estudiante = $row_e['cod_tipoestudiante_est'];
										$nombre_car = $row_e['nombre_car']." - ".$row_e['resolucion_ministerial_car'];
										$sucursal_est = $row_e['cod_sucursal'];
									}
								}
								?>
								<div class="row">
									<div class="col-6">
										<p><b>NOMBRE: </b><?php echo $nombre; ?></p>
										<p><b>CARNET: </b><?php echo $carnet; ?></p>
										<p><b>PLAN: </b><?php echo $sigla_plan; ?></p>
										<p><b>ESTADO: </b>
										<?php
										switch ($tipo_estudiante) {
											case '1':
												echo "<button class='btn btn-success btn-sm'>ACTIVO</button>";
												break;
										}
										?>
										</p>
									</div>
									<div class="col-6">
										<p><b>SUCURSAL: </b><?php echo $nombre_suc; ?></p>
										<p><b>NIVEL: </b><?php echo $nombre_niv; ?></p>
										<p><b>CARRERA: </b><?php echo $nombre_car; ?></p>
									</div>
								</div>
								<hr>

								<div class="no-more-tables">
									<table id="datatable_buscador" class="table table-striped table-sm">
										<thead>
											<tr align="center">
												<th>SIGLA</th>
												<th>MATERIA</th>
												<th>ESTRUCTURA</th>
												<th>ESTADO</th>
												<th>OFERTA MAT.</th>
												<th>OPCIONES</th>
											</tr>
										</thead>
											<?php
											$cod_materia = 0;
											$sql_materia = mysqli_query($con, "SELECT cod_materia, sigla_mat, nombre_mat, nombre_estmat FROM tbl_materia, tbl_estructura_materia 
												WHERE cod_estructura_materia_mat = cod_estructura_materia AND cod_carrera_mat = $cod_carrera 
												ORDER BY cod_estructura_materia_mat, numero_mat, nombre_mat");
											while ($row_m = mysqli_fetch_array($sql_materia)) {
												$cod_materia = $row_m['cod_materia'];
												$enlace = 1;
												$cod_historico = 0; $cod_oferta = 0;
												?>
												<tr>
													<td data-title="SIGLA"><?php echo $row_m['sigla_mat']; ?></td>
													<td data-title="MATERIA"><?php echo $row_m['nombre_mat']; ?></td>
													<td data-title="ESTRUCTURA" align="center"><?php echo $row_m['nombre_estmat']; ?></td>
													<td data-title="ESTADO" align="center">
														<?php
														$estado_res = "";
														// Ver si la materia ya esta en la tabla tbl_historico
														$sql_his = mysqli_query($con, "SELECT cod_historico, cod_oferta_materia_his, nota_final_his FROM tbl_historico, tbl_oferta_materia 
															WHERE cod_oferta_materia_his = cod_oferta_materia AND cod_estudiante_his = $cod_estudiante AND cod_materia_of = $cod_materia 
															AND estado_his = 1 AND (nota_final_his > 50 OR nota_final_his IS NULL)");
														if(mysqli_num_rows($sql_his) > 0){
															$enlace = 0;
															while ($row_h = mysqli_fetch_array($sql_his)) {
																$cod_historico = $row_h['cod_historico'];
																$cod_oferta = $row_h['cod_oferta_materia_his'];
																if($row_h['nota_final_his'] == NULL)
																	$estado_res = "<i class='text-primary'>Inscrita</i> - <a data-toggle='modal' data-target='#modal_nota' data-whatever='".$cod_historico."' ><i class='ion-plus-circled text-primary'></i></a>";
																else
																	$estado_res = "<i class='text-success'>Aprovada (".$row_h['nota_final_his'].")</i>";
															}
														}else{
															$estado_res = "<i>Pendiente</i>";
														}
														
														// CONTAR REPROBADAS
														$sql_rep = mysqli_query($con, "SELECT cod_historico FROM tbl_historico, tbl_oferta_materia 
															WHERE cod_oferta_materia_his = cod_oferta_materia AND cod_estudiante_his = $cod_estudiante AND cod_materia_of = $cod_materia 
															AND estado_his = 1 AND nota_final_his >= 0 AND nota_final_his <= 50");
														if (mysqli_num_rows($sql_rep) > 0) {
															$estado_res = $estado_res." - <i class='text-danger'>R(".mysqli_num_rows($sql_rep).")</i>";
														}

														echo $estado_res;
														?>
													</td>
													<td data-title="OFERTA MAT." align="center">
														<?php
														if($cod_oferta > 0){
															?>
															<a href="" data-toggle='modal' data-target='#modal_detalle' data-whatever='<?php echo $cod_oferta; ?>' >
																<?php
																// OBTENER DATOS DE LA OFERTA
																$sql_oferta = mysqli_query($con, "SELECT nombre_gest, nombre_peri, sigla_mat FROM tbl_oferta_materia, tbl_materia, tbl_periodo, tbl_gestion 
																	WHERE cod_materia_of = cod_materia  AND cod_periodo_of = cod_periodo AND cod_gestion_peri = cod_gestion 
																	AND cod_oferta_materia = $cod_oferta");
																while ($row_o = mysqli_fetch_array($sql_oferta)) {
																	echo $row_o['nombre_gest']."-".$row_o['nombre_peri']." / ".$row_o['sigla_mat'];
																}
																?>
															</a>
															<?php
														}
														?>
													</td>
													<td data-title="OPCIONES" align="center">
														<?php
														if($enlace == 1){
															?>
															<a href="" data-toggle='modal' data-target='#modal_oferta' data-whatever='<?php echo $row_m['cod_materia']; ?>' onclick="matricular_tabla(<?php echo $row_m['cod_materia']; ?>)">Matricular</a>
														<?php 
														}else{
															?>
															<a style="cursor: pointer;" onclick="retirar(<?php echo $cod_historico; ?>)" class="text-danger" >Retirar</a>
															<?php
														}
														?>
													</td>
												</tr>
												<?php
											}
											?>
										<tbody>
										</tbody>
									</table>
								</div>

							</div>
						</div>
					</div>
				</section>
			</main>
		</div>

		<div class="modal fade" id="modal_oferta" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
			<div class="modal-dialog modal-lg" role="document">
				<div class="modal-content">
					<div class="modal-header bg-primary">
						<h5 class="modal-title" id="exampleModalLabel">OFERTAS DE MATERIAS</h5>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
					<div class="modal-body">
						<input type="hidden" name="cod_materia" id="cod_materia" value="0">
						<div class="form-group row">
							<div class="col-sm-4">
								<label class="col-form-label">TIPO PERIODO</label>
								<select class="form-control select2-all" name="tipo_periodo" id="tipo_periodo" style="width: 100%;" placeholder="..." required="">
									<option value="">...</option>
									<?php
									$sql_tipo = mysqli_query($con, "SELECT cod_tipoperiodo, nombre_tipper FROM tbl_tipo_periodo");
									while ($row_ti = mysqli_fetch_array($sql_tipo)) {
										?>
										<option value="<?php echo $row_ti['cod_tipoperiodo']; ?>"><?php echo $row_ti['nombre_tipper']; ?></option>
										<?php
									}
									?>
								</select>
							</div>
							<div class="col-sm-4">
								<label class="col-form-label">GESTIÓN</label>
								<select class="form-control select2-all" name="gestion" id="gestion" style="width: 100%;" placeholder="..." required="">
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
							<div class="col-sm-4">
								<label class="col-form-label">PERIODO</label>
								<select class="form-control select2-all" name="periodo" id="periodo" style="width: 100%;" placeholder="..." required="">
									<option value="">...</option>
								</select>
							</div>
						</div>
						<hr>
						<div id="resultado_of"></div>
						<div class="clearfix"></div>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
						<input type="submit" class="btn btn-primary" id="update_art" value="Modificar Registro">
					</div>
				</div>
			</div>
		</div>

		<div class="modal fade" id="modal_detalle" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
			<div class="modal-dialog modal-lg" role="document">
				<div class="modal-content">
					<div class="modal-header bg-primary">
						<h5 class="modal-title" id="exampleModalLabel">DETALLE DE LA OFERTA</h5>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
					<div class="modal-body">
						<input type="hidden" name="cod_oferta" id="cod_oferta" value="0">
						<div class="form-group row">
							<div class="col-sm-6">
								<label class="col-form-label text-bold">MATERIA</label>
								<input type="text" class="form-control" name="materia_of" id="materia_of" placeholder="...">
							</div>
							<div class="col-sm-6">
								<label class="col-form-label text-bold">DOCENTE</label>
								<input type="text" class="form-control" name="docente_of" id="docente_of" placeholder="...">
							</div>
						</div>

						<div class="form-group row">
							<div class="col-sm-4">
								<label class="col-form-label text-bold">PERIODO</label>
								<input type="text" class="form-control" name="periodo_of" id="periodo_of" placeholder="...">
							</div>
							<div class="col-sm-4">
								<label class="col-form-label text-bold">FECHA INICIO</label>
								<input type="text" class="form-control" name="fecha_inicio_of" id="fecha_inicio_of" placeholder="...">
							</div>
							<div class="col-sm-4">
								<label class="col-form-label text-bold">FECHA FIN</label>
								<input type="text" class="form-control" name="fecha_fin_of" id="fecha_fin_of" placeholder="...">
							</div>
						</div>

						<div class="form-group row">
							<div class="col-sm-4">
								<label class="col-form-label text-bold">TURNO</label>
								<input type="text" class="form-control" name="turno_of" id="turno_of" placeholder="...">
							</div>
							<div class="col-sm-4">
								<label class="col-form-label text-bold">AULA</label>
								<input type="text" class="form-control" name="aula_of" id="aula_of" placeholder="...">
							</div>
						</div>
						<div class="clearfix"></div>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
					</div>
				</div>
			</div>
		</div>

		<!-- MODAL NOTA -->
		<div class="modal fade" id="modal_nota" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
			<div class="modal-dialog modal-lg" role="document">
				<div class="modal-content">
					<div class="modal-header bg-primary">
						<h5 class="modal-title" id="exampleModalLabel">NOTAS</h5>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
					<div class="modal-body">
						<input type="hidden" name="cod_historico" id="cod_historico" value="0">
						<div class="form-group row">
							<div class="col-sm-3">
								<label class="col-form-label">NOTA UNO</label>
								<input type="text" class="form-control" name="nota_uno" id="nota_uno" placeholder="..." maxlength="3" onKeyPress="return numeros(event)">
							</div>
							<div class="col-sm-3">
								<label class="col-form-label">NOTA DOS</label>
								<input type="text" class="form-control" name="nota_dos" id="nota_dos" placeholder="..." maxlength="3" onKeyPress="return numeros(event)">
							</div>
							<div class="col-sm-3">
								<label class="col-form-label">NOTA TRES</label>
								<input type="text" class="form-control" name="nota_tres" id="nota_tres" placeholder="..." maxlength="3" onKeyPress="return numeros(event)">
							</div>
							<div class="col-sm-3">
								<label class="col-form-label">FINAL</label>
								<input type="text" class="form-control" name="nota_final" id="nota_final" placeholder="..." maxlength="3" onKeyPress="return numeros(event)">
							</div>
						</div>
						<div class="clearfix"></div>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
						<input type="submit" class="btn btn-primary" id="registrar_nota" value="Registrar Nota">
					</div>
				</div>
			</div>
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
    <!-- Alertify -->
    <script src="vendor/alertifyjs/alertify.js"></script>
    <!-- Sweet Alert-->
    <script src="vendor/sweetalert/dist/sweetalert-dev.js"></script>

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

    		$('#modal_oferta').on('show.bs.modal', function (event) {
					var button = $(event.relatedTarget);
					var recipient = button.data('whatever');
					var modal = $(this);
					modal.find('#cod_materia').val(recipient);
				});

				$('#modal_detalle').on('show.bs.modal', function (event) {
					var button = $(event.relatedTarget);
					var recipient = button.data('whatever');
					var modal = $(this);
					modal.find('#cod_oferta').val(recipient);
					$('#cod_oferta').val(recipient).trigger('change');
				});

				$('#modal_nota').on('show.bs.modal', function (event) {
					var button = $(event.relatedTarget);
					var recipient = button.data('whatever');
					var modal = $(this);
					modal.find('#cod_historico').val(recipient);
				});

				$("#periodo").change(function(event){
    			var per = $("#periodo").find(':selected').val();
    			var cod_mat = $("#cod_materia").val();
    			var suc = "<?php echo $sucursal_est; ?>";
    			if(per > 0 && cod_mat > 0 && per != "" && cod_mat != ""){
	    			$.ajax({
		    			type: "POST",
		    			url: "../class/matricular_funciones.php",
		    			data: 'funcion=buscar_oferta2&mat='+cod_mat+"&per="+per+'&suc='+suc,
		    			dataType: "html",
		    			error: function(){
		    				alertify.notify('<div class="text-center text-white">ERROR AL REALIZAR EL PROCESO.!</div>','error', 15).dismissOthers();
		    			},
		    			success: function(data){
		    				$("#resultado_of").empty();
		    				$("#resultado_of").append(data);
		    			}
		    		});
	    		}
    		});

    		$("#cod_oferta").change(function(event){
    			var cod_of = $("#cod_oferta").val();
    			if(cod_of > 0 && cod_of != ""){
	    			$.ajax({
		    			type: "POST",
		    			url: "../class/matricular_funciones.php",
		    			data: 'funcion=buscar_oferta3&cod_of='+cod_of,
		    			dataType: "json",
		    			error: function(){
		    				alertify.notify('<div class="text-center text-white">ERROR AL REALIZAR EL PROCESO.!</div>','error', 15).dismissOthers();
		    			},
		    			success: function(data){
		    				$("#materia_of").val(data.materia_of);
		    				$("#docente_of").val(data.docente_of);
		    				$("#periodo_of").val(data.periodo_of);
		    				$("#fecha_inicio_of").val(data.fecha_inicio_of);
		    				$("#fecha_fin_of").val(data.fecha_fin_of);
		    				$("#turno_of").val(data.turno_of);
		    				$("#aula_of").val(data.aula_of);
		    			}
		    		});
	    		}
    		});

				$('#nota_uno, #nota_dos, #nota_tres').keyup(function() {
					var nota1 = $("#nota_uno").val();
					var nota2 = $("#nota_dos").val();
					var nota3 = $("#nota_tres").val();

					var pro = parseFloat(nota1) + parseFloat(nota2) + parseFloat(nota3);
					pro = pro / 3;
					pro = pro.toFixed(0);

					if(pro !== NaN)
						$("#nota_final").val(pro);
					else
						$("#nota_final").val("");
				});

    		$('#registrar_nota').click(function() {
		    	var nota_uno = $("#nota_uno").val();
		    	var nota_dos = $("#nota_dos").val();
		    	var nota_tres = $("#nota_tres").val();
		    	var nota_final = $("#nota_final").val();
		    	var cod_his = $("#cod_historico").val();
		    	var cod_usu = "<?php echo $cod_usuario; ?>";

		    	$.ajax({
	    			type: "POST",
	    			url: "../class/matricular_funciones.php",
	    			data: 'funcion=registrar_nota&cod_his='+cod_his+'&nota_uno='+nota_uno+'&nota_dos='+nota_dos+'&nota_tres='+nota_tres+'&nota_final='+nota_final+'&cod_usu='+cod_usu,
	    			dataType: "json",
	    			error: function(){
	    				alertify.notify('<div class="text-center text-white">ERROR AL REALIZAR EL PROCESO.!</div>','error', 15).dismissOthers();
	    			},
	    			success: function(data){
	    				if(data.tipo == 1){
	    					$('#modal_nota').modal('hide');
	    					swal({
	    						title: "¡BIEN!", 
	    						text: data.mensaje, 
	    						type: "success"
	    					}, 
	    					function(){ 
	    						location.reload(); 
	    					});
	    				}else if(data.tipo == 2){
	    					swal("¡ERROR!", data.mensaje, "error");
	    				}
	    			}
	    		});
		    });

    	});

    	function matricular_tabla(cod_mat){
    		var suc = "<?php echo $sucursal_est; ?>";
    		$.ajax({
    			type: "POST",
    			url: "../class/matricular_funciones.php",
    			data: 'funcion=buscar_oferta&mat='+cod_mat+'&suc='+suc,
    			dataType: "html",
    			error: function(){
    				alertify.notify('<div class="text-center text-white">ERROR AL REALIZAR EL PROCESO.!</div>','error', 15).dismissOthers();
    			},
    			success: function(data){
    				$("#resultado_of").empty();
    				$("#resultado_of").append(data);
    			}
    		});
    	}

    	function matricular(cod_of){
    		var cod_est = "<?php echo $cod_estudiante; ?>";
    		var cod_usu = "<?php echo $cod_usuario; ?>";
    		$.ajax({
    			type: "POST",
    			url: "../class/matricular_funciones.php",
    			data: 'funcion=matricular&cod_of='+cod_of+'&cod_est='+cod_est+'&cod_usu='+cod_usu,
    			dataType: "json",
    			error: function(){
    				alertify.notify('<div class="text-center text-white">ERROR AL REALIZAR EL PROCESO.!</div>','error', 15).dismissOthers();
    			},
    			success: function(data){
    				if(data.tipo == 1){
    					$('#modal_oferta').modal('hide');
    					swal({
    						title: "¡BIEN!", 
    						text: data.mensaje, 
    						type: "success"
    					}, 
    					function(){ 
    						location.reload(); 
    					});
    				}else if(data.tipo == 2){
    					swal("¡ERROR!", data.mensaje, "error");
    				}
    			}
    		});
    	}

    	function retirar(cod_his){
    		swal({
					title: "¿Seguro qué deseas retirar la materia?",
					text: "No podrás deshacer este acción...",
					type: "warning",
					showCancelButton: true,
					cancelButtonText: "Cancelar",
					confirmButtonColor: "#DD6B55",
					confirmButtonText: "Adelante",
					closeOnConfirm: false },

					function(){
						var cod_usu = "<?php echo $cod_usuario; ?>";
						$.ajax({
		    			type: "POST",
		    			url: "../class/matricular_funciones.php",
		    			data: 'funcion=retirar&cod_his='+cod_his+'&cod_usu='+cod_usu,
		    			dataType: "json",
		    			error: function(){
		    				alertify.notify('<div class="text-center text-white">ERROR AL REALIZAR EL PROCESO.!</div>','error', 15).dismissOthers();
		    			},
		    			success: function(data){
		    				if(data.tipo == 1){
		    					swal({
		    						title: "¡BIEN!", 
		    						text: data.mensaje, 
		    						type: "success"
		    					}, 
		    					function(){ 
		    						location.reload(); 
		    					});
		    				}else if(data.tipo == 2){
		    					swal("¡ERROR!", data.mensaje, "error");
		    				}
		    			}
		    		});
					}
				);
    	}
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