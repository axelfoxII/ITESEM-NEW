<?php
include ('../conf/funciones.php');
include("../conf/mysql.php");
$cod_usuario = 0;
if (verificar_usuario()){
	$cod_usuario = $_SESSION['cod_usuario'];
	$nombre_usuario = "";
	$apellido_usuario = "";
	$nombre_pagina = "caja.php";
	// Verificar el privilegio de la pagina
	$sql_pagina = mysqli_query($con, "SELECT cod_privilegio FROM tbl_submenu, tbl_privilegio, tbl_usuario 
		WHERE cod_submenu = cod_submenu_priv AND cod_perfil_priv = cod_perfil_us AND estado_priv = 1 
		AND cod_usuario = $cod_usuario AND enlace_subm = '$nombre_pagina'");
	if(mysqli_num_rows($sql_pagina) > 0){
		$sql_usuario = mysqli_query($con, "SELECT nombre_per, apellido_per FROM tbl_usuario, tbl_persona 
			WHERE cod_persona_us = cod_persona AND cod_usuario = $cod_usuario");
		while ($row_u = mysqli_fetch_array($sql_usuario)) {
			$nombre_usuario = $row_u['nombre_per'];
			$apellido_usuario = $row_u['apellido_per'];
		}

		date_default_timezone_set('America/La_Paz');
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
								if(isset($_GET['cod_est']) && isset($_GET['cod_usu'])){
									$cod_estudiante = $_GET['cod_est'];
									$cod_usuario = $_GET['cod_usu'];
									$tipo_pago = $_GET['tipo_pago'];
									$total = $_GET['total'];
									$efectivo = $_GET['efectivo'];
									$devolucion = $_GET['devolucion'];
									$fecha2 = $_GET['fecha2'];

									// VERIFICAR SI HAY ARTICULOS EN EL DETALLE
									$sql_det = mysqli_query($con, "SELECT cod_nro_movimiento FROM tbl_detalle_movimiento WHERE cod_usuario_nmov = $cod_usuario");
									if (mysqli_num_rows($sql_det) > 0) {
										// OBTENER LOS DATOS LOS ESTUDIANTES
										$cod_persona = 0;
										$nombre_estudiante = "";
										$ci_estudiante = "";
										$cod_carrera = 0; $nombre_car = ""; $sigla_car = "";
										$cod_plan = 0; $sigla_plan = "";
										$precio_total_plan = 0;
										$sql_estudiante = mysqli_query($con, "SELECT cod_persona, nombre_per, apellido_per, carnet_per, cod_carrera_est, 
											cod_plan, sigla_plan, nombre_car, sigla_car, precio_total_plan, cod_sucursal, nombre_suc 
											FROM tbl_persona, tbl_estudiante, tbl_plan, tbl_carrera, tbl_sucursal 
											WHERE cod_persona_est = cod_persona AND cod_plan_est = cod_plan AND cod_carrera = cod_carrera_est 
											AND cod_sucursal = cod_sucursal_est AND cod_estudiante = $cod_estudiante");
										while ($row_est = mysqli_fetch_array($sql_estudiante)) {
											$cod_directorio = $row_est['cod_persona'];
											$nombre_estudiante = $row_est['nombre_per'].' - '.$row_est['apellido_per'];
											$ci_estudiante = $row_est['carnet_per'];
											$cod_carrera = $row_est['cod_carrera_est'];
											$cod_plan = $row_est['cod_plan'];
											$sigla_plan = $row_est['sigla_plan'];
											$nombre_car = $row_est['nombre_car'];
											$sigla_car = $row_est['sigla_car'];
											$precio_total_plan = $row_est['precio_total_plan'];
										}

										// TABLA RECIBO
										$sql_det_recibo = mysqli_query($con, "SELECT cod_nro_movimiento, nombre_art, descripcion_nmov, cod_articulo_nmov, cod_estudiante_nmov, 
											cantidad_nmov, precio_nmov, dscto_nmov, subtotal_nmov FROM tbl_detalle_movimiento, tbl_articulo 
											WHERE cod_articulo_nmov = cod_articulo AND cod_usuario_nmov = $cod_usuario AND cod_estudiante_nmov = $cod_estudiante 
											ORDER BY cod_nro_movimiento DESC");

										?>
										<table id="datatable-table-pdf" class="pdftable22" style="display:none;">
											<thead>
												<tr class="info">
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
													$cod_articulo = $row['cod_articulo_nmov'];
													$total_precio_detalle = $total_precio_detalle + $row['precio_nmov'];
													$total_dscto_detalle = $total_dscto_detalle + $row['dscto_nmov'];
													$total_monto_detalle = $total_monto_detalle + $row['subtotal_nmov'];
													?>
													<tr class="active">
														<td><?php echo $valor_item++; ?></td>
														<td><?php echo $row['nombre_art'].$row['descripcion_nmov']; ?></td>
														<td><span class="fw-semi-bold"><?php echo $row['precio_nmov']; ?></span></td>
														<td><?php echo $row['cantidad_nmov']; ?></td>
														<td><?php echo $row['dscto_nmov']; ?></td>
														<td><span class="fw-semi-bold"><?php echo $row['subtotal_nmov']; ?></span></td>
													</tr>
													<?php
												}
												mysqli_free_result($sql_det_recibo);
												?>
											</tbody>
										</table>
										<?php
										// FIN DE LA TABLA DEL RECIBO

										// INSERT EN LA TABLA tbl_venta
										$cod_venta = 0; $fecha_venta = "";
										if($efectivo > 0 && $efectivo != ""){
											$efectivo = $efectivo - $devolucion;
											$insert_venta = mysqli_query($con, "INSERT INTO tbl_venta (cod_estudiante_venta, 
													monto_total_venta, cod_tipopago_venta, cod_usuario_venta, fecha_venta) 
													VALUES ($cod_estudiante, $efectivo, $tipo_pago, $cod_usuario, '$fecha2')");

											if(mysqli_affected_rows($con) > 0){
												// Obtener el ultimo codigo de la tabla tbl_venta
												$sql_ult_venta = mysqli_query($con, "SELECT cod_venta, fecha_venta FROM tbl_venta 
													WHERE cod_usuario_venta = $cod_usuario ORDER BY cod_venta DESC LIMIT 0,1");
												while ($row_ult = mysqli_fetch_row($sql_ult_venta)) {
													$cod_venta = $row_ult[0];
													$fecha_venta = $row_ult[1];
												}
											} // if(tbl_venta)
										}

										// OBTNER EL DETALLE DE LA TABLA tbl_detalle_movimiento
										$cod_articulo_nmov = 0;
										$descripcion = "";
										$cantidad_nmov = 0;
										$precio_nmov = 0; $dscto_nmov = 0; 
										$subtotal_nmov = 0;
										$sql_det_mov = mysqli_query($con, "SELECT cod_articulo_nmov, descripcion_nmov, cantidad_nmov, precio_nmov, dscto_nmov, subtotal_nmov 
											FROM tbl_detalle_movimiento WHERE cod_estudiante_nmov = $cod_estudiante AND cod_usuario_nmov = $cod_usuario");
										while ($row_det = mysqli_fetch_array($sql_det_mov)) {
											$cod_articulo_nmov = $row_det['cod_articulo_nmov'];
											$descripcion = $row_det['descripcion_nmov'];
											$cantidad_nmov = $row_det['cantidad_nmov'];
											$precio_nmov = $row_det['precio_nmov'];
											$dscto_nmov = $row_det['dscto_nmov'];
											$subtotal_nmov = $row_det['subtotal_nmov'];

											// Verificar si es un articulo de inventario
											$sql_ver = mysqli_query($con, "SELECT cod_articulo FROM tbl_articulo WHERE cod_articulo = $cod_articulo_nmov AND cod_tipoarticulo_art = 1");
											if(mysqli_num_rows($sql_ver) > 0){
												// REGISTRO DE SALIDA DE INVENATRIO
												$observacion = "VENTA Nro. ".$cod_venta;
												$insert_inv = mysqli_query($con, "INSERT INTO tbl_inventario (cod_articulo_inv, cod_tipoinventario_inv, cantidad_inv, observacion_inv, cod_usuario_inv) 
													VALUES ($cod_articulo_nmov, 2, $cantidad_nmov, '$observacion', $cod_usuario)");
											}

											if($subtotal_nmov > 0 && $cod_venta > 0){
												//INSERT INTO EN LA TABLA tbl_detalle_venta
												$insert_detalle_venta = mysqli_query($con, "INSERT INTO tbl_detalle_venta (cod_venta_detven, cod_articulo_detven, descripcion_detven, 
													precio_detven, cantidad_detven, dscto_detven, monto_bs_detven) 
													VALUES ($cod_venta, $cod_articulo_nmov, '$descripcion', $precio_nmov, $cantidad_nmov, $dscto_nmov, $subtotal_nmov)");
											} // if $subtotal_nmov > 0 $cod_venta > 0

											// ARTICULO
											$nombre_art = "";
											$cod_tipoarticulo = 0;
											$precio_art = 0;
											$sql_articulo = mysqli_query($con, "SELECT nombre_art, cod_tipoarticulo_art, precio_art FROM tbl_articulo 
												WHERE cod_articulo = $cod_articulo_nmov");
											while ($row_art = mysqli_fetch_array($sql_articulo)) {
												$nombre_art = $row_art['nombre_art'];
												$cod_tipoarticulo = $row_art['cod_tipoarticulo_art'];
												$precio_art = $row_art['precio_art'];
											}

											if($cod_tipoarticulo != 3){
												$insert_cuenta_debe = mysqli_query($con, "INSERT INTO tbl_cuenta_estudiante (cod_estudiante_cuenta, cod_venta_cuenta, 
													precio_debe_cuenta, cod_articulo_cuenta, precio_haber_cuenta, cod_tipocuenta_cuenta, descripcion_cuenta, fecha_cuenta)
													VALUES ($cod_estudiante, $cod_venta, $subtotal_nmov, $cod_articulo_nmov, 0, 3, '$descripcion', '$fecha2')");
											}elseif(strpos($nombre_art, "PRECIO TOTAL AÑO") !== false){
												$insert_cuenta_debe = mysqli_query($con, "INSERT INTO tbl_cuenta_estudiante (cod_estudiante_cuenta, cod_venta_cuenta, 
													precio_debe_cuenta, cod_articulo_cuenta, precio_haber_cuenta, cod_tipocuenta_cuenta, descripcion_cuenta, fecha_cuenta)
													VALUES ($cod_estudiante, $cod_venta, $precio_nmov, $cod_articulo_nmov, 0, 4, '$descripcion', '$fecha2')");
											}
											
											if(strpos($nombre_art, "PRECIO TOTAL AÑO") === false){
												$insert_cuenta_haber = mysqli_query($con, "INSERT INTO tbl_cuenta_estudiante (cod_estudiante_cuenta, cod_venta_cuenta, 
													precio_debe_cuenta, cod_articulo_cuenta, precio_haber_cuenta, cod_tipocuenta_cuenta, descripcion_cuenta, fecha_cuenta)
													VALUES ($cod_estudiante, $cod_venta, 0, $cod_articulo_nmov, $subtotal_nmov, 2, '$descripcion', '$fecha2')");
											}

										} // while(tbl_detalle_movimiento)

										$sql_det_recibo_NC = mysqli_query($con, "SELECT cod_nro_movimiento, nombre_art,cod_articulo_nmov,cod_estudiante_nmov, 
											precio_nmov, dscto_nmov, subtotal_nmov FROM tbl_detalle_movimiento, tbl_articulo 
											WHERE cod_articulo_nmov = cod_articulo AND cod_usuario_nmov = $cod_usuario AND cod_estudiante_nmov = $cod_estudiante 
											ORDER BY cod_nro_movimiento DESC");
									?>

									<!-- TABLA PARA EL RECIBO DE UNA DEVOLUCION DE NOTA DE CREDITO -->
									<table id="datatable-table-pdf_NC" class="pdftable22_NC" style="display:none;">
										<thead>
											<tr class="info">
												<th class="text-left"><font color="white">N</font></th>
												<th class="text-left"><font color="white">ITEM</font></th>
												<th class="text-left"><font color="white">P/U (Bs.)</font></th>
											</tr>
										</thead>
										<tbody>
											<tr class="active">
												<td>1</td>
												<td>REGISTRO</td>
												<td width="40%" colspan="3"></td>
											</tr>
											<?php
											$valor_item=1; $int_total_monto_detalle_NC = 0; $total_new_NC = 0;
											while($row_NC = mysqli_fetch_array($sql_det_recibo_NC)){ ?>
												<tr class="active">
													<td><?php echo ++$valor_item; ?></td>
													<td><?php echo $row_NC['nombre_art']; ?></td>
													<td><span class="fw-semi-bold">
														<?php echo '- '.$row_NC['precio_nmov']; $int_total_monto_detalle_NC = $int_total_monto_detalle_NC + $row_NC['precio_nmov'];?>
													</span></td>
												</tr>
												<?php
											}
											$total_new_NC = $int_total_monto_detalle_NC;
											if($total_new_NC < 0)
												$total_new_NC = 0;
											?>
											<tr class="info">
												<td class="text-left"><font color="white"></font>  </td>
												<td class="text-left"><font color="white"><strong>TOTAL A CUENTA</strong></font> </td>
												<td class="text-left"><font color="white" size="3"><?php echo number_format(($total_new_NC), 2); ?></font> </td>
											</tr>
										</tbody>
									</table>
									<!-- FIN TABLA RECIBO - NOTA DE CREDITO -->

									<?php

										//ELIMINAR REGISTROS DEL DETALLE
										$delete_detalle_mov = mysqli_query($con, "DELETE FROM tbl_detalle_movimiento 
											WHERE cod_estudiante_nmov = $cod_estudiante AND cod_usuario_nmov = $cod_usuario");
										if(mysqli_affected_rows($con) > 0){
											// Codificar imagen del logo de la empresa
											$image='img/logo_itesem1.png';
											$imageData = base64_encode(file_get_contents($image));
											$logo_fact = 'data:'.mime_content_type($image).';base64,'.$imageData;
											?>
											<h3 align="center">Transacción realizada exitosamente</h3>
											</br>
											<center>
												<button id ="registrard4" class="btn btn-primary width-80 mb-xs" role="button">
													Imprimir Recibo Caja
												</button>
											</center>
											<?php
											$nro_recibo = '';
											$fecha_recibo = '';

											if($cod_venta != 0){
												$nro_recibo = $cod_venta;
												$fecha_recibo = date("d-m-Y H:i a", strtotime($fecha_venta));
											}elseif($cod_historico != 0){
												$nro_recibo = $cod_historico.'-H';
												$fecha_recibo = $fecha_his;
											}else{
												$cod_cuenta_ult = 0;
												$sql_cod_cuenta = mysqli_query($con, "SELECT cod_cuenta_estudiante FROM tbl_cuenta_estudiante 
													WHERE cod_estudiante_cuenta = $cod_estudiante ORDER BY cod_cuenta_estudiante DESC LIMIT 0, 1");
												while ($row_cod_cuenta = mysqli_fetch_array($sql_cod_cuenta)){
													$cod_cuenta_ult = $row_cod_cuenta['cod_cuenta_estudiante'].'-C';
												}

												$nro_recibo = $cod_cuenta_ult;
												$fecha_recibo = date("d-m-Y H:i a");
											}
										} // Fin delete detalle_movimiento

									} // IF (tbl_detalle_movimiento)
								} // IF ($_GET)
								?>
								<br>
								<div class="text-center">
									<a href="caja2.php">Registrar Nueva Transacción</a>
								</div>
							</div>
						</div>
					</div>
				</section>
			</main>
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
		<!-- Sparkline-->
		<script src="vendor/jquery-sparkline/jquery.sparkline.js"></script>
		<!-- jQuery Knob charts-->
		<script src="vendor/jquery-knob/js/jquery.knob.js"></script>
		<script src="vendor/pdff/js_table/libre/jspdf.min.js"></script>
		<script src="vendor/pdff/js_table/libre/jspdf.plugin.autotable.src.js"></script>
		<!-- App script-->
		<script src="js/app.js"></script>
		<script type="text/javascript">
			$(document).ready(function(){
				// RECIBO CON EFECTIVO
				$("#registrard4").click(function(){
					var doc = new jsPDF('p', 'pt', 'a4');
					var $tables2 = $(".pdftable22");
					var startingY = 190;
					var tbl_ingreso='';
					var img_logo = "<?php echo $logo_fact; ?>";
					
					var efectivo = "<?php echo $efectivo; ?>" ;
					var devuelto = "<?php echo $devolucion; ?>" ;

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
									fontSize: 8,
									valign: 'middle',
								},
								columnStyles: {
									0: {
										valign: "top",
										columnWidth: 20,
										halign: 'center',
									},
									1: {
										fontStyle: 'normal',
										halign: 'left',
									},
									2: {
										fontStyle: 'normal',
										halign: 'right',
									},
									3: {
										fontStyle: 'normal',
										halign: 'right',
									},
									4: {
										fontStyle: 'normal',
										halign: 'right',
									},
								}
							});
						});
						doc.addImage(img_logo, 'PNG', 40, 15, 100, 25);
						doc.text("RECIBO  DE  CAJA", 220, 34);
						doc.setFontSize(9);
						doc.setDrawColor(0);
						doc.setFillColor(255,255,255);
						doc.rect(40, 43, 515, 42, 'FD'); // empty square
						doc.text("CODIGO: <?php echo $ci_estudiante; ?>", 45, 60);
						doc.text("PLAN: <?php echo $sigla_plan; ?>", 340, 60);
						doc.text("Nro.: <?php echo $nro_recibo; ?>", 460, 60);
						doc.text("ESTUDIANTE: <?php echo $nombre_estudiante; ?>", 45, 75);
						doc.text("CARRERA: <?php echo $sigla_car; ?>", 340, 75);

						doc.text("TOTALES Bs: ", 40, doc.autoTableEndPosY() + 17);
						doc.text("EFECTIVO: ", 140, doc.autoTableEndPosY() + 17);
						doc.text("DEVOLUCION: ", 240, doc.autoTableEndPosY() + 17);

						doc.setDrawColor(0);
						doc.setFillColor(255,255,255);

						doc.rect(40, doc.autoTableEndPosY() + 23, 75, 18, 'FD'); // empty square
						doc.rect(140, doc.autoTableEndPosY() + 23, 75, 18, 'FD'); // empty square
						doc.rect(240, doc.autoTableEndPosY() + 23, 75, 18, 'FD'); // empty square

						doc.text("<?php echo number_format((abs ($total_monto_detalle)), 2); ?>", 45, doc.autoTableEndPosY() + 35);
						doc.text("<?php echo number_format((abs ($efectivo)), 2); ?>", 145, doc.autoTableEndPosY() + 35);
						doc.text("<?php echo number_format((abs ($devolucion)), 2); ?>", 245, doc.autoTableEndPosY() + 35);

						doc.text("Fecha Transaccion: <?php echo $fecha_recibo; ?> ", 40, doc.autoTableEndPosY() + 55);
						doc.text("Fecha Impresion    : <?php echo date("d-m-Y").' '.date("H:i a", $time = time());?>", 40, doc.autoTableEndPosY() + 68);
						doc.text("Cajero : <?php echo $nombre_usuario.' '.$apellido_usuario; ?>", 40, doc.autoTableEndPosY() + 81);

						doc.text("________________________________ ", 55, doc.autoTableEndPosY() + 140);
						doc.text("Firma y Sello del Cajero.", 86, doc.autoTableEndPosY() + 150);
						doc.text("________________________________ ", 380, doc.autoTableEndPosY() + 140);
						doc.text("Firma del Estudiante. ", 420, doc.autoTableEndPosY() + 150);
						doc.setFontSize(7);
						// doc.text("Av. Mutualista #3060 y 3er Anillo Externo - Santa Cruz - Estado Plurinacional de Bolivia.", 160, doc.autoTableEndPosY() + 170);

						document.getElementById('pdf_file').setAttribute('src', doc.output('bloburl'));
						$('#modal_pdf').modal('show');
					}
				});

			});
		</script>

		<div class="modal fade bd-example-modal-lg" id="modal_pdf" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
			<div class="modal-dialog modal-lg" role="document">
				<div class="modal-content">
					<div class="modal-body">
						<embed id="pdf_file" frameborder="0" width="100%" height="600px">
					</div>
				</div>
			</div>
		</div>

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