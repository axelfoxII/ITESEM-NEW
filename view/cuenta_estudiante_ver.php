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
								<h4><a href="cuenta_estudiante.php"><i class="ion-arrow-left-c"></i>Volver Atras</a></h4>

								<div id="expor" class="text-right">
									<form action="funciones/exportar_cuenta_estudiante.php" method="post" target="_blank" id="FormularioExportacion">
										<a href="" class = "botonExcel"><i class="ion-document-text " title="Exportar a Excel"></i> - Exportar a Excel</a>
										<input type="hidden" id="datos_a_enviar" name="datos_a_enviar" />
										<input type="hidden" id="nombre" name="nombre" value="kardex_estudiante" />
									</form>
								</div>
								<div id="resultadoBusqueda">
									<center><h4><font face="courier">Cuenta del Estudiante</font></h4></center>
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
										<font face="Tahoma" size="1">
											<table class="table table-striped tabla_striped">
													<tr style="font-size: 10px;">
														<td colspan="3"></td>
														<td colspan="3" align="right">Fecha:<?php echo " ".date("d-m-Y h:i a");?></td>
													</tr>
													<tr style="font-size: 10px;">
														<td colspan="3">C.I. : <?php echo $carnet." - ".$nombre; ?></td>
														<td colspan="3" align="right">Carrera : <?php echo $carrera; ?></td>
													</tr>
													<tr class="table-primary" style="text-align:center; font-size: 10px;">
														<th style="border-bottom: 1px solid black !important; border-top: 1px solid black !important;">
															<font color="black">N°</font>
														</th>
														<th style="border-bottom: 1px solid black !important; border-top: 1px solid black !important;">
															<font color="black">FECHA</font>
														</th>
														<th style="border-bottom: 1px solid black !important; border-top: 1px solid black !important;">
															<font color="black">DESCRIPCIÓN</font>
														</th>
														<th style="border-bottom: 1px solid black !important; border-top: 1px solid black !important;" align="right">
															<font color="black">DÉBITOS (Bs.)</font>
														</th>
														<th style="border-bottom: 1px solid black !important; border-top: 1px solid black !important;" align="right">
															<font color="black">ABONOS (Bs.)</font>
														</th>
														<th style="border-bottom: 1px solid black !important; border-top: 1px solid black !important;" align="right">
															<font color="black">SALDOS (Bs.)</font>
														</th>
													</tr>
												<tbody>
													<?php
													// OBTENER LAS MATERIAS
													$codigo = 0;
													$saldo = 0;
													$suma_debe = 0; $suma_haber = 0;
													$detalle = "";
													$sql_cuenta = mysqli_query($con, "SELECT cod_cuenta_estudiante, cod_venta_cuenta, cod_historico_cuenta, 
														precio_debe_cuenta, precio_haber_cuenta, nombre_tipocuenta, nombre_art, descripcion_cuenta, fecha_cuenta 
														FROM tbl_cuenta_estudiante, tbl_tipo_cuenta, tbl_articulo 
														WHERE cod_tipocuenta_cuenta = cod_tipocuenta AND cod_articulo_cuenta = cod_articulo AND estado_cuenta = 1 
														AND cod_estudiante_cuenta = $cod_estudiante ORDER BY fecha_cuenta");
													while ($row_c = mysqli_fetch_array($sql_cuenta)) {
														$suma_debe = $suma_debe + $row_c['precio_debe_cuenta'];
														$suma_haber = $suma_haber + $row_c['precio_haber_cuenta'];
														$saldo = $saldo - $row_c['precio_debe_cuenta'];
														$saldo = $saldo + $row_c['precio_haber_cuenta'];
														
														$detalle = "";
														if($saldo > 0)
															$detalle = 'color="green"';
														elseif($saldo < 0)
															$detalle = 'color="red"';

														if($row_c['cod_venta_cuenta'] > 0)
															$codigo = $row_c['cod_venta_cuenta'];
														elseif($row_c['cod_historico_cuenta'] > 0)
															$codigo = $row_c['cod_historico_cuenta'];
														else
															$codigo = $row_c['cod_cuenta_estudiante'];
														?>
														<tr style="font-size: 10px;">
															<td><?php echo $codigo; ?></td>
															<td><?php echo date_format(date_create($row_c['fecha_cuenta']), "d-m-Y"); ?></td>
															<td><?php echo $row_c['nombre_art'].$row_c['descripcion_cuenta']; ?></td>
															<td align="right"><?php echo $row_c['precio_debe_cuenta']; ?></td>
															<td align="right"><?php echo $row_c['precio_haber_cuenta']; ?></td>
															<td align="right"><?php echo "<font ".$detalle.">".number_format((abs ($saldo)), 2)."</font>"; ?></td>
														</tr>
														<?php
													}
													?>
													<tr style="font-size: 10px;">
														<td colspan="3"></td>
														<td style="border-top: 1px solid black !important;" align="right"><?php echo number_format((abs ($suma_debe)), 2); ?></td>
														<td style="border-top: 1px solid black !important;" align="right"><?php echo number_format((abs ($suma_haber)), 2); ?></td>
														<td style="border-top: 1px solid black !important;" align="right"><?php echo "<font ".$detalle.">".number_format((abs ($saldo)), 2)."</font>"; ?></td>
													</tr>
												</tbody>
											</table>
										</font>
										<?php
									}
									?>
								</div>
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