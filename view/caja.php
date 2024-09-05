<?php
include ('../conf/funciones.php');
include("../conf/mysql.php");
$cod_usuario = 0;
if (verificar_usuario()){
	$cod_usuario = $_SESSION['cod_usuario'];
	$nombre_pagina = "caja.php";
	// Verificar el privilegio de la pagina
	$sql_pagina = mysqli_query($con, "SELECT cod_privilegio FROM tbl_submenu, tbl_privilegio, tbl_usuario 
		WHERE cod_submenu = cod_submenu_priv AND cod_perfil_priv = cod_perfil_us AND estado_priv = 1 
		AND cod_usuario = $cod_usuario AND enlace_subm = '$nombre_pagina'");
	if(mysqli_num_rows($sql_pagina) > 0){

		// VACIAR EL DETALLE DE LA TABLA tbl_detalle_movimiento
		$delete_det = mysqli_query($con, "DELETE FROM tbl_detalle_movimiento WHERE cod_usuario_nmov = $cod_usuario");
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
		<!-- Alertify -->
		<link rel="stylesheet" href="vendor/alertifyjs/css/alertify.css" />
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
								<center><h2>REGISTRO DE TRANSACCIONES</h2></center>
								<h5><a href="caja.php"><i class="ion-arrow-left-c"></i>Nueva Transacción</a></h5>
								<input type="hidden" id="cod_estudiante" name="cod_estudiante" value="">
								<div id="buscar">
									<div class="row">
										<div class="col-sm-1"></div>
										<div class="col-sm-11"><h5 class="text-primary">Buscar:</h5></div>
									</div>
									<fieldset>
										<div class="form-group row">
											<div class="col-sm-1"></div>
											<div class="col-sm-2">
												<select name="sucursal" id="sucursal" class="form-control select2-all">
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
											<div class="col-sm-2">
												<input class="form-control" type="text" id="buscar_carnet" name="carnet" placeholder="... Por Carnet" autocomplete="off">
											</div>
											<div class="col-sm-3">
												<input class="form-control" type="text" id="buscar_nombre" name="nombre" placeholder="... Por Nombre" autocomplete="off">
											</div>
											<div class="col-sm-3">
												<input class="form-control" type="text" id="buscar_apellido" name="apellido" placeholder="... Por Apellido" autocomplete="off">
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
								</div>
								<hr>
								<h5 class="text-primary">Información del Estudiante</h5>
								<fieldset>
									<div class="form-group row">
										<div class="col-sm-4">
											<label class="col-form-label">SUCURSAL</label>
											<input type="hidden" name="cod_sucursal" id="cod_sucursal" value="0">
											<input class="form-control" type="text" id="nombre_suc" name="nombre_suc" placeholder="..." disabled="" value="">
										</div>
										<div class="col-sm-4"></div>
										<div class="col-sm-4">
											<label class="col-form-label">OBSERVACIONES</label>
											<input class="form-control" type="text" id="observacion" name="observacion" placeholder="..." value="">
										</div>
									</div>
								</fieldset>
								<fieldset>
									<div class="form-group row">
										<div class="col-sm-4">
											<label class="col-form-label">ESTUDIANTE</label>
											<input class="form-control" type="text" id="estudiante" name="estudiante" placeholder="..." disabled="" value="">
										</div>
										<div class="col-sm-4">
											<label class="col-form-label">CARRERA</label>
											<input class="form-control" type="text" id="carrera" name="carrera" placeholder="..." disabled="" value="">
										</div>
										<div class="col-sm-2">
											<label class="col-form-label">PLAN</label>
											<input class="form-control" type="text" id="plan" name="plan" placeholder="..." disabled="" value="">
										</div>
										<div class="col-sm-2">
											<label class="col-form-label">CUENTA</label>
											<input class="form-control" type="text" id="cuenta" name="cuenta" placeholder="..." disabled="" value="">
										</div>
									</div>
								</fieldset>

								<fieldset>
									<div class="form-group row">
										<div class="col-sm-4">
											<!-- ARTICULO -->
											<label class="col-form-label">ÁRTÍCULO</label>
											<select class="form-control select2-all" name="articulo" id="articulo" disabled="">
												<option value=""></option>
											</select>
										</div>
										<div class="col-sm-3">
											<!-- ARTICULO -->
											<label class="col-form-label">FORMA DE PAGO</label>
											<select class="form-control select2-all" name="tipo_pago" id="tipo_pago" disabled="">
												<option value="">...</option>
												<?php
												$sql_tipo_pago = mysqli_query($con, "SELECT cod_tipopago, nombre_tipopago FROM tbl_tipo_pago 
													WHERE estado_tipopago = 1");
												while ($row_tp = mysqli_fetch_array($sql_tipo_pago)) {
													?>
													<option value="<?php echo $row_tp['cod_tipopago']; ?>"><?php echo $row_tp['nombre_tipopago']; ?></option>
													<?php
												}
												?>
											</select>
										</div>
										<div class="col-sm-2" id="div_transaccion" style="display: none;">
											<label class="col-form-label">Nro TRANSACCIÓN</label>
											<input class="form-control" type="text" id="nro_transaccion" name="nro_transaccion" placeholder="..." value="">
										</div>
									</div>
								</fieldset>
								<hr>
								<fieldset>
									<div class="row">
										<div class="col-sm-6">
											<h5 class="text-primary text-bold">Registro de Materias</h5>
											<p id="historico"></p>
											<div class="form-group row">
												<div class="col-sm-4">
													<label class="col-form-label">TIPO PERIODO</label>
													<select class="form-control select2-all" name="tipo_periodo" id="tipo_periodo" style="width: 100%;" placeholder="..." required="">
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
												<div class="col-sm-4">
													<label class="col-form-label">GESTIÓN</label>
													<select class="form-control select2-all" name="gestion" id="gestion" style="width: 100%;" placeholder="..." required="">
														<?php
														$anho = date("Y");
														$cod_gestion = 0;
														$sql_gestion = mysqli_query($con, "SELECT cod_gestion, nombre_gest FROM tbl_gestion WHERE nombre_gest = '$anho'");
														while ($row_g = mysqli_fetch_array($sql_gestion)) {
															$cod_gestion = $row_g['cod_gestion'];
															?>
															<option value="<?php echo $row_g['cod_gestion']; ?>"><?php echo $row_g['nombre_gest']; ?></option>
															<?php
														}
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
													<select class="form-control select2-all" name="periodo" id="periodo" style="width: 100%;" placeholder="..." required="" disabled="">
														<option value="">...</option>
													</select>
												</div>
											</div>
											<div class="form-group row">
												<div class="col-sm-4">
													<label class="col-form-label">TURNO</label>
													<select class="form-control select2-all" name="turno" id="turno" disabled="">
														<option value="">...</option>
														<?php
														$sql_turno = mysqli_query($con, "SELECT cod_turno, nombre_tur FROM tbl_turno");
														while ($row_t = mysqli_fetch_array($sql_turno)) {
															?>
															<option value="<?php echo $row_t['cod_turno']; ?>"><?php echo $row_t['nombre_tur']; ?></option>
															<?php
														}
														?>
													</select>
												</div>
												<div class="col-sm-4">
													<label class="col-form-label">GRUPO</label>
													<select class="form-control select2-all" name="grupo" id="grupo" disabled="">
														<option value="">...</option>
													</select>
												</div>
												<div class="col-sm-4">
													<label class="col-form-label">MODALIDAD</label>
													<select class="form-control select2-all" name="modalidad" id="modalidad" disabled="">
														<option value="">...</option>
														<?php
														$sql_modalidad = mysqli_query($con, "SELECT cod_tipomodalidad, nombre_tipmod FROM tbl_tipo_modalidad");
														while ($row_m = mysqli_fetch_array($sql_modalidad)) {
															?>
															<option value="<?php echo $row_m['cod_tipomodalidad']; ?>"><?php echo $row_m['nombre_tipmod']; ?></option>
															<?php
														}
														?>
													</select>
												</div>
												<div class="col-sm-12 text-right">
													<br>
													<a class="btn btn-success text-white" id="verificar_materia">Verificar</a>
												</div>
											</div>
										</div>
										<!-- COL-6 -->
										<div class="col-sm-6" id="div_cantidad_materia"></div>
									</div>
								</fieldset>
								<hr>
								<h5 class="text-primary">Detalle de la Venta</h5>
								<table class="table">
									<thead>
										<tr class="table-primary">
											<th><font color="white">NOMBRE</font></th>
											<th><font color="white">P. UNIDAD</font></th>
											<th><font color="white">FACT (16%)</font></th>
											<th><font color="white">CANT.</font></th>
											<th><font color="white">DSCTO.</font></th>
											<th><font color="white">SUB TOTAL</font></th>
											<th><font color="white">OPCIONES</font></th>
										</tr>
									</thead>
									<tbody id="tabla_detalle">
									</tbody>
								</table>

								<fieldset>
									<div class="form-group row">
										<div class="col-sm-2">
											<label class="col-form-label"><b>TOTAL Bs:</b></label>
											<input type="text" class="form-control textbox" id="totales" name="totales" value="" readonly />
										</div>
										<div class="col-sm-2">
											<br>
											<label class="switch switch-warn">
												<input type="checkbox" id="factura-16"><span></span>
											</label>Factura +16%
										</div>

										<div class="col-sm-1"></div>

										<div class="col-sm-2">
											<label class="col-form-label"><b>EFECTIVO:</b></label>
											<input type="text" class="form-control textbox" id="efectivo" name="efectivo" value=""/>
										</div>

										<div class="col-sm-2">
											<label class="col-form-label"><b>DEVOLUCIÓN</b></label>
											<input type="text" class="form-control textbox" id="devolucion" name="devolucion" value="" readonly />
										</div>
									</div>
								</fieldset>

								<div class="row text-right">
									<div class="col-sm-12">
										<button id ="btn_registrar" class="btn btn-primary width-80 mb-xs" role="button" disabled>Realizar Transaccion</button>
									</div>
								</div>
							</div>
						</div>
					</div>
				</section>
			</main>
		</div>

		<!-- VENTANA MODAL PARA MODIFICAR EL DETALLE DE LOS ARTICULOS -->
		<div class="modal fade" id="modal_articulo" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
			<div class="modal-dialog" role="document">
				<div class="modal-content">
					<div class="modal-header bg-primary">
						<h5 class="modal-title" id="exampleModalLabel">MODIFICAR ARTICULO</h5>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
					<div class="modal-body">
						<input type="hidden" name="cod_movimiento" id="cod_movimiento" value="">
						<input type="hidden" name="cod_articulo_modal" id="cod_articulo_modal" value="">
						<div class="row">
							<div class="col-sm-12">
								<div class="form-group">
									<label for="recipient-name" class="col-form-label">Articulo:</label>
									<input type="text" class="form-control" id="nombre_art" name="nombre_art" placeholder="..." value="" disabled="disabled">
								</div>
							</div>
						</div>

						<div class="row">
							<div class="col-sm-4">
								<div class="form-group">
									<label for="recipient-name" class="col-form-label">Cantidad:</label>
									<input type="number" class="form-control" id="cantidad" name="cantidad" min="1" placeholder="...0" value="">
								</div>
							</div>
						</div>

						<div class="row">
							<div class="col-sm-4">
								<div class="form-group">
									<label for="recipient-name" class="col-form-label">Pr. Unitario:</label>
									<input type="text" class="form-control" id="precio" name="precio" placeholder="...0.00" value="">
								</div>
							</div>
							<div class="col-sm-4">
								<div class="form-group">
									<label for="recipient-name" class="col-form-label">Dscto:</label>
									<input type="text" class="form-control" id="dscto" name="dscto" placeholder="...0.00" value="" required="required">
								</div>
							</div>
							<div class="col-sm-4">
								<div class="form-group">
									<label for="recipient-name" class="col-form-label">Sub-Total:</label>
									<input type="text" class="form-control" id="subtotal" name="subtotal" placeholder="...0.00" value="" required="required">
								</div>
							</div>
						</div>
						<div class="clearfix"></div>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
						<input type="submit" class="btn btn-primary" id="update_art" value="Modificar Registro">
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
		<!-- Alertify -->
		<script src="vendor/alertifyjs/alertify.js"></script>
		<!-- App script-->
		<script src="js/app.js"></script>

		<style type="text/css">
			.ion-loop{
				font-size: 23px;
			}
			.ion-loop:hover{
				font-size: 25px;
				cursor: pointer;
			}
			.ion-trash-a{
				font-size: 23px;
			}
			.ion-trash-a:hover{
				font-size: 25px;
				cursor: pointer;
			}
			.ion-checkmark-round{
				color: #FFFFFF;
			}
			.ion-checkmark-round:hover{
				cursor: pointer;
			}
		</style>

		<script language="JavaScript" type="text/JavaScript">
			var acuenta_activo = 0;
			$(document).ready(function(){
				$('.select2-all').select2({
					placeholder: "..."
				});

				calcular(acuenta_activo);

				$("#buscar_carnet").keyup(function(e){
					if($(this).val().length > 2){
						carnet = $("#buscar_carnet").val();
						sucursal = $("#sucursal").find(':selected').val();
						$.ajax({
							type: "POST",
							url: "../class/buscar_caja_datos.php",
							data: 'carn='+carnet+'&suc='+sucursal,
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
						sucursal = $("#sucursal").find(':selected').val();
						$.ajax({
							type: "POST",
							url: "../class/buscar_caja_datos.php",
							data: "nom="+nombre+'&ape='+apellido+'&suc='+sucursal,
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

				$("#tipo_periodo").change(function(event){
    			$("#periodo").select2('val','...');
    			var tipper = $("#tipo_periodo").find(':selected').val();
    			var gest = $("#gestion").find(':selected').val();
    			$("#periodo").load('../class/buscar_select.php?tipo=periodo&tipper='+tipper+'&gest='+gest);
    		});

    		$("#gestion").change(function(event){
    			$("#div_cantidad_materia").empty();
    			$("#periodo").select2('val','...');
    			var tipper = $("#tipo_periodo").find(':selected').val();
    			var gest = $("#gestion").find(':selected').val();
    			$("#periodo").load('../class/buscar_select.php?tipo=periodo&tipper='+tipper+'&gest='+gest);
    		});

    		$("#turno").change(function(event){
    			$("#grupo").select2('val','...');
    			var cod_est = $("#cod_estudiante").val();
    			var tur = $("#turno").find(':selected').val();
    			$("#grupo").load('../class/buscar_select.php?tipo=grupo&cod_est='+cod_est+'&tur='+tur);
    		});

				$("#sucursal").change(function(){
					$("#buscar_carnet").val('');
					$("#buscar_nombre").val('');
					$("#buscar_apellido").val('');
					$("#resultado_est").empty();
				});

				$("#cod_estudiante").change(function(){
					var cod_est = $("#cod_estudiante").val();
					var cod_usuario = "<?php echo $cod_usuario; ?>";

					$.ajax({
						type: "POST",
						url: "../class/caja_funciones.php",
						data: "funcion=estudiante&cod_est="+cod_est,
						dataType: "json",
						error: function(){ alert("Error en la peticion AJAX - Datos"); },
						success: function(data){
							$("#estudiante").val(data.nombre_per+" "+data.apellido_per+" - "+data.carnet_per);
							$("#carrera").val(data.nombre_car);
							$("#plan").val(data.sigla_plan);
							$("#nombre_suc").val(data.nombre_suc);
							$("#cod_sucursal").val(data.cod_sucursal);
							$("#observacion").val(data.observacion_est);
							$("#historico").html(data.historico);
							$("#buscar").hide();
							$("#tipo_periodo").prop('disabled', false);
							$("#gestion").prop('disabled', false);
							$("#periodo").prop('disabled', false);
							$("#articulo").prop('disabled', false);
							$("#tipo_pago").prop('disabled', false);
							$("#periodo").prop('disabled', false);	
							$("#turno").prop('disabled', false);
							$("#grupo").prop('disabled', false);
							$("#estructura").prop('disabled', false);
							$("#modalidad").prop('disabled', false);

							$("#articulo").select2('val','...');
							$("#articulo").load('../class/buscar_select.php?tipo=articulo_caja&suc='+data.cod_sucursal);
						}
					});

					// Obtener el saldo de la cuenta
					$.ajax({
						type: "POST",
						url: "../class/caja_funciones.php",
						data: "funcion=cuenta&cod_est="+cod_est+"&cod_usu="+cod_usuario,
						dataType: "json",
						error: function(){ alert("Error en la peticion AJAX - Cuenta"); },
						success: function(data){
							$("#cuenta").val(data.cuenta_text);
							if(data.cuenta >= 5){
								$("#cuenta").css("background-color", "#89D6C1");
								$("#cuenta").css("color", "#424242");
							}else if(data.cuenta < 0){
								$("#cuenta").css("background-color", "#DD9B9B");
								$("#cuenta").css("color", "#424242");
							}

							$.ajax({
								type: "POST",
								url: "../class/caja_funciones.php",
								data: "funcion=generar_tabla&cod_usu="+cod_usuario,
								dataType: "json",
								beforeSend: function(){
									// $("#tabla_detalle").html("<div class='text-center'><img width='10%' src='img/load.gif'/></div>");
								},
								error: function(){ alert("Error en la peticion AJAX - Generar Tabla"); },
								success: function(data){
									acuenta_activo = data.acuenta_activo;
									$("#tabla_detalle").empty();
									$("#tabla_detalle").html(data.tabla);
									$("#totales").val(data.total);
								}
							});
						}
					});
				});

				$("#articulo").change(function(){
					var cod_articulo = $("#articulo").val();
					var cod_usuario = "<?php echo $cod_usuario; ?>";
					var cod_est = $("#cod_estudiante").val();
					if(cod_articulo != null && cod_articulo != "null"){
						$.ajax({
							type: "POST",
							url: "../class/caja_funciones.php",
							data: "funcion=detalle_articulo&cod_art="+cod_articulo+"&cod_est="+cod_est+"&cod_usu="+cod_usuario,
							dataType: "html",
							error: function(){ alert("Error en la peticion AJAX - Articulo"); },
							success: function(data){
								$('#factura-16').prop('checked', false);
								$.ajax({
									type: "POST",
									url: "../class/caja_funciones.php",
									data: "funcion=generar_tabla&cod_usu="+cod_usuario,
									dataType: "json",
									beforeSend: function(){
										// $("#tabla_detalle").html("<div class='text-center'><img width='10%' src='img/load.gif'/></div>");
									},
									error: function(){ alert("Error en la peticion AJAX - Generar Tabla"); },
									success: function(data){
										acuenta_activo = data.acuenta_activo;
										$("#tabla_detalle").empty();
										$("#tabla_detalle").html(data.tabla);
										$("#totales").val(data.total);
									}
								});
							}
						});
					}
				});

				$('#modal_articulo').on('show.bs.modal', function (event) {
					var button = $(event.relatedTarget);
					var recipient = button.data('whatever');
					var modal = $(this);
					modal.find('#cod_movimiento').val(recipient);
				});

				$("#cantidad").keyup(function (){
					this.value = (this.value + '').replace(/[^0-9]/g, '');
				});

				$("#cantidad").change(function(){
		    	var cod_articulo = $("#cod_articulo_modal").val();
		    	var cantidad = $("#cantidad").val();
		    	var cod_movimiento = $("#cod_movimiento").val();
		    	var dscto = $("#dscto").val();
		    	var precio = 0;
		    	var resultado = 0;
		    	
		    	if(cantidad > 0){
		    		$("#update_art").prop('disabled', false);
		    		$.ajax({
							type: "POST",
							url: "../class/caja_funciones.php",
							data: "funcion=precio_articulo&cant="+cantidad+"&cod_mov="+cod_movimiento+"&dscto="+dscto,
							dataType: "json",
							error: function(){ alert("Error en la peticion AJAX - Precio articulo"); },
							success: function(data){
								$("#subtotal").val(data.subt);
								$("#dscto").val(data.dscto);
								$('#btn_registrar').attr("disabled", true);	

								// precio = data.precio;

								// if(cantidad > data.cantidad && data.cantidad > 0){
								// 	cantidad = data.cantidad;
								// 	$("#cantidad").val(cantidad);
								// }

								// resultado = precio * cantidad;
								// $("#subtotal").val(resultado);
							}
						});
			    }else{
			    	$("#update_art").prop('disabled', true);
			    }
		    });

		    $("#tipo_pago").change(function(event){
    			var tipo_pago = $("#tipo_pago").find(':selected').val();

    			if(tipo_pago == 2 || tipo_pago == 6){
    				$('#div_transaccion').show();
    			}else{
    				$('#div_transaccion').hide();
    			}
    		});

		    $("#dscto").keyup(function (){
		    	this.value = (this.value + '').replace(/[^0-9.]/g, '');    	
		    });

		    $("#dscto").change(function(){
		    	var cod_movimiento = $("#cod_movimiento").val();
					var cant = $("#cantidad").val();
					var dscto = $("#dscto").val();
		    	$.ajax({
		    		type: "POST", 
		    		url: "../class/caja_funciones.php",
		    		data: "funcion=precio_articulo&cant="+cant+"&cod_mov="+cod_movimiento+"&dscto="+dscto,
		    		dataType: "json",
		    		error: function(){ alert("Error en la peticion AJAX - Precio articulo"); },
		    		success: function(data){
		    			$("#subtotal").val(data.subt);
		    			$("#dscto").val(data.dscto);
		    			$('#btn_registrar').attr("disabled", true);														
		    		}
		    	});
		    });

		    $("#precio").keyup(function (){
		    	this.value = (this.value + '').replace(/[^0-9.]/g, '');    	
		    });

		    $("#precio").change(function(){
		    	var precio = $("#precio").val();
		    	var dscto = $("#dscto").val();
		    	if(precio >= dscto){
		    		var resultado = precio - dscto;
						$("#subtotal").val(resultado);
					}else{
						$("#subtotal").val("0");
						$("#precio").val(dscto);
					}
		    });

		    $('#update_art').click(function() {
		    	var cantidad = $("#cantidad").val();
		    	var cod_movimiento = $("#cod_movimiento").val();
		    	var precio = $("#precio").val();
		    	var dscto = $("#dscto").val();
		    	var subtotal = $("#subtotal").val();
		    	var cod_usuario = <?php echo $cod_usuario; ?>;
		    	
			    $.ajax({
						type: "POST",
						url: "../class/caja_funciones.php",
						data: "funcion=update_articulo&cant="+cantidad+"&cod_mov="+cod_movimiento+"&precio="+precio+"&dscto="+dscto+"&subtotal="+subtotal,
						dataType: "html",
						error: function(){ alert("Error en la peticion AJAX - Update articulo"); },
						success: function(data){
							$('#modal_articulo').modal('hide');

							$.ajax({
								type: "POST",
								url: "../class/caja_funciones.php",
								data: "funcion=generar_tabla&cod_usu="+cod_usuario,
								dataType: "json",
								beforeSend: function(){
									// $("#tabla_detalle").html("<div class='text-center'><img width='10%' src='img/load.gif'/></div>");
								},
								error: function(){ alert("Error en la peticion AJAX - Generar Tabla"); },
								success: function(data){
									acuenta_activo = data.acuenta_activo;
									$("#tabla_detalle").empty();
									$("#tabla_detalle").html(data.tabla);
									$("#totales").val(data.total);
								}
							});
						}
					});
		    });

		    // CONTAR LAS MATERIA REGISTRADAS EN EL PERIODO
		    $("#periodo").change(function(event){
    			var periodo = $("#periodo").find(':selected').val();
    			var cod_est = $("#cod_estudiante").val();
    			if(periodo !== "" && periodo != undefined){
	    			$.ajax({
	    				type: "POST",
	    				url: "../class/caja_funciones.php",
	    				data: "funcion=contar_materias&periodo="+periodo+"&cod_est="+cod_est,
	    				dataType: "html",
	    				error: function(){ alert("Error en la peticion AJAX - Contar Materias"); },
	    				success: function(data){
	    					$("#div_cantidad_materia").empty();
	    					$("#div_cantidad_materia").html(data);
	    				}
	    			});
	    		}
    		});

		    // VERIFICAR MATERIA
		    $('#verificar_materia').click(function() {
		    	var periodo = $("#periodo").val();
		    	var grupo = $("#grupo").val();
		    	var modalidad = $("#modalidad").val();
		    	var cod_usuario = "<?php echo $cod_usuario; ?>";
					var cod_est = $("#cod_estudiante").val();

		    	if(periodo != "" && grupo != "" && modalidad != ""){
		    		$.ajax({
							type: "POST",
							url: "../class/caja_funciones.php",
							data: "funcion=verificar_materia&periodo="+periodo+"&grupo="+grupo+"&modalidad="+modalidad+"&cod_est="+cod_est+"&cod_usu="+cod_usuario,
							dataType: "json",
							error: function(){ alert("Error en la peticion AJAX - Verificar Materia"); },
							success: function(data){
								if(data.resultado == 1 || data.resultado == "1"){
									alertify.notify('<p class="text-white">'+data.mensaje+'</p>', 'success', 7).dismissOthers();
									$.ajax({
										type: "POST",
										url: "../class/caja_funciones.php",
										data: "funcion=generar_tabla&cod_usu="+cod_usuario,
										dataType: "json",
										error: function(){ alert("Error en la peticion AJAX - Generar Tabla"); },
										success: function(data){
											acuenta_activo = data.acuenta_activo;
											$("#tabla_detalle").empty();
											$("#tabla_detalle").html(data.tabla);
											$("#totales").val(data.total);
										}
									});
								}else{
									alertify.notify('<i class="ion-alert-circled"></i> '+data.mensaje,'error', 7).dismissOthers();
								}
							}
						});
		    	}else{
		    		alertify.notify('<i class="fa fa-warning"></i> SELECCIONE TODOS LOS CAMPOS','error', 7).dismissOthers();
		    	}
		    });
		    ///////////////////

				$("#efectivo").keyup(function (){
					this.value = (this.value + '').replace(/[^0-9.]/g, '');
				});

				$('#tabla_detalle').on('keyup', '#acuenta', function () {
					this.value = (this.value + '').replace(/[^0-9.]/g, '');
				});

				$('#tabla_detalle').on('click', '#precio_editable', function () {
					var acuenta = $("#acuenta").val();
					var cod_usuario = "<?php echo $cod_usuario; ?>";

					if (acuenta > 0 && acuenta != "") {
						var cod_mov = $("#cod_nro_mov").val();

						$.ajax({
							type: "POST",
							url: "../class/caja_funciones.php",
							data: "funcion=precio_editable&acuenta="+acuenta+"&cod_mov="+cod_mov,
							dataType: "html",
							error: function(){ alert("Error en la peticion AJAX - Precio Editable"); },
							success: function(data){
								$.ajax({
									type: "POST",
									url: "../class/caja_funciones.php",
									data: "funcion=generar_tabla&cod_usu="+cod_usuario,
									dataType: "json",
									beforeSend: function(){
										// $("#tabla_detalle").html("<div class='text-center'><img width='10%' src='img/load.gif'/></div>");
									},
									error: function(){ alert("Error en la peticion AJAX - Generar Tabla"); },
									success: function(data){
										acuenta_activo = data.acuenta_activo;
										$("#tabla_detalle").empty();
										$("#tabla_detalle").html(data.tabla);
										$("#totales").val(data.total);
									}
								});
							}
						});
					}
				});

				// EFECTIVO
				$('#efectivo').keyup(function (){
					var total = $("#totales").val();

					var efectivo=$('#efectivo').val();
					var devuelto = parseFloat(efectivo) - parseFloat(total);
					devuelto = devuelto.toFixed(2);
					if (devuelto >= 0){
						$('#devolucion').val(devuelto);
						$('#efectivo').css({ 'color': '#2E3133'});
						if(acuenta_activo == 0)
							$('#btn_registrar').attr("disabled", false);
					}else{
						$('#devolucion').val('0');
						$('#efectivo').css({ 'color': '#ff0000' });
						$('#btn_registrar').attr("disabled", true);
					}
				});

				// PROCESO PARA REGISTRAR LA TRANSACCION //
				$('#btn_registrar').click(function() {
					var tipo_pago = $("#tipo_pago").val();
					var observacion = $("#observacion").val();
					if(tipo_pago != ""){
						var nro_tran = 0;
						if(tipo_pago == 2 || tipo_pago == 6){
							nro_tran = $("#nro_transaccion").val();
							if($("#nro_transaccion").val() == ""){
								alertify.notify('<i class="fa fa-warning"></i> COMPLETE EL CAMPO (NRO DE TRANSACCIÓN)','error',7).dismissOthers();
							}
						}
						if(nro_tran !== ""){
							var cod_est = $("#cod_estudiante").val();
							var cod_usu = <?php echo $cod_usuario; ?>;

							var total = $("#totales").val();
							var efectivo = $("#efectivo").val();
							var devolucion = $("#devolucion").val();

							if(efectivo == "")
								efectivo = 0;

							window.top.location.href = 'caja_guardar.php?cod_est='+cod_est+'&observacion='+observacion+'&cod_usu='+cod_usu+'&tipo_pago='+tipo_pago+
									'&total='+total+'&efectivo='+efectivo+'&devolucion='+devolucion+'&nro_tran='+nro_tran;
						}
					}else{
						alertify.notify('<i class="fa fa-warning"></i> FORMA DE PAGO NO SELECCIONADA','error',7).dismissOthers();
					}
				});

				$('#factura-16').on('change', function() {
					if ($(this).is(':checked')) {
						var factura = 1;
					} else {
						var factura = 0;
					}
					var cod_usuario = "<?php echo $cod_usuario; ?>";
					$.ajax({
						type: "POST",
						url: "../class/caja_funciones.php",
						data: "funcion=factura&fac="+factura+"&cod_usu="+cod_usuario,
						dataType: "html",
						error: function(){ alert("Error en la peticion AJAX - Factura"); },
						success: function(data){
							$.ajax({
								type: "POST",
								url: "../class/caja_funciones.php",
								data: "funcion=generar_tabla&cod_usu="+cod_usuario,
								dataType: "json",
								beforeSend: function(){
									// $("#tabla_detalle").html("<div class='text-center'><img width='10%' src='img/load.gif'/></div>");
								},
								error: function(){ alert("Error en la peticion AJAX - Generar Tabla"); },
								success: function(data){
									acuenta_activo = data.acuenta_activo;
									$("#tabla_detalle").empty();
									$("#tabla_detalle").html(data.tabla);
									$("#totales").val(data.total);
								}
							});
						}
					});
				});

			});

			function obtner_datos(cod_est){
				document.getElementById('resultado_est').innerHTML = "";
				$('#cod_estudiante').val(cod_est).trigger('change');
			}

			function update_articulo(nombre_art, cod_articulo, cantidad, precio, dscto, subtotal){
				document.getElementById("nombre_art").value = nombre_art;
				document.getElementById("cod_articulo_modal").value = cod_articulo;
				document.getElementById("cantidad").value = cantidad;
				document.getElementById("precio").value = precio;
				document.getElementById("dscto").value = dscto;
				document.getElementById("subtotal").value = subtotal;
				// if (precio > 0) {
				// 	$("#precio").prop('disabled', true);
				// 	$("#dscto").prop('disabled', true);
				// 	$("#subtotal").prop('disabled', true);
				// }
			}

			function delete_articulo(cod_mov){
				var cod_usuario = <?php echo $cod_usuario; ?>;
				limpiar();
				$.ajax({
					type: "POST",
					url: "../class/caja_funciones.php",
					data: "funcion=delete_articulo&cod_mov="+cod_mov,
					dataType: "html",
					error: function(){ alert("Error en la peticion AJAX - Eliminar articulo"); },
					success: function(data){
						$.ajax({
							type: "POST",
							url: "../class/caja_funciones.php",
							data: "funcion=generar_tabla&cod_usu="+cod_usuario,
							dataType: "json",
							beforeSend: function(){
								// $("#tabla_detalle").html("<div class='text-center'><img width='10%' src='img/load.gif'/></div>");
							},
							error: function(){ alert("Error en la peticion AJAX - Generar Tabla"); },
							success: function(data){
								acuenta_activo = data.acuenta_activo;
								$("#tabla_detalle").empty();
								$("#tabla_detalle").html(data.tabla);
								$("#totales").val(data.total);
							}
						});
					}
				});
			}

			function limpiar(){
				$("#efectivo").val('');
				$("#devolucion").val('');
				$('#btn_registrar').attr("disabled", true);
			}

			function calcular(acuenta_activo){
				var total = $("#totales").val();
				if(total == "0.00"){
					$('#devolucion').val(devuelto);
					$('#efectivo').css({ 'color': '#2E3133'});
					$('#btn_registrar').attr("disabled", false);
					$('#efectivo').attr("disabled", true);
				}
				if(total == 0 || total == ""){
				}else {
					$('#efectivo').attr("disabled", false);

					var efectivo=$('#efectivo').val();
					if(efectivo == "")
						efectivo = 0;

					var devuelto = parseFloat(efectivo) - total;
					devuelto = devuelto.toFixed(2);
					if (devuelto >= 0)
					{
						if(efectivo == "" || efectivo == 0)
							document.getElementById("efectivo").placeholder = "0";

						$('#devolucion').val(devuelto);
						$('#efectivo').css({ 'color': '#2E3133'});
						if(acuenta_activo == 0)
							$('#btn_registrar').attr("disabled", false);
					}
					else
					{
						plac_efe = devuelto * (-1);
						if(efectivo == "" || efectivo == 0)
							document.getElementById("efectivo").placeholder = plac_efe;

						$('#devolucion').val('0');
						$('#efectivo').css({ 'color': '#ff0000' });
						$('#btn_registrar').attr("disabled", true);
					}
				}
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