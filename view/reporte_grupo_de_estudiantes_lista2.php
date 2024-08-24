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

										/* 	TITULOS-->FECHA INICIO,TURNO,PERIODO,AULA */
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
														<tr align="center" class="table-primary" style="border: 1px solid;">
															<th>#</th>
															<th>ESTUDIANTE</th>
															<th>CELULAR</th>
															<?php
															for ($i = 1; $i <= $anho_niv; $i++) {
																?>
																<th>MATR.</th>
																<?php
																for ($m = 1; $m <= $mensualidad; $m++) {
																	?>
																	<th>C<?php echo $m; ?></th>
																	<?php
																}
															}
															?>
														</tr>
													</thead>
													<tbody>
														<?php
														// Obtener los estudiantes de la materia
														$item = 1;
														$estado = "";
														$cod_estudiante = 0;
														$sql_historico = mysqli_query($con, "SELECT cod_estudiante, nombre_per, apellido_per, carnet_per, sigla_plan, cod_carrera, sigla_car, nota_final_his, celular_per, 
															mensualidad_car, anho_niv 
															FROM tbl_historico, tbl_estudiante, tbl_persona, tbl_plan, tbl_carrera, tbl_nivel, tbl_oferta_materia 
															WHERE cod_estudiante_his = cod_estudiante AND cod_persona = cod_persona_est AND cod_plan_est = cod_plan AND cod_nivel_car = cod_nivel 
															AND cod_carrera_est = cod_carrera AND cod_oferta_materia = cod_oferta_materia_his AND cod_grupo_of = $cod_grupo AND cod_periodo_of = $cod_periodo AND estado_his = 1 
															GROUP BY cod_estudiante ORDER BY apellido_per, nombre_per");
														if (mysqli_num_rows($sql_historico) > 0) {
															while ($row_h = mysqli_fetch_array($sql_historico)) {
																$cod_estudiante = $row_h['cod_estudiante'];
																$anho_niv = $row_h['anho_niv'];
																?>
																<tr style="border: 1px solid;">
																	<td data-title="#"><?php echo $item++; ?></td>
																	<td data-title="ESTUDIANTE">
																		<?php echo "<small>" . $row_h['carnet_per'] . " - " . $row_h['apellido_per'] . " - " . $row_h['nombre_per'] . "</small>"; ?>
																	</td>
																	<td data-title="CELULAR">
																		<?php echo "<small>" . $row_h['celular_per'] . "</small>"; ?>
																	</td>
																	<?php
																	for ($i = 0; $i < $anho_niv; $i++) {
																		$sql_cuenta = mysqli_query($con, "SELECT nombre_art, precio_haber_cuenta, descripcion_cuenta, fecha_cuenta FROM tbl_cuenta_estudiante, tbl_articulo 
																			WHERE cod_articulo_cuenta = cod_articulo AND (nombre_art LIKE 'MATRICULA GEST%' OR nombre_art LIKE 'DERECHO REGISTRO%') AND cod_estudiante_cuenta = $cod_estudiante AND estado_cuenta = 1 AND cod_tipocuenta_cuenta = 2 ORDER BY fecha_cuenta LIMIT $i, 1");
																		if (mysqli_num_rows($sql_cuenta) > 0) {
																			while ($row_c = mysqli_fetch_array($sql_cuenta)) {
																				?>
																				<td align="center" class="table-success">
																					<?php echo "<small>" . number_format(abs($row_c['precio_haber_cuenta']), 0) . "</small>"; ?>
																				</td>
																				<?php
																			}
																		} else {
																			?>
																			<td></td>
																			<?php
																		}

																		if ($i == 0)
																			$men = 0;
																		elseif ($i == 1)
																			$men = $mensualidad;
																		elseif ($i == 2)
																			$men = $mensualidad * 2;

																		for ($m = $men; $m < $mensualidad; $m++) {
																			//ORDENA SEGUN LA FECHA LAS CUOTAS PAGADAS
																			$sql_cuenta2 = mysqli_query($con, "SELECT ta.nombre_art, tc.precio_haber_cuenta, tc.descripcion_cuenta, tc.fecha_cuenta FROM tbl_cuenta_estudiante tc INNER JOIN tbl_articulo ta ON tc.cod_articulo_cuenta = ta.cod_articulo INNER JOIN tbl_estudiante te ON tc.cod_estudiante_cuenta = te.cod_estudiante INNER JOIN tbl_carrera tcar ON te.cod_carrera_est = tcar.cod_carrera WHERE ta.nombre_art = 'CUOTA DEL PLAN' AND tc.cod_estudiante_cuenta = $cod_estudiante AND tc.estado_cuenta = 1 AND tc.cod_tipocuenta_cuenta = 2 ORDER BY tc.fecha_cuenta;");
																			if (mysqli_num_rows($sql_cuenta2) > 0) {
																				while ($row_c = mysqli_fetch_array($sql_cuenta2)) {
																					?>
																					<td align="center">
																						<?php echo "<small>" . number_format(abs($row_c['precio_haber_cuenta']), 0) . "</small>"; ?>
																					</td>
																					<?php
																				}
																			} else {
																				?>
																				<td></td>
																				<?php
																			}
																		}
																	}
																	?>
																</tr>
																<?php
															}
														} else {
															?>
															<tr align='center'>
																<td colspan="6">No se encontraron resultados.</td>
															</tr>
															<?php
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
				$(document).ready(function () {
					$(".botonExcel").click(function (event) {
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