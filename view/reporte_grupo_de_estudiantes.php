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
		<!-- Select2-->
		<link rel="stylesheet" href="vendor/select2/dist/css/select2.css">
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
								<center>
									<h2>Reporte de Grupos de Estudiantes</h2>
								</center>
								<div class="row">
									<div class="col-sm-2">
										<label class="col-form-label">SUB SEDE</label>
										<select name="sucursal" id="sucursal" class="form-control select2-all">
											<option value=""></option>
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
										<label class="col-form-label">TIPO PERIODO</label>
										<select class="form-control select2-all" name="tipo_periodo" id="tipo_periodo">
											<option value="">...</option>
											<?php
											$sql_tipo = mysqli_query($con, "SELECT cod_tipoperiodo, nombre_tipper FROM tbl_tipo_periodo WHERE estado_tipper = 1");
											while ($row_ti = mysqli_fetch_array($sql_tipo)) {
												?>
												<option value="<?php echo $row_ti['cod_tipoperiodo']; ?>"><?php echo $row_ti['nombre_tipper']; ?></option>
												<?php
											}
											?>
										</select>
									</div>
									<div class="col-sm-2">
										<label class="col-form-label">GESTIÓN</label>
										<select class="form-control select2-all" name="gestion" id="gestion">
											<?php
											$anho = date("Y");
											$cod_gestion = 0;
											$sql_gestion = mysqli_query($con, "SELECT cod_gestion, nombre_gest FROM tbl_gestion WHERE nombre_gest = '$anho'");
											while ($row_g = mysqli_fetch_array($sql_gestion)) {
												$cod_gestion = $row_g['cod_gestion'];
												?>
												<option value="<?php echo $row_g['cod_gestion']; ?>"><?php echo $row_g['nombre_gest']; ?></option>
												<?php
											}
											$sql_gestion = mysqli_query($con, "SELECT cod_gestion, nombre_gest FROM tbl_gestion");
											while ($row_ge = mysqli_fetch_array($sql_gestion)) {
												?>
												<option value="<?php echo $row_ge['cod_gestion']; ?>"><?php echo $row_ge['nombre_gest']; ?></option>
												<?php
											}
											?>
										</select>
									</div>
									<div class="col-sm-2">
										<label class="col-form-label">PERIODO</label>
										<select class="form-control select2-all" name="periodo" id="periodo">
											<option value="">...</option>
										</select>
									</div>
									<div class="col-sm-2">
										<label class="col-form-label">TURNO</label>
										<select class="form-control select2-all" name="turno" id="turno">
											<option value="">...</option>
											<?php
											$sql_turno = mysqli_query($con, "SELECT cod_turno, nombre_tur FROM tbl_turno");
											while ($row_t = mysqli_fetch_array($sql_turno)) {
												?>
												<option value="<?php echo $row_t['cod_turno']; ?>"><?php echo $row_t['nombre_tur']; ?></option>
												<?php
											}
											?>
										</select>
									</div>
									<div class="col-sm-2">
										<label class="col-form-label">GRUPO</label>
										<select class="form-control select2-all" name="grupo" id="grupo">
											<option value="">...</option>
										</select>
									</div>
								</div>
								<br>
								<div class="no-more-tables">
									<table id="datatable_buscador" class="table table-striped table-sm">
										<thead>
											<tr align="center">
												<th>#</th>
												<th>GRUPO</th>
												<th>CARRERA</th>
												<th>AULA</th>
												<th>REG.</th>
												<th>PAGOS</th>
												<th>MATERIAS</th>
											</tr>
										</thead>
										<tbody>
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
		<!-- Select2-->
		<script src="vendor/select2/dist/js/select2.js"></script>
		<!-- jQuery Knob charts-->
		<script src="vendor/jquery-knob/js/jquery.knob.js"></script>
		<!-- App script-->
		<script src="js/app.js"></script>

		<script type="text/javascript">
			$(document).ready(function(){
				$('.select2-all').select2({
					placeholder: "..."
				});

				$("#tipo_periodo").change(function(event){
    			$("#periodo").select2('val','...');
    			var tipper = $("#tipo_periodo").find(':selected').val();
    			var gest = $("#gestion").find(':selected').val();
    			$("#periodo").load('../class/buscar_select.php?tipo=periodo&tipper='+tipper+'&gest='+gest);
    		});

    		$("#gestion").change(function(event){
    			$("#periodo").select2('val','...');
    			$("#grupo").select2('val','...');
    			$("#datatable_buscador").empty();
    			var tipper = $("#tipo_periodo").find(':selected').val();
    			var gest = $("#gestion").find(':selected').val();
    			$("#periodo").load('../class/buscar_select.php?tipo=periodo&tipper='+tipper+'&gest='+gest);
    		});

    		$("#turno").change(function(event){
    			$("#grupo").select2('val','...');
    			var tur = $("#turno").find(':selected').val();
    			$("#grupo").load('../class/buscar_select.php?tipo=grupo_of&tur='+tur);
    		});

				$("#periodo, #turno, #grupo").change(function(event){
    			var per = $("#periodo").find(':selected').val();
    			var suc = $("#sucursal").val();

    			var tur = $("#turno").val();
    			if(tur > 0 && tur != ""){
    				var tur = $("#turno").val();
    			}else{
    				var tur = "0";
    			}

    			var gru = $("#grupo").val();
    			if(gru > 0 && gru != ""){
    				var gru = $("#grupo").val();
    			}else{
    				var gru = "0";
    			}

    			if(per > 0 && per != ""){
	    			$.ajax({
		    			type: "POST",
		    			url: "../class/buscar_reporte_grupo_de_estudiantes.php",
		    			data: 'per='+per+'&suc='+suc+'&tur='+tur+'&gru='+gru,
		    			dataType: "html",
		    			beforeSend: function(){
								$("#datatable_buscador").empty();
								$("#resultado").html("<div class='text-center'><img width='10%' src='img/load.gif'/></div>");
							},
		    			error: function(){
		    				alertify.notify('<div class="text-center text-white">ERROR AL BUSCAR.!</div>','error', 15).dismissOthers();
		    			},
		    			success: function(data){
		    				$("#datatable_buscador").empty();
								$("#resultado").empty();
								$("#datatable_buscador").append(data);

							  $(window).resize(function() {
						        var bodyheight = $(this).height();
						        $(".section-container").height(bodyheight);
						    }).resize();

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