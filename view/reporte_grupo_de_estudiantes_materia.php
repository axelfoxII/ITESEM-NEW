<?php
include ('../conf/funciones.php');
include("../conf/mysql.php");
$cod_usuario = 0;
if (verificar_usuario()){
	$cod_usuario = $_SESSION['cod_usuario'];
	date_default_timezone_set('America/La_Paz');
	$nombre_pagina = "reporte_grupo_de_estudiantes.php";
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
									<h4><a href="reporte_grupo_de_estudiantes.php"><i class="ion-arrow-left-c"></i>Volver Atras</a></h4>
									<br>
									<div id="expor">
										<form action="funciones/exportar_cuenta_estudiante.php" method="post" target="_blank" id="FormularioExportacion">
											<a href="" class = "botonExcel"><i class="ion-document-text " title="Exportar a Excel"></i> - Exportar a Excel</a>
											<input type="hidden" id="datos_a_enviar" name="datos_a_enviar" />
											<input type="hidden" id="nombre" name="nombre" value="reporte-grupo-estudiantes" />
										</form>
									</div>

									<div id="resultadoBusqueda">
										<center><h4><font face="courier">REPORTE POR GRUPO - (MATERIAS)</font></h4></center>
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
											$cod_carrera = 0;
											$mes_gestion = ""; $fecha_ini = "0000-00-00";
											$sql_oferta = mysqli_query($con, "SELECT cod_grupo_of, cod_periodo_of, nombre_gru, cod_carrera, nombre_car, resolucion_ministerial_car, nombre_tur, nombre_tipmod, nombre_au, 
												nombre_gest, nombre_peri, fecha_inicio_of 
												FROM tbl_oferta_materia, tbl_materia, tbl_carrera, tbl_nivel, tbl_turno, tbl_grupo, tbl_tipo_modalidad, tbl_aula, tbl_periodo, tbl_gestion 
												WHERE cod_materia_of = cod_materia AND cod_carrera_mat = cod_carrera AND cod_turno_of = cod_turno AND cod_grupo_of = cod_grupo AND cod_tipomodalidad_of = cod_tipomodalidad 
												AND cod_nivel_car = cod_nivel 
												AND cod_aula_of = cod_aula AND cod_periodo_of = cod_periodo AND cod_gestion_peri = cod_gestion AND estado_of = 1 AND cod_grupo_of = $cod_grupo AND cod_periodo_of = $cod_periodo 
												GROUP BY nombre_gru, nombre_car ORDER BY cod_turno, cod_oferta_materia");
											while ($row_o = mysqli_fetch_array($sql_oferta)) {
												$mes_gestion = $row_o['nombre_gest']."-".$row_o['nombre_peri'];
												$turno = $row_o['nombre_gru']." / ".$row_o['nombre_tur']." / ".$row_o['nombre_tipmod'];
												$aula = $row_o['nombre_au'];
												$cod_carrera = $row_o['cod_carrera'];
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
														<tr align="center" class="table-primary" style = "border: 1px solid;">
															<th>#</th>
															<th>ESTUDIANTE</th>
															<th>CELULAR</th>
															<?php
															// MATERIAS
															$sql_materia = mysqli_query($con, "SELECT cod_materia, nombre_mat FROM tbl_materia WHERE cod_carrera_mat = $cod_carrera AND estado_mat = 1 ORDER BY numero_mat");
															if(mysqli_num_rows($sql_materia) > 0){
																while ($row_m = mysqli_fetch_array($sql_materia)) {
																	?>
																	<th><?php echo $row_m['nombre_mat']; ?></th>
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
														if(mysqli_num_rows($sql_historico) > 0){
															while ($row_h = mysqli_fetch_array($sql_historico)) {
																$cod_estudiante = $row_h['cod_estudiante'];
																$anho_niv = $row_h['anho_niv'];
																?>
																<tr style = "border: 1px solid;">
																	<td data-title="#"><?php echo $item++; ?></td>
																	<td data-title="ESTUDIANTE"><?php echo "<small>".$row_h['carnet_per']." - ".$row_h['apellido_per']." - ".$row_h['nombre_per']."</small>"; ?></td>
																	<td data-title="CELULAR"><?php echo "<small>".$row_h['celular_per']."</small>"; ?></td>
																	<?php
																	// MATERIAS
																	$cod_materia = 0;
																	$sql_materia = mysqli_query($con, "SELECT cod_materia FROM tbl_materia WHERE cod_carrera_mat = $cod_carrera AND estado_mat = 1 ORDER BY numero_mat");
																	if(mysqli_num_rows($sql_materia) > 0){
																		while ($row_m = mysqli_fetch_array($sql_materia)) {
																			$cod_materia = $row_m['cod_materia'];

																			// HISTORICO
																			$estado = "";
																			$sql_historico2 = mysqli_query($con, "SELECT nota_final_his FROM tbl_historico, tbl_oferta_materia WHERE cod_oferta_materia_his = cod_oferta_materia 
																				AND cod_estudiante_his = $cod_estudiante AND cod_materia_of = $cod_materia AND estado_his = 1 ORDER BY nota_final_his LIMIT 0,1");
																			if (mysqli_num_rows($sql_historico2) > 0) {
																				while ($row_m = mysqli_fetch_array($sql_historico2)) {
																					if($row_m['nota_final_his'] > 50)
																						$estado = "<font color='green'>".$row_m['nota_final_his']."</font>";
																					elseif($row_m['nota_final_his'] == "")
																						$estado = "<font color='blue'>INSC.</font>";
																					elseif($row_m['nota_final_his'] < 51)
																						$estado = "<font color='red'>".$row_m['nota_final_his']."</font>";
																					?>
																					<td align="center"><?php echo $estado; ?></td>
																					<?php
																				}
																			}else{
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
														}else{
															?>
															<tr align='center'><td colspan="6">No se encontraron resultados.</td></tr>
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
			#BARRA{
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
					$("#datos_a_enviar").val( $("<div>").append( $("#resultadoBusqueda").eq(0).clone()).html());
					$("#FormularioExportacion").submit();
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