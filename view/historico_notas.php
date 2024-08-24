<?php
include ('../conf/funciones.php');
include("../conf/mysql.php");
$cod_usuario = 0;
if (verificar_usuario()){
	$cod_usuario = $_SESSION['cod_usuario'];
	date_default_timezone_set('America/La_Paz');
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
								<h4><a href="historico.php"><i class="ion-arrow-left-c"></i>Volver Atras</a></h4>
								<div id="expor" class="text-right">
									<form action="funciones/exportar_cuenta_estudiante.php" method="post" target="_blank" id="FormularioExportacion">
										<a href="" class = "botonExcel"><i class="ion-document-text " title="Exportar a Excel"></i> - Exportar a Excel</a>
										<input type="hidden" id="datos_a_enviar" name="datos_a_enviar" />
										<input type="hidden" id="nombre" name="nombre" value="historico_estudiante" />
									</form>
								</div>
								<center><h2><font face="courier">Historial de Notas</font></h2></center>
								<?php
								$cod_estudiante = 0;
								if (isset($_GET['cod'])) {
									$cod_estudiante = $_GET['cod'];

									$nombre = "";
									$carnet = "";
									$carrera = ""; $cod_carrera = 0;
									$sql_estudiante = mysqli_query($con, "SELECT nombre_per, apellido_per, carnet_per, sigla_expedido, nombre_car, resolucion_ministerial_car, cod_carrera 
										FROM tbl_estudiante, tbl_persona, tbl_carrera, tbl_expedido 
										WHERE cod_persona = cod_persona_est AND cod_carrera_est = cod_carrera AND cod_expedido_per = cod_expedido 
										AND cod_estudiante = $cod_estudiante");
									while ($row_e = mysqli_fetch_array($sql_estudiante)) {
										$nombre = $row_e['apellido_per']." ".$row_e['nombre_per'];
										$carnet = $row_e['carnet_per']." ".$row_e['sigla_expedido'];
										$carrera = $row_e['nombre_car']." - R.M. ".$row_e['resolucion_ministerial_car'];
										$cod_carrera = $row_e['cod_carrera'];
									}
									?>
									<br>
									<div class="row">
										<div class="col-sm-12">
											<font size="3" face="courier">
												C.I. &nbsp;&nbsp;: <?php echo $carnet." - ".$nombre; ?> <span style="float:right;">Fecha:<?php echo " ".date("d-m-Y");?></span><br>
												Carrera : <?php echo $carrera; ?></label><label style="float:right;">Hora:<?php  echo $date = date('h:i a', time()); ?></label>
											</font>
										</div>
									</div>
									<hr>
									<div id="resultadoBusqueda">
										<font face="Tahoma" size="2">
											<table class="table table-striped">
												<thead style="display:none;">
													<tr>
														<td colspan="6"><center><font size=5><h2>Historial de Notas</h2></font></center></td>				
													</tr>
													<tr>
														<td>CEDULA</td>
														<td colspan="3">:<?php echo $carnet." - ".$nombre;?></td>
														<td>Fecha</td>
														<td><?php echo " ".date("d-m-Y"); ?></td>
													</tr>
													<tr>
														<td>CARRERA</td>
														<td colspan="3">:<?php echo $carrera; ?></td>
														<td>HORA</td>
														<td><?php echo $date = date('h:i a', time()); ?></td>
													</tr>
													<tr>
														<td colspan="6"></td>				
													</tr>
												</thead>
												<thead>
													<tr class="table-primary">
														<th><font color="black">PERIODO</font></th>
														<th><font color="black">TURNO / GRUPO</font></th>
														<th><font color="black">FECHA</font></th>
														<th><font color="black">SIGLA</font></th>
														<th><font color="black">MATERIA</font></th>
														<th><font color="black">NOTA</font></th>
														<th><font color="black">ESTADO</font></th>
													</tr>
												</thead>
												<tbody>
													<?php
													// OBTENER LAS MATERIAS
													$cant_mat_vencidas = 0;
													$estado = "";
													$sql_materia = mysqli_query($con, "SELECT nombre_peri, nombre_gest, nombre_tur, sigla_mat, nombre_mat, nota_final_his, nombre_gru, nombre_tipmod, fecha_inicio_of 
														FROM tbl_historico, tbl_materia, tbl_oferta_materia, tbl_periodo, tbl_gestion, tbl_turno, tbl_grupo, tbl_tipo_modalidad 
														WHERE cod_oferta_materia_his = cod_oferta_materia AND cod_materia_of = cod_materia AND cod_periodo_of = cod_periodo 
														AND cod_tipomodalidad_of = cod_tipomodalidad AND cod_grupo_of = cod_grupo 
														AND cod_turno_of = cod_turno AND cod_gestion_peri = cod_gestion AND estado_his = 1 AND cod_estudiante_his = $cod_estudiante 
														ORDER BY cod_gestion, fecha_ini_peri ASC");
													$total_mat = mysqli_num_rows($sql_materia);
													while ($row_m = mysqli_fetch_array($sql_materia)) {
														if($row_m['nota_final_his'] > 50){
															$estado = "<font color='green'>APROBADA</font>";
															$cant_mat_vencidas++;
														}
														elseif($row_m['nota_final_his'] == "")
															$estado = "<font color='blue'>INSCRITA</font>";
														elseif($row_m['nota_final_his'] < 51)
															$estado = "<font color='red'>REPROBADA</font>";
														?>
														<tr>
															<td valign="middle"><?php echo $row_m['nombre_peri']."-".$row_m['nombre_gest']; ?></td>
															<td valign="middle"><?php echo $row_m['nombre_tur']." / ".$row_m['nombre_gru']." / ".$row_m['nombre_tipmod']; ?></td>
															<td valign="middle"><?php echo date_format(date_create($row_m['fecha_inicio_of']), 'd-m-Y'); ?></td>
															<td valign="middle"><?php echo $row_m['sigla_mat']; ?></td>
															<td valign="middle"><?php echo $row_m['nombre_mat']; ?></td>
															<td valign="middle"><?php echo $row_m['nota_final_his']; ?></td>
															<td valign="middle"><?php echo $estado; ?></td>
														</tr>
														<?php
													}
													?>
													<tr>
														<td align="center" colspan="6">MATERIAS CURSADAS: <?php echo $total_mat." - MATERIAS VENCIDAS: ".$cant_mat_vencidas; ?></td>
													</tr>
												</tbody>
											</table>
										</font>
									</div>
								<?php
								}
								?>
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