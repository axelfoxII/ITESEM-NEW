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
									$observacion = $_GET['observacion'];
									$tipo_pago = $_GET['tipo_pago'];
									$total = $_GET['total'];
									$efectivo = $_GET['efectivo'];
									$devolucion = $_GET['devolucion'];
									$nro_transaccion = $_GET['nro_tran'];
									$det_nro_tran = " - (".$nro_transaccion.")";
									$det_asesor = "";
									if($nro_transaccion == "0"){
										$nro_transaccion = "";
										$det_nro_tran = "";
									}
									$fecha_recibo = date("Y-m-d H:i a");

									// VERIFICAR SI HAY ARTICULOS EN EL DETALLE
									$sql_det = mysqli_query($con, "SELECT cod_nro_movimiento FROM tbl_detalle_movimiento WHERE cod_usuario_nmov = $cod_usuario");
									if (mysqli_num_rows($sql_det) > 0) {
										// OBTENER LOS DATOS LOS ESTUDIANTES
										$cod_persona = 0;
										$nombre_estudiante = "";
										$ci_estudiante = "";
										$cod_carrera = 0; $nombre_car = ""; $sigla_car = ""; $celular = "";
										$cod_plan = 0; $sigla_plan = ""; $descripcion_plan = ""; $nombre_tur = "";
										$precio_total_plan = 0;
										$cod_sucursal_est = 0;
										$info_suc = ""; $imagen_suc = "";
										$sql_estudiante = mysqli_query($con, "SELECT cod_persona, nombre_per, apellido_per, carnet_per, cod_carrera_est, 
											cod_plan, sigla_plan, descripcion_plan, nombre_car, sigla_car, precio_total_plan, cod_sucursal, nombre_suc, direccion_suc, telefono_suc, imagen_suc, celular_per, nombre_tur 
											FROM tbl_persona, tbl_estudiante, tbl_plan, tbl_carrera, tbl_sucursal, tbl_turno 
											WHERE cod_persona_est = cod_persona AND cod_plan_est = cod_plan AND cod_carrera = cod_carrera_est AND cod_turno_est = cod_turno 
											AND cod_sucursal = cod_sucursal_est AND cod_estudiante = $cod_estudiante");
										while ($row_est = mysqli_fetch_array($sql_estudiante)) {
											$cod_directorio = $row_est['cod_persona'];
											$nombre_estudiante = $row_est['nombre_per'].' - '.$row_est['apellido_per'];
											$ci_estudiante = $row_est['carnet_per'];
											$celular = $row_est['celular_per'];
											$cod_carrera = $row_est['cod_carrera_est'];
											$cod_plan = $row_est['cod_plan'];
											$sigla_plan = $row_est['sigla_plan'];
											$descripcion_plan = $row_est['descripcion_plan'];
											$nombre_tur = $row_est['nombre_tur'];
											$nombre_car = $row_est['nombre_car'];
											$sigla_car = $row_est['sigla_car'];
											$precio_total_plan = $row_est['precio_total_plan'];
											$cod_sucursal_est = $row_est['cod_sucursal'];
											$info_suc = "Direccion: ".$row_est['direccion_suc']." -- Contacto: ".$row_est['telefono_suc']." -- Pagina Web: www.itesem.edu.bo";
											$imagen_suc = $row_est['imagen_suc'];
										}

										//CAMBIAR LA IMAGEN 
										if($cod_sucursal_est == 3 && ($cod_carrera == 3 || $cod_carrera == 4 || $cod_carrera == 5 || $cod_carrera == 1 || $cod_carrera == 23 || $cod_carrera == 2 || $cod_carrera == 24)){
											$sql_suc_img = mysqli_query($con, "SELECT imagen_suc FROM tbl_sucursal WHERE cod_sucursal = 1");
											while ($row_is = mysqli_fetch_array($sql_suc_img)) {
												$imagen_suc = $row_is['imagen_suc'];
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

										// TIPO DE PAGO
										$nombre_tp = "";
										$sql_tipopago = mysqli_query($con, "SELECT nombre_tipopago FROM tbl_tipo_pago WHERE cod_tipopago = $tipo_pago");
										while ($row_tp = mysqli_fetch_array($sql_tipopago)) {
											$nombre_tp = $row_tp['nombre_tipopago'];
										}

										// UPDATE OBSERVACION
										$update_est = mysqli_query($con, "UPDATE tbl_estudiante SET observacion_est = '$observacion' WHERE cod_estudiante = $cod_estudiante");

										// TABLA RECIBO
										$sql_det_recibo = mysqli_query($con, "SELECT cod_nro_movimiento, nombre_art, descripcion_nmov, cod_articulo_nmov, cod_estudiante_nmov, 
											cantidad_nmov, precio_nmov, factura_nmov, dscto_nmov, subtotal_nmov, tipo_nmov FROM tbl_detalle_movimiento, tbl_articulo 
											WHERE cod_articulo_nmov = cod_articulo AND tipo_nmov != 2 AND cod_usuario_nmov = $cod_usuario AND cod_estudiante_nmov = $cod_estudiante 
											ORDER BY cod_nro_movimiento DESC");

										?>
										<table id="datatable-table-pdf" class="pdftable22" style="display:none;">
											<thead>
												<tr>
													<th class="text-left"><b>N</b></th>
													<th class="text-left"><b>ITEM</b></th>
													<th class="text-left"><b>P/U (Bs.)</b></th>
													<th class="text-left"><b>CANT.</b></th>
													<th class="text-left"><b>DSCTO (Bs.)</b></th>
													<th class="text-left"><b>TOTAL (Bs.)</b></th>
												</tr>
											</thead>
											<tbody>
												<?php
												$valor_item = 1; $total_precio_detalle = 0; $total_dscto_detalle = 0; $total_monto_detalle = 0;
												$nombre_art_nmov = "";
												while($row = mysqli_fetch_array($sql_det_recibo)){ 
													$cod_articulo = $row['cod_articulo_nmov'];
													$factura = "";

													if($row['tipo_nmov'] == 2 || $row['tipo_nmov'] == "2"){
														// OFERTAS DE MATERIAS
														$estructura = "";
									    			$sql_oferta = mysqli_query($con, "SELECT nombre_mat, sigla_mat, nombre_estmat FROM tbl_oferta_materia, tbl_materia, tbl_estructura_materia 
									    				WHERE cod_estructura_materia_mat = cod_estructura_materia AND cod_materia_of = cod_materia AND cod_oferta_materia = $cod_articulo");
									    			while ($row_of = mysqli_fetch_array($sql_oferta)) {
									    				$nombre_art_nmov = $row_of['nombre_mat']." - ".$row_of['sigla_mat'];
									    				$estructura = "MATERIA DE ".$row_of['nombre_estmat'];
									    			}
									    			?>
														<tr class="active">
															<td><?php echo $valor_item++; ?></td>
															<td><?php echo $nombre_art_nmov.$row['descripcion_nmov']; ?></td>
															<td colspan="4"><?php echo $estructura; ?></td>
														</tr>
														<?php
													}else{
														$nombre_art_nmov = $row['nombre_art'];
														$total_precio_detalle = $total_precio_detalle + $row['precio_nmov'];
														$total_dscto_detalle = $total_dscto_detalle + $row['dscto_nmov'];
														$total_monto_detalle = $total_monto_detalle + $row['subtotal_nmov'];
														if($row['factura_nmov'] != 0 && $row['factura_nmov'] != "0.00")
															$factura = " - (+".$row['factura_nmov'].")";
														?>
														<tr class="active">
															<td><?php echo $valor_item++; ?></td>
															<td><?php echo $nombre_art_nmov.$row['descripcion_nmov']; ?></td>
															<td><b><?php echo $row['precio_nmov'].$factura; ?></b></td>
															<td><?php echo $row['cantidad_nmov']; ?></td>
															<td><?php echo $row['dscto_nmov']; ?></td>
															<td><b><?php echo $row['subtotal_nmov']; ?></b></td>
														</tr>
														<?php
													}
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
											$insert_venta = mysqli_query($con, "INSERT INTO tbl_venta (cod_estudiante_venta, monto_total_venta, cod_tipopago_venta, nro_transaccion_venta, cod_usuario_venta, cod_sucursal_venta) 
													VALUES ($cod_estudiante, $efectivo, $tipo_pago, '$nro_transaccion', $cod_usuario, $cod_sucursal_est)");

											if(mysqli_affected_rows($con) > 0){
												// Obtener el codigo de la tabla
												$cod_tabla = 0;
												$sql_tabla = mysqli_query($con, "SELECT cod_tabla FROM tbl_tabla WHERE nombre_tabla = 'tbl_venta'");
												while ($row_ta = mysqli_fetch_array($sql_tabla)) {
													$cod_tabla = $row_ta['cod_tabla'];
												}

												// Obtener el ultimo codigo de la tabla tbl_venta
												$sql_ult_venta = mysqli_query($con, "SELECT cod_venta, fecha_venta FROM tbl_venta 
													WHERE cod_usuario_venta = $cod_usuario ORDER BY cod_venta DESC LIMIT 0,1");
												while ($row_ult = mysqli_fetch_row($sql_ult_venta)) {
													$cod_venta = $row_ult[0];
													$fecha_venta = $row_ult[1];
												}

												// tbl_log
												$sql_log = mysqli_query($con, "INSERT INTO tbl_log(cod_tipolog_log, cod_tabla_log, codigo_log, cod_usuario_log) 
													VALUES(1, $cod_tabla, $cod_venta, $cod_usuario)");
											} // if(tbl_venta)
										}

										// OBTNER EL DETALLE DE LA TABLA tbl_detalle_movimiento
										$cod_articulo_nmov = 0;
										$descripcion = "";
										$cantidad_nmov = 0;
										$precio_nmov = 0; $factura_nmov = 0; $dscto_nmov = 0; 
										$subtotal_nmov = 0;
										$sql_det_mov = mysqli_query($con, "SELECT cod_articulo_nmov, descripcion_nmov, cantidad_nmov, precio_nmov, factura_nmov, dscto_nmov, subtotal_nmov, tipo_nmov 
											FROM tbl_detalle_movimiento WHERE cod_estudiante_nmov = $cod_estudiante AND cod_usuario_nmov = $cod_usuario");
										while ($row_det = mysqli_fetch_array($sql_det_mov)) {
											$cod_articulo_nmov = $row_det['cod_articulo_nmov'];
											$descripcion = $row_det['descripcion_nmov'];
											$cantidad_nmov = $row_det['cantidad_nmov'];
											$precio_nmov = $row_det['precio_nmov'];
											$factura_nmov = $row_det['factura_nmov'];
											$dscto_nmov = $row_det['dscto_nmov'];
											$subtotal_nmov = $row_det['subtotal_nmov'];

											if($row_det['tipo_nmov'] == 1 || $row_det['tipo_nmov'] == "1"){
												// ARTICULO NOMRAL
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
														precio_detven, factura_detven, cantidad_detven, dscto_detven, monto_bs_detven) 
														VALUES ($cod_venta, $cod_articulo_nmov, '$descripcion', $precio_nmov, $factura_nmov, $cantidad_nmov, $dscto_nmov, $subtotal_nmov)");
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

												$subtotal_nmov = $subtotal_nmov - $factura_nmov;
												if($cod_tipoarticulo != 3){
													$insert_cuenta_debe = mysqli_query($con, "INSERT INTO tbl_cuenta_estudiante (cod_estudiante_cuenta, cod_venta_cuenta, 
														precio_debe_cuenta, cod_articulo_cuenta, precio_haber_cuenta, cod_tipocuenta_cuenta, descripcion_cuenta)
														VALUES ($cod_estudiante, $cod_venta, $subtotal_nmov, $cod_articulo_nmov, 0, 3, '$descripcion')");
												}elseif(strpos($nombre_art, "PRECIO TOTAL AÑO") !== false){
													$insert_cuenta_debe = mysqli_query($con, "INSERT INTO tbl_cuenta_estudiante (cod_estudiante_cuenta, cod_venta_cuenta, 
														precio_debe_cuenta, cod_articulo_cuenta, precio_haber_cuenta, cod_tipocuenta_cuenta, descripcion_cuenta)
														VALUES ($cod_estudiante, $cod_venta, $precio_nmov, $cod_articulo_nmov, 0, 4, '$descripcion')");
												}
												
												if(strpos($nombre_art, "PRECIO TOTAL AÑO") === false){
													$insert_cuenta_haber = mysqli_query($con, "INSERT INTO tbl_cuenta_estudiante (cod_estudiante_cuenta, cod_venta_cuenta, 
														precio_debe_cuenta, cod_articulo_cuenta, precio_haber_cuenta, cod_tipocuenta_cuenta, descripcion_cuenta)
														VALUES ($cod_estudiante, $cod_venta, 0, $cod_articulo_nmov, $subtotal_nmov, 2, '$descripcion')");
												}
											}else{
												$fecha_his = "";
												// REGISTRAR LA MATERIA EN EL HISTORICO
												$insert_his = mysqli_query($con, "INSERT INTO tbl_historico (cod_estudiante_his, cod_oferta_materia_his, nota_final_his) 
													VALUES ($cod_estudiante, $cod_articulo_nmov, NULL)");
												if(mysqli_affected_rows($con) > 0){
													// Obtener el codigo de la tabla
													$cod_tabla = 0;
													$sql_tabla = mysqli_query($con, "SELECT cod_tabla FROM tbl_tabla WHERE nombre_tabla = 'tbl_historico'");
													while ($row_ta = mysqli_fetch_array($sql_tabla)) {
														$cod_tabla = $row_ta['cod_tabla'];
													}
													// Obtener el ultimo cod_historico
													$cod_historico = 0;
													$sql_historico = mysqli_query($con, "SELECT cod_historico, fecha_his FROM tbl_historico ORDER BY cod_historico DESC LIMIT 0,1");
													while ($row_h = mysqli_fetch_array($sql_historico)) {
														$cod_historico = $row_h['cod_historico'];
														$fecha_his = $row_h['fecha_his'];
													}
													// tbl_log
													$sql_log = mysqli_query($con, "INSERT INTO tbl_log(cod_tipolog_log, cod_tabla_log, codigo_log, cod_usuario_log) 
														VALUES(1, $cod_tabla, $cod_historico, $cod_usuario)");

													$data['mensaje'] = "ESTUDIANTE REGISTRADO EXITOSAMENTE";
													$data['tipo'] = 1;
												}
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

									<!-- TABLA REQUISITOS -->
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

									<?php

									$anho = date('Y');
									$detalle_materias = "";
									// Obtener las materias registradas en la gestion
									$sql_historico2 = mysqli_query($con, "SELECT cod_hiStorico, nombre_mat, nombre_tur, nombre_gru FROM tbl_historico, tbl_oferta_materia, tbl_materia, tbl_periodo, tbl_gestion, tbl_turno, tbl_grupo 
									WHERE cod_oferta_materia_his = cod_oferta_materia AND cod_materia_of = cod_materia AND cod_periodo_of = cod_periodo AND cod_gestion_peri = cod_gestion 
									AND cod_turno_of = cod_turno AND cod_grupo_of = cod_grupo AND estado_his = 1 AND nombre_gest = '$anho' AND cod_estudiante_his = $cod_estudiante");
									if (mysqli_num_rows($sql_historico2) > 0) {
										$detalle_materias = "MATERIAS REGISTRADAS EN LA GESTION (".$anho.")<br>";
										while ($row_h = mysqli_fetch_array($sql_historico2)) {
											$detalle_materias = $detalle_materias."- ".ucwords(mb_strtolower($row_h['nombre_mat'], "UTF-8"))." - (".$row_h["nombre_tur"]."/".$row_h["nombre_gru"].")<br>";
										}
									}

										//ELIMINAR REGISTROS DEL DETALLE
										$delete_detalle_mov = mysqli_query($con, "DELETE FROM tbl_detalle_movimiento 
											WHERE cod_estudiante_nmov = $cod_estudiante AND cod_usuario_nmov = $cod_usuario");
										if(mysqli_affected_rows($con) > 0){
											// Codificar imagen del logo de la empresa
											$image='img/'.$imagen_suc;
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
											$fecha_recibo = date("Y-m-d H:i a");

											if($cod_venta != 0){
												$nro_recibo = $cod_venta;
												$fecha_recibo = $fecha_venta;
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
												$fecha_recibo = date("Y-m-d H:i a");
											}
										} // Fin delete detalle_movimiento

									} // IF (tbl_detalle_movimiento)

									$fecha_recibo = date_format(date_create($fecha_recibo), "d-m-Y H:i a");
									// $date = new DateTime($fecha_recibo, new DateTimeZone('Europe/Madrid'));
									// $date->format('Y-m-d H:i:sP');

									// $date->setTimezone(new DateTimeZone('America/La_Paz')); 
									// $fecha_recibo =  $date->format('d-m-Y H:i a');
								} // IF ($_GET)
								?>
								<br>
								<div class="text-center">
									<a href="caja.php">Registrar Nueva Transacción</a>
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
		<!-- <script src="vendor/pdff/js_table/libre/jspdf.min.js"></script> -->
		<!-- <script src="vendor/pdff/js_table/libre/jspdf.plugin.autotable.src.js"></script> -->
		
		<script src="vendor/pdff/js_table/libre3/libs/jspdf.umd.js"></script>
		<script src="vendor/pdff/js_table/libre3/dist/jspdf.plugin.autotable.js"></script>
		<!-- App script-->
		<script src="js/app.js"></script>
		<script type="text/javascript">
			$(document).ready(function(){
				// RECIBO CON EFECTIVO
				$("#registrard4").click(function(){
					var doc = new jspdf.jsPDF('p', 'pt', 'a4');
					var $tables2 = $(".pdftable22");
					var startingY = 190;
					var tbl_ingreso='';
					var img_logo = "<?php echo $logo_fact; ?>";
					
					var efectivo = "<?php echo $efectivo; ?>" ;
					var devuelto = "<?php echo $devolucion; ?>" ;

					var nombre_tp = "<?php echo $nombre_tp.$det_nro_tran; ?>" ;

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
						doc.text("Nro. Rec.: <?php echo $nro_recibo; ?>", 375, 25);
						doc.setFontSize(7);
						doc.text("ESTUDIANTE: <?php echo $nombre_estudiante; ?>", 20, 60);
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
						doc.text("<?php echo number_format((abs ($efectivo)), 2); ?>", 200, doc.autoTableEndPosY() + 35);

						doc.text("Fecha Transaccion : <?php echo $fecha_recibo; ?> ", 25, doc.autoTableEndPosY() + 55);

						doc.setFontSize(7);
						var materias = "<?php echo $detalle_materias; ?>";
						materias = materias.replaceAll("<br>", "\n");
						doc.text(materias, 280, doc.autoTableEndPosY() + 30);
						doc.setFontSize(8);

						doc.text("Cajero : <?php echo $nombre_usuario.' '.$apellido_usuario; ?>", 25, doc.autoTableEndPosY() + 65);
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

		<div class="modal fade bd-example-modal-lg" id="modal_pdf" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
			<div class="modal-dialog modal-lg" role="document">
				<div class="modal-content">
					<div class="modal-body">
						<embed id="pdf_file" frameborder="0" width="100%" height="700px">
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