<?php
include ('../conf/funciones.php');
include("../conf/mysql.php");
$cod_usuario = 0;
if (verificar_usuario()){
	$cod_usuario = $_SESSION['cod_usuario'];
	$nombre_pagina = "inventario.php";
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
								<?php
								$cod_articulo = 0;
								if (isset($_GET['cod'])) {
									$cod_articulo = $_GET['cod'];

									$nombre = "";
									$precio = "";
									$cod_tipoarticulo = 0;
									$sucursal = 0;
									$sql_articulo = mysqli_query($con, "SELECT * FROM tbl_articulo WHERE cod_articulo = $cod_articulo");
									while ($row_a = mysqli_fetch_array($sql_articulo)) {
										$nombre = $row_a['nombre_art'];
										$precio = $row_a['precio_art'];
										$cod_tipoarticulo = $row_a['cod_tipoarticulo_art'];
										$sucursal = $row_a['cod_sucursal_art'];
									}
								}
								?>
								<h4><a href="inventario.php"><i class="ion-arrow-left-c"></i>Volver Atras</a></h4>
								<center><h3>Detalle del Artículo</h3></center>
									<fieldset>
										<div class="form-group row">
											<div class="col-sm-1"></div>
											<div class="col-sm-7">
												<label class="col-form-label">NOMBRE</label>
												<input class="form-control" type="text" name="nombre" placeholder="..." required="" value="<?php echo $nombre; ?>" disabled>
											</div>
											<div class="col-sm-3">
												<label class="col-form-label">PRECIO</label>
												<input class="form-control" type="number" name="precio" placeholder="0.00" required="" onKeyPress="return numeros(event)" value="<?php echo $precio; ?>" disabled>
											</div>
										</div>
									</fieldset>

									<fieldset>
										<div class="form-group row">
											<div class="col-sm-1"></div>
											<div class="col-sm-4">
												<label class="col-form-label">TIPO ARTÍCULO</label>
												<select class="form-control select2-all" name="tipo_articulo" placeholder="..." required="" disabled>
													<?php
													$sql_tipo = mysqli_query($con, "SELECT cod_tipoarticulo, nombre_tipoart FROM tbl_tipo_articulo 
														WHERE cod_tipoarticulo = $cod_tipoarticulo");
													while ($row_ti = mysqli_fetch_array($sql_tipo)) {
														?>
														<option value="<?php echo $row_ti['cod_tipoarticulo']; ?>"><?php echo $row_ti['nombre_tipoart']; ?></option>
														<?php
													}
													$sql_tipo = mysqli_query($con, "SELECT cod_tipoarticulo, nombre_tipoart FROM tbl_tipo_articulo 
														WHERE cod_tipoarticulo != $cod_tipoarticulo");
													while ($row_ti = mysqli_fetch_array($sql_tipo)) {
														?>
														<option value="<?php echo $row_ti['cod_tipoarticulo']; ?>"><?php echo $row_ti['nombre_tipoart']; ?></option>
														<?php
													}
													?>
												</select>
											</div>
											<div class="col-sm-6">
												<label class="col-form-label">SUB SEDE</label>
												<select class="form-control select2-all" name="sucursal" placeholder="..." required="" disabled>
													<?php
													$sql_sucursal = mysqli_query($con, "SELECT cod_sucursal, nombre_suc FROM tbl_sucursal 
														WHERE cod_sucursal = $sucursal");
													while ($row_s = mysqli_fetch_array($sql_sucursal)) {
														?>
														<option value="<?php echo $row_s['cod_sucursal']; ?>"><?php echo $row_s['nombre_suc']; ?></option>
														<?php
													}

													$sql_sucursal = mysqli_query($con, "SELECT cod_sucursal, nombre_suc FROM tbl_sucursal 
														WHERE estado_suc = 1 AND cod_sucursal != $sucursal");
													while ($row_s = mysqli_fetch_array($sql_sucursal)) {
														?>
														<option value="<?php echo $row_s['cod_sucursal']; ?>"><?php echo $row_s['nombre_suc']; ?></option>
														<?php
													}
													?>
												</select>
											</div>
										</div>
									</fieldset>
									<?php
									$cant_positivo = 0;
									$cant_negativo = 0;
									// CANTIDAD ARTICULO
									$sql_inventario = mysqli_query($con, "SELECT cod_articulo_inv, SUM(cantidad_inv) AS cantidad FROM tbl_inventario 
										WHERE cod_tipoinventario_inv = 1 AND cod_articulo_inv = $cod_articulo AND estado_inv = 1 GROUP BY cod_articulo_inv");
									if(mysqli_num_rows($sql_inventario) > 0){
										while ($row_in = mysqli_fetch_array($sql_inventario)) {
											$cant_positivo = $row_in['cantidad'];
										}
									}
									$sql_inventario = mysqli_query($con, "SELECT cod_articulo_inv, SUM(cantidad_inv) AS cantidad FROM tbl_inventario 
										WHERE cod_tipoinventario_inv IN (2, 3) AND cod_articulo_inv = $cod_articulo AND estado_inv = 1 GROUP BY cod_articulo_inv");
									if(mysqli_num_rows($sql_inventario) > 0){
										while ($row_in = mysqli_fetch_array($sql_inventario)) {
											$cant_negativo = $row_in['cantidad'];
										}
									}
									?>
									<fieldset>
										<div class="form-group row">
											<div class="col-sm-3"></div>
											<div class="col-sm-2">
												<label class="col-form-label">CANT. ARTICULOS</label>
												<input class="form-control" type="text" name="nombre" placeholder="..." required="" value="<?php echo $cant_positivo; ?>" disabled>
											</div>
											<div class="col-sm-2">
												<label class="col-form-label">CANT. SALIDAS</label>
												<input class="form-control" type="text" name="nombre" placeholder="..." required="" value="<?php echo $cant_negativo; ?>" disabled>
											</div>
											<div class="col-sm-2">
												<label class="col-form-label">DISPONIBLES</label>
												<input class="form-control" type="text" name="nombre" placeholder="..." required="" value="<?php echo $cant_positivo - $cant_negativo; ?>" disabled>
											</div>
										</div>
									</fieldset>

								<hr><br>
								<center><h3>Registrar el Evento del Artículo</h3></center>
								<form action="inventario_guardar.php" method="POST">
									<input type="hidden" name="cod_articulo" value="<?php echo $cod_articulo; ?>">
									<input type="hidden" name="tipo" value="ingresar">
									<fieldset>
										<div class="form-group row">
											<div class="col-sm-2"></div>
											<div class="col-sm-2">
												<label class="col-form-label">TIPO DE REGISTRO</label>
												<select class="form-control select2-all" name="tipo_inventario" placeholder="..." required="">
													<option value=""></option>
													<?php
													$sql_tipo = mysqli_query($con, "SELECT cod_tipoinventario, nombre_tipinv FROM tbl_tipo_inventario 
														WHERE cod_tipoinventario IN (1, 3)");
													while ($row_ti = mysqli_fetch_array($sql_tipo)) {
														?>
														<option value="<?php echo $row_ti['cod_tipoinventario']; ?>"><?php echo $row_ti['nombre_tipinv']; ?></option>
														<?php
													}
													?>
												</select>
											</div>
											<div class="col-sm-2">
												<label class="col-form-label">CANTIDAD DE ART.</label>
												<input type="text" class="form-control" name="cantidad" value="" placeholder="...">
											</div>
											<div class="col-sm-4">
												<label class="col-form-label">OBSERVACIÓN</label>
												<input type="text" class="form-control" name="observacion" value="" placeholder="...">
											</div>
										</div>
									</fieldset>
									<div class="text-center">
										<button class="btn btn-primary pull-right" type="submit">Registrar Ingreso</button>
									</div>
								</form>

								<hr><br>
								<div class="no-more-tables">
									<table id="datatable_buscador" class="table table-striped table-sm">
										<thead>
											<tr align="center">
												<th>#</th>
												<th>TIPO DE EVENTO</th>
												<th>FECHA REGISTRO</th>
												<th>CANTIDAD</th>
												<th>OBSERVACIÓN</th>
												<th>ACTUALIZAR</th>
												<th>ELIMINAR</th>
											</tr>
										</thead>
										<tbody>
											<?php
											$item = 1;
											$cod_inventario = 0;
											$sql_inventario = mysqli_query($con, "SELECT cod_inventario, nombre_tipinv, fecha_inv, cantidad_inv, observacion_inv 
												FROM tbl_inventario, tbl_tipo_inventario 
												WHERE cod_tipoinventario_inv = cod_tipoinventario AND cod_articulo_inv = $cod_articulo AND estado_inv = 1 
												AND cod_tipoinventario IN (1, 3) ORDER BY fecha_inv DESC");
											while ($row_in = mysqli_fetch_array($sql_inventario)) {
												$cod_inventario = $row_in['cod_inventario'];
												?>
												<form action="inventario_guardar.php" method="POST">
													<input type="hidden" name="cod_articulo" value="<?php echo $cod_articulo; ?>">
													<input type="hidden" name="cod_inventario" value="<?php echo $cod_inventario; ?>">
													<input type="hidden" name="tipo" value="actualizar">
													<tr>
														<td data-title="#"><?php echo $item++; ?></td>
														<td data-title="TIPO DE EVENTO"><?php echo $row_in['nombre_tipinv']; ?></td>
														<td data-title="FECHA REGISTRO"><?php echo date_format(date_create($row_in['fecha_inv']), 'm-d-Y H:i'); ?></td>
														<td data-title="CANTIDAD"><input type="text" name="cantidad" class="form-control" value="<?php echo $row_in['cantidad_inv']; ?>"></td>
														<td data-title="OBSERVACIÓN"><?php echo $row_in['observacion_inv']; ?></td>
														<td data-title="ACTUALIZAR" align="center"><button class="btn btn-primary pull-right" type="submit">Actualizar</button></td>
														<td data-title="ELIMINAR" align="center"><a class="btn btn-danger" href="fun-del/inventario_delete.php?cod=<?php echo $row_in['cod_inventario']; ?>&cod_art=<?php echo $cod_articulo; ?>" onclick="return confirm('¿Estás seguro de cambiar este registro?')">Eliminar</a></td>
													</tr>
												</form>
												<?php
											}
											?>
										</tbody>
									</table>
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
    	$(document).ready(function(){
    		$('.select2-all').select2({
					placeholder: "..."
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