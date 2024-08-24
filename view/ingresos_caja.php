<?php
include ('../conf/funciones.php');
include("../conf/mysql.php");
$cod_usuario = 0;
if (verificar_usuario()){
	$cod_usuario = $_SESSION['cod_usuario'];
	$nombre_pagina = "ingresos_caja.php";
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
								<div class="row">
									<div class="col-sm-4"></div>
									<div class="col-sm-2">
										<label class="col-form-label text-bold">Sucursal</label>
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
										<label class="col-form-label text-bold">Nro Recibo</label>
										<input class="form-control" type="text" id="buscar_nro_recibo" placeholder="...Nro Recibo" value="">
									</div>
									<div class="col-sm-2">
										<label class="col-form-label text-bold">Carnet</label>
										<input class="form-control" type="text" id="buscar_carnet" placeholder="...C.I." value="">
									</div>
									<div class="col-sm-2">
										<label class="col-form-label text-bold">Fecha</label>
										<div class="rel-wrapper ui-datepicker ui-datepicker-popup dp-theme-primary" id="example-datepicker-container-4">
                      <input class="form-control" id="example-datepicker-4" type="text" data-date="" placeholder="...Fecha">
                    </div>
									</div>
								</div>
								<br>
								<div class="no-more-tables">
									<table class="table table-striped table-bordered table-sm">
										<thead>
											<tr align="center" class="bg-primary">
												<th>Nro. RECIBO</th>
												<th>MONTO Bs.</th>
												<th>NOMBRE ESTUDIANTE</th>
												<th>CARNET</th>
												<th>FECHA</th>
												<th>USUARIO</th>
												<th>VER</th>
												<th>ESTADO</th>
											</tr>
										</thead>
										<tbody id="datatable_buscador">
											<tr>
												<td colspan="8" align="center">No hay resultados en la tabla.</td>
											</tr>
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
				$("#buscar_nro_recibo").focus();
				$("#buscar_nro_recibo").keyup(function(e){
					if($(this).val() !== ""){
						nro_rec = $("#buscar_nro_recibo").val();
						sucursal = $("#sucursal").find(':selected').val();
						$.ajax({
							type: "POST",
							url: "../class/buscar_ingresos.php",
							data: "tipo=nro_recibo&nro_rec="+nro_rec+"&suc="+sucursal,
							dataType: "html",
							beforeSend: function(){
								$("#datatable_buscador").empty();
								$("#resultado").html("<tr><td colspan='8' align='center'><img width='10%' src='img/load.gif'/></td></tr>");
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

				$("#buscar_carnet").keyup(function(e){
					if($(this).val().length > 2){
						carnet = $("#buscar_carnet").val();
						sucursal = $("#sucursal").find(':selected').val();
						$.ajax({
							type: "POST",
							url: "../class/buscar_ingresos.php",
							data: "tipo=carnet&carnet="+carnet+"&suc="+sucursal,
							dataType: "html",
							beforeSend: function(){
								$("#datatable_buscador").empty();
								$("#resultado").html("<tr><td colspan='8' align='center'><img width='10%' src='img/load.gif'/></td></tr>");
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

				$("#example-datepicker-4").change(function(e){
					if($(this).val().length > 2){
						fecha = $("#example-datepicker-4").val();
						sucursal = $("#sucursal").find(':selected').val();
						$.ajax({
							type: "POST",
							url: "../class/buscar_ingresos.php",
							data: "tipo=fecha&fecha="+fecha+"&suc="+sucursal,
							dataType: "html",
							beforeSend: function(){
								$("#datatable_buscador").empty();
								$("#resultado").html("<tr><td colspan='8' align='center'><img width='10%' src='img/load.gif'/></td></tr>");
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