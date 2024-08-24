<?php
include ('../conf/funciones.php');
include("../conf/mysql.php");
$cod_usuario = 0;
if (verificar_usuario()){
	$cod_usuario = $_SESSION['cod_usuario'];
	$nombre_pagina = "anular_recibo.php";
	// Verificar el privilegio de la pagina
	$sql_pagina = mysqli_query($con, "SELECT cod_privilegio FROM tbl_submenu, tbl_privilegio, tbl_usuario 
		WHERE cod_submenu = cod_submenu_priv AND cod_perfil_priv = cod_perfil_us AND estado_priv = 1 
		AND cod_usuario = $cod_usuario AND enlace_subm = '$nombre_pagina'");
	if(mysqli_num_rows($sql_pagina) > 0){
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
								$fecha_venta = "";
								$nombre_suc = "";
								$ci_estudiante =""; $nombre_est = "";
								$sigla_plan = "";
								$sigla_car = "";
								$det_estado = "";
								$det_usuario = "";
								$tipo_pago = "";
								$info_suc = ""; $imagen_suc = "";
								$cod_sucursal_est = ""; $cod_carrera = "";
								if(isset($_GET['cod'])){
									$cod_venta = $_GET['cod'];

									$sql_venta = mysqli_query($con, "SELECT cod_venta, fecha_venta, nombre_suc, nombre_per, apellido_per, carnet_per, estado_venta, nombre_tipopago, direccion_suc, telefono_suc, imagen_suc, 
										sigla_car, sigla_plan, cod_sucursal, cod_carrera 
										FROM tbl_venta, tbl_sucursal, tbl_estudiante, tbl_persona, tbl_tipo_pago, tbl_carrera, tbl_plan 
										WHERE cod_venta = $cod_venta AND cod_sucursal_venta = cod_sucursal AND cod_estudiante_venta = cod_estudiante AND cod_tipopago_venta = cod_tipopago 
										AND cod_carrera_est = cod_carrera AND cod_plan_est = cod_plan AND cod_persona_est = cod_persona");
									while ($row_v = mysqli_fetch_array($sql_venta)) {
										$cod_sucursal_est = $row_v['cod_sucursal'];
										$cod_carrera = $row_v['cod_carrera'];
										$fecha_venta = $row_v['fecha_venta'];
										$nombre_suc = $row_v['nombre_suc'];
										$ci_estudiante = $row_v['carnet_per'];
										$nombre_est = $row_v['nombre_per']." ".$row_v['apellido_per'];
										$sigla_plan = $row_v['sigla_plan'];
										$sigla_car = $row_v['sigla_car'];
										$tipo_pago = $row_v['nombre_tipopago'];

										$info_suc = "Direccion: ".$row_v['direccion_suc']." -- Contacto: ".$row_v['telefono_suc']." -- Pagina Web: www.itesem.edu.bo";
										$imagen_suc = $row_v['imagen_suc'];

										//CAMBIAR LA IMAGEN 
										if($cod_sucursal_est == 3 && ($cod_carrera == 3 || $cod_carrera == 4 || $cod_carrera == 5)){
											$sql_suc_img = mysqli_query($con, "SELECT imagen_suc FROM tbl_sucursal WHERE cod_sucursal = 1");
											while ($row_is = mysqli_fetch_array($sql_suc_img)) {
												$imagen_suc = $row_is['imagen_suc'];
											}
										}

										$det_estado = "ACTIVO";
										$det_style = "";
										if($row_v['estado_venta'] == 0){
											$det_estado = "ANULADO";
											$det_style = "background-color: #DD9B9B";
										}

										// Obtener el codigo de la tabla
										$cod_tabla = 0;
										$sql_tabla = mysqli_query($con, "SELECT cod_tabla FROM tbl_tabla WHERE nombre_tabla = 'tbl_venta'");
										while ($row_ta = mysqli_fetch_array($sql_tabla)) {
											$cod_tabla = $row_ta['cod_tabla'];
										}

										// OBTENER EL USUARIO
										$sql_us = mysqli_query($con, "SELECT nombre_us FROM tbl_log, tbl_usuario WHERE cod_tabla_log = $cod_tabla AND codigo_log = $cod_venta AND cod_tipolog_log = 1 
											AND cod_usuario_log = cod_usuario");
										if(mysqli_num_rows($sql_us) > 0){
											while ($row_us = mysqli_fetch_array($sql_us)) {
												$det_usuario = $row_us['nombre_us'];
											}
										}
									}
								}

								$date = new DateTime($fecha_venta, new DateTimeZone('Europe/Madrid'));
								$date->format('Y-m-d H:i:sP');

								$date->setTimezone(new DateTimeZone('America/La_Paz')); 
								$fecha_rec =  $date->format('d-m-Y H:i');
								?>
								<h5><a href="anular_recibo.php"><i class="ion-arrow-left-c"></i>Volver Atras</a></h5>
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
												<input class="form-control" type="text" placeholder="...Estudiante" value="<?php echo $det_estado; ?>" style="<?php echo $det_style; ?>" disabled>
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
										<a href="fun-del/anular_recibo_delete.php?cod=<?php echo $cod_venta; ?>" class="btn btn-danger width-80 mb-xs" onclick="return confirm('¿Estás seguro de anular este recibo?')">Anular Recibo</a>
									</div>
									<div class="col-sm-2"></div>
									<div class="col-sm-6">
										<h4><u class="text-bold text-primary"><?php echo $tipo_pago; ?></u> : <?php echo number_format(($total_monto_detalle), 2, '.', ''); ?> Bs.</h4>
									</div>
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

		<script type="text/javascript">
			$(document).ready(function(){

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