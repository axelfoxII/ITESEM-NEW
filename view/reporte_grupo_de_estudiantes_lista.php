<?php
include('../conf/funciones.php');
include("../conf/mysql.php");
$cod_usuario = 0;
if (verificar_usuario()) {
	$cod_usuario = $_SESSION['cod_usuario'];
	date_default_timezone_set('America/La_Paz');
	$nombre_pagina = "reporte_grupo_de_estudiantes.php";
	// Verificar el privilegio de la pagina
	$sql_pagina = mysqli_query($con, "SELECT cod_privilegio FROM tbl_submenu, tbl_privilegio, tbl_usuario 
		WHERE cod_submenu = cod_submenu_priv AND cod_perfil_priv = cod_perfil_us AND estado_priv = 1 
		AND cod_usuario = $cod_usuario AND enlace_subm = '$nombre_pagina'");
	if (mysqli_num_rows($sql_pagina) > 0) {
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
			<style>
				.pago {
					color: green;
					font-weight: bold;
				}

				.pendiente {
					color: red;
					font-weight: bold;
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
							<div class="cardbox">
								<div class="cardbox-body" id="BARRA">
									<h4><a href="reporte_grupo_de_estudiantes.php"><i class="ion-arrow-left-c"></i>Volver
											Atras</a></h4>
									<br>
									<div id="expor">
										<form action="funciones/exportar_cuenta_estudiante.php" method="post" target="_blank"
											id="FormularioExportacion">
											<a href="" class="botonExcel"><i class="ion-document-text "
													title="Exportar a Excel"></i> - Exportar a Excel</a>
											<input type="hidden" id="datos_a_enviar" name="datos_a_enviar" />
											<input type="hidden" id="nombre" name="nombre" value="reporte-grupo-estudiantes" />
										</form>
									</div>

									<div id="resultadoBusqueda">
										<center>
											<h4>
												<font face="courier">REPORTE POR GRUPO - (PAGOS)</font>
											</h4>
										</center>
										<?php
										$cod_oferta = 0;
										if (isset($_GET['gru']) && isset($_GET['per'])) {
											$cod_grupo = $_GET['gru'];
											$cod_periodo = $_GET['per'];

											$materia = "";
											$carrera = "";
											$docente = "";
											$turno = "";
											$aula = "";
											$anho_niv = 0;
											$mensualidad = 0;
											$mes_gestion = "";
											$fecha_ini = "0000-00-00";
											$sql_oferta = mysqli_query($con, "SELECT cod_grupo_of, cod_periodo_of, nombre_gru, nombre_car, anho_niv, mensualidad_car, resolucion_ministerial_car, nombre_tur, nombre_tipmod, nombre_au, 
												nombre_gest, nombre_peri, fecha_inicio_of 
												FROM tbl_oferta_materia, tbl_materia, tbl_carrera, tbl_nivel, tbl_turno, tbl_grupo, tbl_tipo_modalidad, tbl_aula, tbl_periodo, tbl_gestion 
												WHERE cod_materia_of = cod_materia AND cod_carrera_mat = cod_carrera AND cod_turno_of = cod_turno AND cod_grupo_of = cod_grupo AND cod_tipomodalidad_of = cod_tipomodalidad 
												AND cod_nivel_car = cod_nivel 
												AND cod_aula_of = cod_aula AND cod_periodo_of = cod_periodo AND cod_gestion_peri = cod_gestion AND estado_of = 1 AND cod_grupo_of = $cod_grupo AND cod_periodo_of = $cod_periodo 
												GROUP BY nombre_gru, nombre_car ORDER BY cod_turno, cod_oferta_materia");
											while ($row_o = mysqli_fetch_array($sql_oferta)) {
												$mes_gestion = $row_o['nombre_gest'] . "-" . $row_o['nombre_peri'];
												$turno = $row_o['nombre_gru'] . " / " . $row_o['nombre_tur'] . " / " . $row_o['nombre_tipmod'];
												$aula = $row_o['nombre_au'];
												$anho_niv = $row_o['anho_niv'];
												$mensualidad = $row_o['mensualidad_car'];
												$fecha_ini = date_format(date_create($row_o['fecha_inicio_of']), "d-m-Y");
											}
										?>
											<div class="row">
												<div class="col-sm-12">
													<font face="courier">
														<label>Fecha de Inicio : <?php echo $fecha_ini; ?></label><br>
														<label>Turno : <?php echo $turno; ?></label><br>
														<label>Periodo : <?php echo $mes_gestion; ?></label><br>
														<label>Aula : <?php echo $aula; ?></label>
													</font>
												</div>
											</div>
											<div class="no-more-tables">
												<table id="datatable_buscador" class="table table-striped table-sm table-bordered">
													<thead>
														<tr class="table-secondary text-center" style="border: 1px solid;">
															<th>#</th>
															<th>ESTUDIANTE</th>
															<th>CELULAR</th>
															<th>MATR.</th> <!-- Mover "MATR." fuera del bucle -->
															<?php
															for ($m = 1; $m <= $mensualidad; $m++) {
															?>
																<th>C<?php echo $m; ?></th>
															<?php
															}
															?>
															<th>TOTAL</th>
														</tr>
													</thead>
													<tbody>
														<?php
														$item = 1;
														$cod_estudiante = 0;

							            	$sql_historico = mysqli_query($con, "SELECT 
																				te.cod_estudiante, 
																				tp.nombre_per, 
																				tp.apellido_per, 
																				tp.carnet_per, 
																				tp.celular_per,
																				tplan.sigla_plan, 
																				tplan.precio_plan,  -- Utilizamos el precio_plan directamente
																				tcar.cod_carrera, 
																				tcar.sigla_car, 
																				tcar.mensualidad_car, 
																				tnivel.anho_niv,
																				tg.cod_grupo, 
																				tpdo.cod_periodo, 
																				tt.cod_turno, 
																				ta.cod_aula,
																				-- Subconsulta para obtener la última fecha de pago realizada donde el cod_articulo_cuenta = 2 (mensualidades)
																				(
																					SELECT MAX(tc.fecha_cuenta)
																					FROM tbl_cuenta_estudiante tc
																					WHERE 
																						tc.cod_estudiante_cuenta = te.cod_estudiante
																						AND tc.estado_cuenta = 1
																						AND tc.cod_articulo_cuenta = 2
																				) AS ultima_fecha_pago,
																				-- Subconsulta para contar el número de cuotas pagadas donde el cod_articulo_cuenta = 2 (mensualidades)
																				(
																					SELECT COUNT(*) 
																					FROM tbl_cuenta_estudiante tc
																					WHERE 
																						tc.cod_estudiante_cuenta = te.cod_estudiante
																						AND tc.estado_cuenta = 1
																						AND tc.cod_articulo_cuenta = 2
																				) AS numero_cuotas_pagadas,
																				-- Verificamos si el número de cuotas pagadas es igual a la mensualidad_car
																				CASE 
																					WHEN 
																						(
																							SELECT COUNT(*) 
																							FROM tbl_cuenta_estudiante tc
																							WHERE 
																								tc.cod_estudiante_cuenta = te.cod_estudiante
																								AND tc.estado_cuenta = 1
																								AND tc.cod_articulo_cuenta = 2
																								AND tc.fecha_cuenta <= LAST_DAY(CURRENT_DATE())
																						) >= TIMESTAMPDIFF(MONTH, tpdo.fecha_ini_peri, LAST_DAY(CURRENT_DATE()))
																						OR 
																						tcar.mensualidad_car = 
																						(
																							SELECT COUNT(*) 
																							FROM tbl_cuenta_estudiante tc
																							WHERE 
																								tc.cod_estudiante_cuenta = te.cod_estudiante
																								AND tc.estado_cuenta = 1
																								AND tc.cod_articulo_cuenta = 2
																						)
																					THEN 'PAGADO'
																					ELSE 'DEUDA'
																				END AS estado_pago_hasta_fecha_actual,
																			-- Calcular el total pagado multiplicando el precio del plan por el número de cuotas pagadas
																				(
																					(
																						SELECT SUM(precio_haber_cuenta) 
																						FROM tbl_cuenta_estudiante tc
																						WHERE 
																							tc.cod_estudiante_cuenta = te.cod_estudiante
																							AND tc.estado_cuenta = 1
																							AND tc.cod_articulo_cuenta = 2
																					)
																				) AS total_pagado_hasta_fecha_actual
																			FROM 
																				tbl_estudiante te
																			INNER JOIN 
																				tbl_persona tp ON te.cod_persona_est = tp.cod_persona
																			INNER JOIN 
																				tbl_plan tplan ON te.cod_plan_est = tplan.cod_plan -- Asegurarse de incluir el precio del plan
																			INNER JOIN 
																				tbl_carrera tcar ON te.cod_carrera_est = tcar.cod_carrera
																			INNER JOIN 
																				tbl_nivel tnivel ON tcar.cod_nivel_car = tnivel.cod_nivel
																			LEFT JOIN 
																				tbl_cuenta_estudiante tc ON te.cod_estudiante = tc.cod_estudiante_cuenta AND tc.cod_tipocuenta_cuenta = 2
																			INNER JOIN 
																				tbl_historico th ON te.cod_estudiante = th.cod_estudiante_his
																			INNER JOIN 
																				tbl_oferta_materia tom ON th.cod_oferta_materia_his = tom.cod_oferta_materia
																			INNER JOIN 
																				tbl_grupo tg ON tom.cod_grupo_of = tg.cod_grupo
																			INNER JOIN 
																				tbl_periodo tpdo ON tom.cod_periodo_of = tpdo.cod_periodo
																			INNER JOIN 
																				tbl_turno tt ON tom.cod_turno_of = tt.cod_turno
																			INNER JOIN 
																				tbl_aula ta ON tom.cod_aula_of = ta.cod_aula
																			WHERE 
																				tom.cod_grupo_of = $cod_grupo
																				AND tom.cod_periodo_of = $cod_periodo 
																				AND th.estado_his = 1 
																			GROUP BY 
																				te.cod_estudiante
																			ORDER BY 
																				tp.apellido_per, tp.nombre_per, tg.cod_grupo, tpdo.cod_periodo, tt.cod_turno;");

														if (mysqli_num_rows($sql_historico) > 0) {
															while ($row_h = mysqli_fetch_array($sql_historico)) {
																$cod_estudiante = $row_h['cod_estudiante'];
																$anho_niv = $row_h['anho_niv'];

																// Contar pagos pendientes
																$pendientes = 0;
																$pagos = [];
																$totales =[];
																$fecha_hoy = date('Y-m-d');
																$estado_cuota = '';

																for ($m = 0; $m < $row_h['mensualidad_car']; $m++) {
																	$sql_cuenta2 = mysqli_query($con, "SELECT nombre_art, precio_haber_cuenta, descripcion_cuenta, fecha_cuenta 
																		FROM tbl_cuenta_estudiante, tbl_articulo 
																		WHERE cod_articulo_cuenta = cod_articulo 
																		AND nombre_art = 'CUOTA DEL PLAN' 
																		AND cod_estudiante_cuenta = $cod_estudiante 
																		AND estado_cuenta = 1 
																		AND cod_tipocuenta_cuenta = 2 
																		
																		ORDER BY fecha_cuenta 
																		LIMIT $m, 1");

																	if (mysqli_num_rows($sql_cuenta2) > 0) {
																		while ($row_c2 = mysqli_fetch_array($sql_cuenta2)) {
																			$estado_cuota = ($row_c2['precio_haber_cuenta'] > 0) ? $row_h['precio_plan'] : '0';
																			if ($estado_cuota == 'pendiente') {
																				$pendientes++;
																			}
																			$pagos[] = $estado_cuota;
																			$totales[]= $row_h['total_pagado_hasta_fecha_actual'];
																		}
																	} else {
																		if ($estado_cuota > 0) {
																			$pendientes++;
																			$pagos[] = '0';
																		} else {

																			$pagos[] = '0';
																		}
																	}
																}

																// Establecer el color según la cantidad de pendientes
																/* $clase_estudiante = ($row_h['estado_pago_hasta_fecha_actual'] == 'DEUDA') ? 'bg-danger text-white' : 'bg-success text-white'; */

														?>
																<tr style="border: 1px solid;">
																	<td data-title="#" class="text-center"><?php echo $item++; ?></td>
																	<td data-title="ESTUDIANTE" class="<?php echo $clase_estudiante; ?>">
																		<?php echo "<small>" . $row_h['carnet_per'] . " - " . $row_h['apellido_per'] . " - " . $row_h['nombre_per'] . "</small>"; ?>
																	</td>
																	<td data-title="CELULAR" class="text-center">
																		<?php echo "<small>" . $row_h['celular_per'] . "</small>"; ?>
																	</td>
																	<?php
																	// Mostrar la matrícula
																	$sql_cuenta = mysqli_query($con, "SELECT nombre_art, precio_haber_cuenta, descripcion_cuenta, fecha_cuenta 
																		FROM tbl_cuenta_estudiante, tbl_articulo 
																		WHERE cod_articulo_cuenta = cod_articulo 
																		AND (nombre_art LIKE 'MATRICULA GEST%' OR nombre_art LIKE 'DERECHO REGISTRO%') 
																		AND cod_estudiante_cuenta = $cod_estudiante 
																		AND estado_cuenta = 1 
																		AND cod_tipocuenta_cuenta = 2 
																		ORDER BY fecha_cuenta 
																		LIMIT 1");

																	if (mysqli_num_rows($sql_cuenta) > 0) {
																		/* LA MATRICULA */
																		while ($row_c = mysqli_fetch_array($sql_cuenta)) {
																			$estado_cuota = ($row_c['precio_haber_cuenta'] > 0) ? 'pago' : 'pendiente';
																			echo "<td align='center' class='$estado_cuota'> ✔ </td>";
																																			
																		}
																	} else {
																		echo "<td align='center' class='pendiente'>❌</td>";
																	}

																	// Mostrar los pagos
																	foreach ($pagos as $pago) {
																		echo "<td align='center' class='$pago'><small>" . ucfirst($pago) . "</small></td>";
																		
																	}
																	$suma_cuota = ($row_h['total_pagado_hasta_fecha_actual'] > 0) ? $row_h['total_pagado_hasta_fecha_actual'] : 0;
																	echo "<td align='center' style='font-weight: bold;'><small><b>" . $suma_cuota . "</b><small></td>";
																	?>
																</tr>
														<?php
															}
														}
														?>
													</tbody>

												</table>
											</div>
										<?php } ?>
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
			<!-- App script-->
			<script src="js/app.js"></script>
			<style>
				#BARRA {
					overflow: scroll;
					color: #000000;
					width: 1100px;
					height: 700px;
					margin: 5px 200;
					padding: 30px 30px 30px 30px;
					border: Solid 1px #000000;
					scrollbar-base-color: #000000;
					scrollbar-arrow-color: #CCCCCC;
					scrollbar-track-color: #CCCCCC;
				}
			</style>
			<script>
				$(document).ready(function() {
					$(".botonExcel").click(function(event) {
						$("#datos_a_enviar").val($("<div>").append($("#resultadoBusqueda").eq(0).clone()).html());
						$("#FormularioExportacion").submit();
					});
				});
			</script>
		</body>

		</html>
<?php
	} else {
		header('Location:inicio.php');
	}
} else {
	header('Location:../inicio.html');
}
?>