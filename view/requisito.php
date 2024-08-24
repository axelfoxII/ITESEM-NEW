<?php
include ('../conf/funciones.php');
include("../conf/mysql.php");
$cod_usuario = 0;
if (verificar_usuario()){
	$cod_usuario = $_SESSION['cod_usuario'];
	$nombre_pagina = "requisito.php";
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
								<center><h2>Gestión de Documentos de Estudiantes</h2></center>
								<br>
								<div class="row">
									<div class="col-sm-4 text-right">
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
									<div class="col-sm-2">
										<input class="form-control" type="text" id="buscar_nombre" placeholder="...Nombre" value="">
									</div>
									<div class="col-sm-2">
										<input class="form-control" type="text" id="buscar_apellido" placeholder="...Apellido" value="">
									</div>
									<div class="col-sm-2">
										<input class="form-control" type="text" id="buscar_carnet" placeholder="...C.I." value="">
									</div>
								</div>
								<br>
								<div class="no-more-tables">
									<table id="datatable_buscador" class="table table-striped table-sm">
										<thead>
											<tr align="center">
												<th>#</th>
												<th>ESTUDIANTE</th>
												<th>CARNET</th>
												<th>CARRERA</th>
												<th>SEB SEDE</th>
												<th>GESTIONAR</th>
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
											$sql_estudiante = mysqli_query($con, "SELECT cod_estudiante, nombre_per, apellido_per, carnet_per, complemento_carnet_per, nombre_car, sigla_suc 
												FROM tbl_estudiante, tbl_persona, tbl_carrera, tbl_sucursal 
												WHERE cod_sucursal = cod_sucursal_est AND cod_persona_est = cod_persona AND cod_carrera_est = cod_carrera AND estado_est = 1 
												AND cod_sucursal_est = $cod_suc ORDER BY cod_estudiante DESC LIMIT 0, 10");
											while ($row_e = mysqli_fetch_array($sql_estudiante)) {
												$complemento = "";
												if($row_e['complemento_carnet_per'] != "" && $row_e['complemento_carnet_per'] != NULL)
													$complemento = "-".$row_e['complemento_carnet_per'];
											?>
											<tr>
												<td data-title="#"><?php echo $item++; ?></td>
												<td data-title="ESTUDIANTE"><?php echo $row_e['nombre_per']." ".$row_e['apellido_per']; ?></td>
												<td data-title="CARNET"><?php echo $row_e['carnet_per'].$complemento; ?></td>
												<td data-title="CARRERA" align="center"><?php echo $row_e['nombre_car']; ?></td>
												<td data-title="SEB SEDE" align="center"><?php echo $row_e['sigla_suc']; ?></td>
												<td data-title="GESTIONAR" align="center"><a href="requisito_registro.php?cod=<?php echo $row_e['cod_estudiante']; ?>">Gestionar</a></td>
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
				$('.select2-all').select2({
					placeholder: "...SUB SEDE"
				});

				var nombre;
				var apellido;
				var carnet;
				$("#buscar_carnet").focus();
				$("#buscar_carnet").keyup(function(e){
					if($(this).val().length > 2){
						carnet = $("#buscar_carnet").val();
						sucursal = $("#sucursal").find(':selected').val();
						$.ajax({
							type: "POST",
							url: "../class/buscar_requisito.php",
							data: "car="+carnet+"&suc="+sucursal,
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

				$("#buscar_nombre, #buscar_apellido").keyup(function(e){
					if($(this).val().length > 2){
						nombre = $("#buscar_nombre").val();
						apellido = $("#buscar_apellido").val();
						sucursal = $("#sucursal").find(':selected').val();
						$.ajax({
							type: "POST",
							url: "../class/buscar_requisito.php",
							data: "nom="+nombre+"&ape="+apellido+"&suc="+sucursal,
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
					$('#buscar_apellido').val("");
					$('#buscar_carnet').val("");
					sucursal = $("#sucursal").find(':selected').val();
					$.ajax({
						type: "POST",
						url: "../class/buscar_requisito.php",
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