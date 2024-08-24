<?php
include ('../conf/funciones.php');
include("../conf/mysql.php");
$cod_usuario = 0;
if (verificar_usuario()){
	$cod_usuario = $_SESSION['cod_usuario'];
	$nombre_pagina = "articulo.php";
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
								<center><h2>Gestión de Artículos</h2></center>
								<a href="articulo_nuevo.php" class="btn btn-labeled btn-primary" type="button"><span class="btn-label"><i class="ion-plus-round"></i></span>Nuevo Registro</a>
								<div class="row">
									<div class="col-sm-6 text-right">
										<h5>Buscar:</h5>
									</div>
									<div class="col-sm-2">
										<select name="sucursal" id="sucursal" class="form-control select2-all">
											<?php
											$sql_sucursal = mysqli_query($con, "SELECT cod_sucursal, sigla_suc FROM tbl_sucursal, tbl_usuario_sucursal 
												WHERE cod_sucursal = cod_sucursal_ususuc AND cod_usuario_ususuc = $cod_usuario AND estado_ususuc = 1");
											while($row_us = mysqli_fetch_array($sql_sucursal)){
												?>
												<option value="<?php echo $row_us['cod_sucursal']; ?>"><?php echo $row_us['sigla_suc']; ?></option>
												<?php
											}
											?>
										</select>
									</div>
									<div class="col-sm-4">
										<input class="form-control" type="text" id="buscar_nombre" placeholder="...ARTÍCULO" value="">
									</div>
								</div>
								<br>
								<div class="no-more-tables">
									<table id="datatable_buscador" class="table table-striped table-sm">
										<thead>
											<tr align="center">
												<th>#</th>
												<th>ARTÍCULO</th>
												<th>PRECIO</th>
												<th>TIPO</th>
												<th>SUB SEDE</th>
												<th>MODIFICAR</th>
												<th>ELIMINAR</th>
											</tr>
										</thead>
										<tbody>
											<?php
											$cod_suc = 0;
											$sql_suc = mysqli_query($con, "SELECT cod_sucursal FROM tbl_sucursal, tbl_usuario_sucursal 
												WHERE cod_sucursal = cod_sucursal_ususuc AND cod_usuario_ususuc = $cod_usuario AND estado_ususuc = 1 ORDER BY cod_sucursal LIMIT 0, 1");
											while ($row_s = mysqli_fetch_array($sql_suc)) {
												$cod_suc = $row_s['cod_sucursal'];
											}

											$item = 1;
											$sql_articulo = mysqli_query($con, "SELECT cod_articulo, nombre_art, precio_art, nombre_tipoart, estado_art, sigla_suc 
												FROM tbl_articulo, tbl_tipo_articulo, tbl_sucursal 
												WHERE cod_sucursal = cod_sucursal_art AND cod_tipoarticulo_art = cod_tipoarticulo AND cod_sucursal_art = $cod_suc 
												ORDER BY cod_articulo DESC LIMIT 0, 10");
											while ($row_a = mysqli_fetch_array($sql_articulo)) {
												$det_estado = "<div class='text-primary'>HABILITADO</div>";
												if($row_a['estado_art'] == 0)
													$det_estado = "<div class='text-danger'>INHABILITADO</div>";
											?>
											<tr>
												<td data-title="#"><?php echo $item++; ?></td>
												<td data-title="ARTICULO"><?php echo $row_a['nombre_art']; ?></td>
												<td data-title="PRECIO"><?php echo $row_a['precio_art']; ?></td>
												<td data-title="TIPO"><?php echo $row_a['nombre_tipoart']; ?></td>
												<td data-title="SUB SEDE"><?php echo $row_a['sigla_suc']; ?></td>
												<td data-title="MODIFICAR" align="center"><a href="articulo_modificar.php?cod=<?php echo $row_a['cod_articulo']; ?>">Modificar</a></td>
												<td data-title="ELIMINAR" align="center"><a href="fun-del/articulo_delete.php?cod=<?php echo $row_a['cod_articulo']; ?>" onclick="return confirm('¿Estás seguro de cambiar este registro?')"><?php echo $det_estado; ?></a></td>
											</tr>
											<?php } ?>
										</tbody>
									</table>
									<div class="row" id="resultado"></div>
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
		<!-- Select2-->
    <script src="vendor/select2/dist/js/select2.js"></script>
    <!-- Clockpicker-->
    <script src="vendor/clockpicker/dist/bootstrap-clockpicker.js"></script>
    <!-- ColorPicker-->
    <script src="vendor/bootstrap-colorpicker/dist/js/bootstrap-colorpicker.js"></script>

		<script type="text/javascript">
			$(document).ready(function(){
				$('.select2-all').select2({
					placeholder: "..."
				});

				var nombre;
				$("#buscar_nombre").focus();
				$("#buscar_nombre").keyup(function(e){
					if($(this).val().length > 2){
						nombre = $("#buscar_nombre").val();
						sucursal = $("#sucursal").find(':selected').val();
						$.ajax({
							type: "POST",
							url: "../class/buscar_articulo.php",
							data: "nom="+nombre+"&suc="+sucursal,
							dataType: "html",
							beforeSend: function(){
								$("#datatable_buscador").empty();
								$("#resultado").html("<div class='text-center'><img width='10%' src='img/load.gif'/></div>");
							},
							error: function(){
								alert("Error al buscar");
							},
							success: function(data){
								$("#datatable_buscador").empty();
								$("#resultado").empty();
								$("#datatable_buscador").append(data);
							}
						});
					}
				});

				$("#sucursal").change(function(e){
					$('#buscar_nombre').val("");
					sucursal = $("#sucursal").find(':selected').val();
					$.ajax({
						type: "POST",
						url: "../class/buscar_articulo.php",
						data: "suc="+sucursal,
						dataType: "html",
						beforeSend: function(){
							$("#datatable_buscador").empty();
							$("#resultado").html("<div class='text-center'><img width='10%' src='img/load.gif'/></div>");
						},
						error: function(){
							alert("Error al buscar");
						},
						success: function(data){
							$("#datatable_buscador").empty();
							$("#resultado").empty();
							$("#datatable_buscador").append(data);
						}
					});
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