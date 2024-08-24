<?php
include ('../conf/funciones.php');
include("../conf/mysql.php");
$cod_usuario = 0;
if (verificar_usuario()){
	$cod_usuario = $_SESSION['cod_usuario'];
	$nombre_pagina = "reporte_ingresos_caja.php";
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
								<center><h2>Reporte de Ingresos de Caja</h2></center>
								<div class="row">
									<div class="col-sm-4 text-right">
										<h5 class="text-primary">Buscar:</h5>
									</div>
									<div class="col-sm-3">
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
									<div class="col-sm-5">
										<div class="rel-wrapper ui-datepicker ui-datepicker-popup dp-theme-primary" id="example-datepicker-container-5">
											<div class="input-daterange" id="example-datepicker-5">
												<div class="form-group row">
													<div class="col-6">
														<input class="form-control" type="text" name="fecha_inicio" id="fecha_inicio" value="" placeholder="... Desde" autocomplete="off">
													</div>
													<div class="col-6">
														<input class="form-control" type="text" name="fecha_fin" id="fecha_fin" value="" placeholder="... Hasta" autocomplete="off">
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
								<div id="expor" class="text-right">
									<form action="funciones/exportar_cuenta_estudiante.php" method="post" target="_blank" id="FormularioExportacion">
										<a href="" class = "botonExcel"><i class="ion-document-text " title="Exportar a Excel"></i> - Exportar a Excel</a>
										<input type="hidden" id="datos_a_enviar" name="datos_a_enviar" />
										<input type="hidden" id="nombre" name="nombre" value="Reporte_Ingresos_Caja" />
									</form>
								</div>
								<br>
								<div id="resultadoBusqueda">
									<table id="datatable_buscador" class="table table-bordered table-sm" border="1">
										<thead>
											<tr align="center" class="table-primary">
												<th>USUARIO</th>
												<th>COD</th>
												<th>ESTUDIANTE</th>
												<th>PLAN</th>
												<th>CAR</th>
												<th>ARTIUCLO</th>
												<th>TIPO PAGO</th>
												<th>MONTO Bs.</th>
											</tr>
										</thead>
										<tbody>
										</tbody>
									</table>
								</div>
								<div id="resultado"></div>
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

		<script language="JavaScript" type="text/JavaScript">
    	$(document).ready(function(){
    		$(".botonExcel").click(function(event) {
					$("#datos_a_enviar").val( $("<div>").append( $("#resultadoBusqueda").eq(0).clone()).html());
					$("#FormularioExportacion").submit();
				});

    		$('.select2-all').select2({
					placeholder: "...SUB SEDE"
				});

    		$("#fecha_inicio, #fecha_fin").bind("keyup change", function(e) {
    			if($(this).val().length > 2){
    				sucursal = $("#sucursal").find(':selected').val();
    				fecha_inicio = $("#fecha_inicio").val();
						fecha_fin = $("#fecha_fin").val();
						$.ajax({
							type: "POST",
							url: "../class/buscar_ingresos_caja.php",
							data: "ini="+fecha_inicio+"&fin="+fecha_fin+"&suc="+sucursal,
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