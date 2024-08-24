<?php
include ('../conf/funciones.php');
include("../conf/mysql.php");
$cod_usuario = 0;
if (verificar_usuario()){
	$cod_usuario = $_SESSION['cod_usuario'];
	$nombre_pagina = "ingresos_caja.php";
	// Verificar el privilegio de la pagina
	$sql_pagina = mysqli_query($con, "SELECT cod_privilegio FROM tbl_submenu, tbl_privilegio, tbl_usuario 
		WHERE cod_submenu = cod_submenu_priv AND cod_perfil_priv = cod_perfil_us AND estado_priv = 1 
		AND cod_usuario = $cod_usuario AND enlace_subm = '$nombre_pagina'");
	if(mysqli_num_rows($sql_pagina) > 0){
		date_default_timezone_set('America/La_Pa	z');
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
								$cod_estudiante = "";
								$fecha_venta = date("Y-m-d H:i a");
								$nombre_suc = "";
								$ci_estudiante =""; $nombre_est = ""; $celular = "";
								$sigla_plan = ""; $descripcion_plan = ""; $nombre_tur = "";
								$sigla_car = ""; $nombre_car = "";
								$det_estado = "";
								$det_usuario = "";
								$det_asesor = "";
								$tipo_pago = "";
								$info_suc = ""; $imagen_suc = "";
								$cod_sucursal_est = ""; $cod_carrera = "";
								$observacion = "";
								$det_nro_tran = "";
								$devolucion = "0.00";
								if(isset($_GET['cod'])){
									$cod_venta = $_GET['cod'];

									$sql_venta = mysqli_query($con, "SELECT cod_estudiante, cod_venta, fecha_venta, nombre_suc, nombre_per, apellido_per, carnet_per, estado_venta, nombre_tipopago, direccion_suc, telefono_suc, 
										imagen_suc, sigla_car, nombre_car, sigla_plan, descripcion_plan, cod_sucursal, cod_carrera, cod_tipopago_venta, nro_transaccion_venta, celular_per, observacion_est, nombre_tur 
										FROM tbl_venta, tbl_sucursal, tbl_estudiante, tbl_persona, tbl_tipo_pago, tbl_carrera, tbl_plan, tbl_turno 
										WHERE cod_venta = $cod_venta AND cod_sucursal_venta = cod_sucursal AND cod_estudiante_venta = cod_estudiante AND cod_tipopago_venta = cod_tipopago 
										AND cod_carrera_est = cod_carrera AND cod_plan_est = cod_plan AND cod_turno_est = cod_turno AND cod_persona_est = cod_persona");
									while ($row_v = mysqli_fetch_array($sql_venta)) {
										$cod_estudiante = $row_v['cod_estudiante'];
										$cod_sucursal_est = $row_v['cod_sucursal'];
										$cod_carrera = $row_v['cod_carrera'];
										$fecha_venta = $row_v['fecha_venta'];
										$nombre_suc = $row_v['nombre_suc'];
										$ci_estudiante = $row_v['carnet_per'];
										$nombre_est = $row_v['nombre_per']." - ".$row_v['apellido_per'];
										$celular = $row_v['celular_per'];
										$sigla_plan = $row_v['sigla_plan'];
										$descripcion_plan = $row_v['descripcion_plan'];
										$nombre_tur = $row_v['nombre_tur'];
										$sigla_car = $row_v['sigla_car'];
										$nombre_car = $row_v['nombre_car'];
										$observacion = $row_v['observacion_est'];
										$tipo_pago = $row_v['nombre_tipopago'];
										if($row_v['cod_tipopago_venta'] == 2 || $row_v['cod_tipopago_venta'] == 6)
											$det_nro_tran = " - (".$row_v['nro_transaccion_venta'].")";

										$info_suc = "Direccion: ".$row_v['direccion_suc']." -- Contacto: ".$row_v['telefono_suc']." -- Pagina Web: www.itesem.edu.bo";
										$imagen_suc = $row_v['imagen_suc'];

										//CAMBIAR LA IMAGEN 
										if($cod_sucursal_est == 3 && ($cod_carrera == 3 || $cod_carrera == 4 || $cod_carrera == 5 || $cod_carrera == 1 || $cod_carrera == 23 || $cod_carrera == 2 || $cod_carrera == 24)){
											$sql_suc_img = mysqli_query($con, "SELECT imagen_suc FROM tbl_sucursal WHERE cod_sucursal = 1");
											while ($row_is = mysqli_fetch_array($sql_suc_img)) {
												$imagen_suc = $row_is['imagen_suc'];
											}
										}

										$det_estado = "ACTIVO";
										if($row_v['estado_venta'] == 0)
											$det_estado = "ANULADO";

										// Obtener el codigo de la tabla
										$cod_tabla = 0;
										$sql_tabla = mysqli_query($con, "SELECT cod_tabla FROM tbl_tabla WHERE nombre_tabla = 'tbl_venta'");
										while ($row_ta = mysqli_fetch_array($sql_tabla)) {
											$cod_tabla = $row_ta['cod_tabla'];
										}

										// OBTENER EL USUARIO
										$sql_us = mysqli_query($con, "SELECT nombre_per, apellido_per FROM tbl_log, tbl_usuario, tbl_persona WHERE cod_tabla_log = $cod_tabla AND codigo_log = $cod_venta AND cod_tipolog_log = 1 
											AND cod_usuario_log = cod_usuario AND cod_persona_us = cod_persona");
										if(mysqli_num_rows($sql_us) > 0){
											while ($row_us = mysqli_fetch_array($sql_us)) {
												$det_usuario = $row_us['nombre_per']." ".$row_us['apellido_per'];
											}
										}

										// ASESOR
										$sql_asesor = mysqli_query($con, "SELECT nombre_per, apellido_per FROM tbl_log, tbl_usuario, tbl_persona WHERE cod_usuario_log = cod_usuario AND cod_persona_us = cod_persona 
											AND codigo_log = $cod_estudiante AND cod_tabla_log = 1 AND cod_tipolog_log = 1");
										if(mysqli_num_rows($sql_asesor) > 0){
											while ($row_as = mysqli_fetch_array($sql_asesor)) {
												$det_asesor = $row_as['nombre_per']." ".$row_as['apellido_per'];
											}
										}

									}
								}

								$fecha_rec = date_format(date_create($fecha_venta), "d-m-Y H:i a");
								// $date = new DateTime($fecha_venta, new DateTimeZone('Europe/Madrid'));
								// $date->format('Y-m-d H:i:sP');

								// $date->setTimezone(new DateTimeZone('America/La_Paz')); 
								// $fecha_rec =  $date->format('d-m-Y H:i a');
								?>
								<h5><a href="ingresos_caja.php"><i class="ion-arrow-left-c"></i>Volver Atras</a></h5>
								<div class="row">
									<div class="col-sm-8">
										<h4>Ver ingreso caja</h4>
									</div>
									<div class="col-sm-4 text-right">
										<b>Fecha Transaccion:</b> <?php echo $fecha_rec; ?>
									</div>
								</div>
								<hr>
								<div class="row bg-grey-300">
									<div class="col-sm-12">
										<div class="row">
											<div class="col-sm-3">
												<label class="col-form-label text-bold text-primary">NRO RECIBO</label>
												<input class="form-control" type="text" placeholder="...Nro Recibo" value="<?php echo $cod_venta; ?>" disabled>
											</div>
											<div class="col-sm-4"></div>
											<div class="col-sm-5">
												<label class="col-form-label text-bold text-primary">SUCURSAL</label>
												<input class="form-control" type="text" placeholder="...Sucursal" value="<?php echo $nombre_suc; ?>" disabled>
											</div>
										</div>

										<div class="row">
											<div class="col-sm-6">
												<label class="col-form-label text-bold text-primary">ESTUDIANTE</label>
												<input class="form-control" type="text" placeholder="...Estudiante" value="<?php echo $nombre_est." - ".$ci_estudiante." / ".$sigla_car; ?>" disabled>
											</div>
											<div class="col-sm-2">
												<label class="col-form-label text-bold text-primary">ESTADO</label>
												<input class="form-control" type="text" placeholder="...Estudiante" value="<?php echo $det_estado; ?>" disabled>
											</div>
											<div class="col-sm-4">
												<label class="col-form-label text-bold text-primary">USUARIO</label>
												<input class="form-control" type="text" placeholder="...Usuario" value="<?php echo $det_usuario; ?>" disabled>
											</div>
										</div>
										<br>
									</div>
								</div>
								<hr>
								<div class="no-more-tables">
									<table class="table table-striped table-bordered">
										<thead>
											<tr align="center" class="bg-primary">
												<th>ARTICULO</th>
												<th>PRECIO UNITARIO Bs.</th>
												<th>CANTIDAD</th>
												<th>DESCUENTO Bs.</th>
												<th>TOTAL Bs.</th>
											</tr>
										</thead>
										<tbody>
											<?php
											$sql_detalle = mysqli_query($con, "SELECT nombre_art, descripcion_detven, cod_articulo, cantidad_detven, precio_detven, dscto_detven, monto_bs_detven FROM tbl_detalle_venta, tbl_articulo WHERE cod_articulo_detven = cod_articulo AND cod_venta_detven = $cod_venta");
											if(mysqli_num_rows($sql_detalle) > 0){
												$valor_item = 1; $total_precio_detalle = 0; $total_dscto_detalle = 0; $total_monto_detalle = 0;
												while($row = mysqli_fetch_array($sql_detalle)){ 
													$total_precio_detalle = $total_precio_detalle + $row['precio_detven'];
													$total_dscto_detalle = $total_dscto_detalle + $row['dscto_detven'];
													$total_monto_detalle = $total_monto_detalle + $row['monto_bs_detven'];
													?>
													<tr class="active">
														<td><?php echo $row['nombre_art'].$row['descripcion_detven']; ?></td>
														<td><span class="fw-semi-bold"><?php echo $row['precio_detven']; ?></span></td>
														<td><?php echo $row['cantidad_detven']; ?></td>
														<td><?php echo $row['dscto_detven']; ?></td>
														<td><span class="fw-semi-bold"><?php echo $row['monto_bs_detven']; ?></span></td>
													</tr>
													<?php
												}
												mysqli_free_result($sql_detalle);
											}else{
												?>
												<tr>
													<td colspan="5" align="center">Registros no encontrados</td>
												</tr>
												<?php
											}
											?>
										</tbody>
										<thead>
											<tr class="bg-primary">
												<th colspan="3">TOTAL</th>
												<th align="center"><?php echo number_format(($total_dscto_detalle), 2, '.', ''); ?></th>
												<th align="center"><?php echo number_format(($total_monto_detalle), 2, '.', ''); ?></th>
											</tr>
										</thead>
									</table>
								</div>
								
								<div class="row">
									<div class="col-sm-2 text-center">
										<button id ="registrard4" class="btn btn-success width-80 mb-xs" role="button">Imprimir Recibo</button>
									</div>
									<div class="col-sm-2 text-center">
										<button id ="registrard4" class="btn btn-warning width-80 mb-xs" role="button" disabled>Imprimir Factura</button>
									</div>
									<div class="col-sm-2"></div>
									<div class="col-sm-6">
										<h4><u class="text-bold text-primary"><?php echo $tipo_pago; ?></u> : <?php echo number_format(($total_monto_detalle), 2, '.', ''); ?> Bs.</h4>
									</div>
								</div>

								<?php
								// Codificar imagen del logo de la empresa
								$image='img/'.$imagen_suc;
								$imageData = base64_encode(file_get_contents($image));
								$logo_fact = 'data:'.mime_content_type($image).';base64,'.$imageData;

								// TABLA RECIBO
								$sql_det_recibo = mysqli_query($con, "SELECT nombre_art, descripcion_detven, cantidad_detven, precio_detven, dscto_detven, monto_bs_detven FROM tbl_detalle_venta, tbl_articulo WHERE cod_articulo_detven = cod_articulo AND cod_venta_detven = $cod_venta");

								?>
								<table id="datatable-table-pdf" class="pdftable22" style="display:none;">
									<thead>
										<tr>
											<th class="text-left"><font color="white">N</font></th>
											<th class="text-left"><font color="white">ITEM</font></th>
											<th class="text-left"><font color="white">P/U (Bs.)</font></th>
											<th class="text-left"><font color="white">CANT.</font></th>
											<th class="text-left"><font color="white">DSCTO (Bs.)</font></th>
											<th class="text-left"><font color="white">TOTAL (Bs.)</font></th>
										</tr>
									</thead>
									<tbody>
										<?php
										$valor_item = 1; $total_precio_detalle = 0; $total_dscto_detalle = 0; $total_monto_detalle = 0;
										while($row = mysqli_fetch_array($sql_det_recibo)){ 
											$total_precio_detalle = $total_precio_detalle + $row['precio_detven'];
											$total_dscto_detalle = $total_dscto_detalle + $row['dscto_detven'];
											$total_monto_detalle = $total_monto_detalle + $row['monto_bs_detven'];
											?>
											<tr class="active">
												<td><?php echo $valor_item++; ?></td>
												<td><?php echo $row['nombre_art'].$row['descripcion_detven']; ?></td>
												<td><span class="fw-semi-bold"><?php echo $row['precio_detven']; ?></span></td>
												<td><?php echo $row['cantidad_detven']; ?></td>
												<td><?php echo $row['dscto_detven']; ?></td>
												<td><span class="fw-semi-bold"><?php echo $row['monto_bs_detven']; ?></span></td>
											</tr>
											<?php
										}
										mysqli_free_result($sql_det_recibo);
										?>
									</tbody>
								</table>

								<?php
								$anho = date('Y');
								$detalle_materias = "";
								// Obtener las materias registradas en la gestion
								$sql_historico = mysqli_query($con, "SELECT cod_hiStorico, nombre_mat, nombre_tur, nombre_gru FROM tbl_historico, tbl_oferta_materia, tbl_materia, tbl_periodo, tbl_gestion, tbl_turno, tbl_grupo 
									WHERE cod_oferta_materia_his = cod_oferta_materia AND cod_materia_of = cod_materia AND cod_periodo_of = cod_periodo AND cod_gestion_peri = cod_gestion 
									AND cod_turno_of = cod_turno AND cod_grupo_of = cod_grupo AND estado_his = 1 AND nombre_gest = '$anho' AND cod_estudiante_his = $cod_estudiante");
								if (mysqli_num_rows($sql_historico) > 0) {
									$detalle_materias = "MATERIAS REGISTRADAS EN LA GESTION (".$anho.")<br>";
									while ($row_h = mysqli_fetch_array($sql_historico)) {
										$detalle_materias = $detalle_materias."- ".ucwords(mb_strtolower($row_h['nombre_mat'], "UTF-8"))." - (".$row_h["nombre_tur"]."/".$row_h["nombre_gru"].")<br>";
									}
								}


								?>
								<table id="table_requisito" class="pdftable23" style="display:none;">
									<thead>
										<tr>
											<?php
											// REQUISITOS
											$sql_requisito = mysqli_query($con, "SELECT cod_requisito_inscripcion, nombre_reqins FROM tbl_requisito_inscripcion, tbl_nivel, tbl_carrera 
												WHERE cod_nivel_reqins = cod_nivel AND cod_nivel_car = cod_nivel AND cod_carrera = $cod_carrera");
											while ($row_req = mysqli_fetch_array($sql_requisito)) {
												?>
												<th class="text-center"><font color="white"><?php echo $row_req['nombre_reqins']; ?></font></th>
												<?php
											}
											?>
										</tr>
									</thead>
									<tbody>
										<tr>
											<?php
											// REQUISITOS
											$cod_requisito = 0;
											$sql_requisito = mysqli_query($con, "SELECT cod_requisito_inscripcion, nombre_reqins FROM tbl_requisito_inscripcion, tbl_nivel, tbl_carrera 
												WHERE cod_nivel_reqins = cod_nivel AND cod_nivel_car = cod_nivel AND cod_carrera = $cod_carrera");
											while ($row_req = mysqli_fetch_array($sql_requisito)) {
												$cod_requisito = $row_req['cod_requisito_inscripcion'];
												// COMRPOBAR SI TIENE EL REQUISITO
												$sql_estreq = mysqli_query($con, "SELECT cod_estudiante_requisito FROM tbl_estudiante_requisito 
													WHERE cod_estudiante_estreq = $cod_estudiante AND cod_requisito_estreq = $cod_requisito");
												$det_requisito = "FALTA";
												if(mysqli_num_rows($sql_estreq) > 0)
													$det_requisito = "ENTREGADO";
												?>
												<td><?php echo $det_requisito; ?></td>
												<?php
											}
											?>
										</tr>
									</tbody>
								</table>
							</div>
						</div>
					</div>
				</section>
			</main>
		</div>

		<div class="modal fade bd-example-modal-lg" id="modal_pdf" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
			<div class="modal-dialog modal-lg" role="document">
				<div class="modal-content">
					<div class="modal-body">
						<embed id="pdf_file" frameborder="0" width="100%" height="700px">
					</div>
				</div>
			</div>
		</div>

		<!-- End Search template-->
		<?php include('ajuste.php'); ?>
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
    <!-- App script-->
    <script src="js/app.js"></script>

    <script src="vendor/pdff/js_table/libre3/libs/jspdf.umd.js"></script>
		<script src="vendor/pdff/js_table/libre3/dist/jspdf.plugin.autotable.js"></script>

		<script type="text/javascript">
			$(document).ready(function(){
				// RECIBO CON EFECTIVO
				$("#registrard4").click(function(){
					var doc = new jspdf.jsPDF('p', 'pt', 'a4');
					var $tables2 = $(".pdftable22");
					var startingY = 190;
					var tbl_ingreso='';
					var img_logo = "<?php echo $logo_fact; ?>";
					
					var efectivo = "<?php echo $total_monto_detalle; ?>";
					var devuelto = "0.00";

					var nombre_tp = "<?php echo $tipo_pago.$det_nro_tran; ?>" ;
					if(efectivo > 0 || devuelto == '0.00'){
						$tables2.each(function( index ) {
							var res = doc.autoTableHtmlToJson(document.getElementById("datatable-table-pdf"));
							var offset =  2;
							startingY = doc.autoTableEndPosY() + offset;
							doc.autoTable(res.columns, res.data, {
								startY: 91, 
								pageBreak: 'avoid',
								theme: 'grid',
								styles: {
									overflow: 'linebreak',
									fontSize: 7,
									valign: 'middle',
								}
							});
						});
						doc.addImage(img_logo, 'PNG', 465, 5, 90, 23);
						doc.text("RECIBO  DE  CAJA", 200, 28);
						doc.setFontSize(6);
						doc.setTextColor(18, 32, 116);
						doc.text("R.M. 0137/2017 R.M. 0584/2021", 467, 35);
						doc.setTextColor(0, 0, 0);
						doc.setFontSize(7);
						doc.setDrawColor(0);
						doc.setFillColor(255,255,255);
						doc.rect(15, 40, 560, 48, 'FD'); // empty square
						doc.text("COD. / CI: <?php echo $ci_estudiante; ?>", 20, 50);
						doc.text("(CELULAR: <?php echo $celular; ?>)", 135, 50);
						doc.text("PLAN: <?php echo $sigla_plan; ?>", 300, 50);
						doc.text("(<?php echo $descripcion_plan; ?>)", 300, 60);
						doc.setFontSize(8);
						doc.text("Nro. Rec.: <?php echo $cod_venta; ?>", 375, 25);
						doc.setFontSize(7);
						doc.text("ESTUDIANTE: <?php echo $nombre_est; ?>", 20, 60);
						doc.text("CARRERA: <?php echo $nombre_car; ?>", 20, 70);
						doc.text("TURNO: <?php echo $nombre_tur; ?>", 400, 70);
						doc.text("OBSER.: <?php echo $observacion; ?>", 20, 80);

						doc.setFontSize(8);
						doc.text("TOTALES Bs: ", 25, doc.autoTableEndPosY() + 17);
						doc.text("DEVOLUCION: ", 110, doc.autoTableEndPosY() + 17);
						doc.text(nombre_tp, 195, doc.autoTableEndPosY() + 17);

						doc.setDrawColor(0);
						doc.setFillColor(255,255,255);

						doc.rect(25, doc.autoTableEndPosY() + 23, 75, 18, 'FD'); // empty square
						doc.rect(110, doc.autoTableEndPosY() + 23, 75, 18, 'FD'); // empty square
						doc.rect(195, doc.autoTableEndPosY() + 23, 75, 18, 'FD'); // empty square

						doc.text("<?php echo number_format((abs ($total_monto_detalle)), 2); ?>", 30, doc.autoTableEndPosY() + 35);
						doc.text("<?php echo number_format((abs ($devolucion)), 2); ?>", 115, doc.autoTableEndPosY() + 35);
						doc.text("<?php echo number_format((abs ($total_monto_detalle)), 2); ?>", 200, doc.autoTableEndPosY() + 35);

						doc.text("Fecha Transaccion : <?php echo $fecha_rec; ?> ", 25, doc.autoTableEndPosY() + 55);

						doc.setFontSize(7);
						var materias = "<?php echo $detalle_materias; ?>";
						materias = materias.replaceAll("<br>", "\n");
						doc.text(materias, 280, doc.autoTableEndPosY() + 30);
						doc.setFontSize(8);

						doc.text("Cajero : <?php echo $det_usuario; ?>", 25, doc.autoTableEndPosY() + 65);
						doc.text("Registrado por : <?php echo $det_asesor; ?>", 25, doc.autoTableEndPosY() + 75);

						doc.text("________________________________ ", 55, doc.autoTableEndPosY() + 130);
						doc.text("Firma y Sello del Cajero.", 86, doc.autoTableEndPosY() + 140);
						doc.text("________________________________ ", 410, doc.autoTableEndPosY() + 130);
						doc.text("Firma del Estudiante. ", 450, doc.autoTableEndPosY() + 140);
						doc.setFontSize(7);

						var $tables3 = $(".pdftable23");
						$tables3.each(function( index ) {
							var res = doc.autoTableHtmlToJson(document.getElementById("table_requisito"));
							var offset =  2;
							startingY = doc.autoTableEndPosY() + offset;
							doc.autoTable(res.columns, res.data, {
								startY: doc.autoTableEndPosY() + 145, 
								pageBreak: 'avoid',
								theme: 'grid',
								styles: {
									overflow: 'linebreak',
									fontSize: 5,
									valign: 'middle',
								}
							});
						});
						
						doc.text("NOTA: El presente recibo se hace efectivo al momento de realizar el pago, el cual no sera devuelto en caso de que el estudiante se retire.", 70, doc.autoTableEndPosY() + 10);
						doc.text("<?php echo $info_suc; ?>", 100, doc.autoTableEndPosY() + 20);

						document.getElementById('pdf_file').setAttribute('src', doc.output('bloburl'));
						$('#modal_pdf').modal('show');
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