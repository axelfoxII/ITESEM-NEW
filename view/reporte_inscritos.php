<?php
include ('../conf/funciones.php');
include("../conf/mysql.php");
$cod_usuario = 0;
if (verificar_usuario()){
	$cod_usuario = $_SESSION['cod_usuario'];
	$nombre_pagina = "reporte_inscritos.php";
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
								<center><h2 class="text-primary">Reporte de Nuevos Inscritos</h2></center>
								<br>
								<div id="buscadores">
									<div class="row">
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
									</div>
									<div class="row">
										<div class="col-sm-2">
											<label class="col-form-label">MES</label>
											<select class="form-control select2-all" id="mes" name="mes">
												<option value=""></option>
												<option value="1">Enero</option>
												<option value="2">Febreo</option>
												<option value="3">Marzo</option>
												<option value="4">Abril</option>
												<option value="5">Mayo</option>
												<option value="6">Junio</option>
												<option value="7">Julio</option>
												<option value="8">Agosto</option>
												<option value="9">Septiembre</option>
												<option value="10">Octubre</option>
												<option value="11">Noviembre</option>
												<option value="12">Diciembre</option>
												<option value="todos">* TODOS *</option>
											</select>
										</div>
										<div class="col-sm-2">
											<label class="col-form-label">GESTIÓN</label>
											<select class="form-control select2-all" id="gestion" name="gestion">
												<option value=""></option>
												<?php
												$sql_gestion = mysqli_query($con, "SELECT * FROM tbl_gestion");
												while ($row_g = mysqli_fetch_array($sql_gestion)) {
													?>
													<option value="<?php echo $row_g['nombre_gest']; ?>"><?php echo $row_g['nombre_gest']; ?></option>
													<?php
												}
												?>
											</select>
										</div>
										<div class="col-sm-3">
											<label class="col-form-label">NIVEL</label>
											<select class="form-control select2-all" id="nivel" name="nivel">
												<option value=""></option>
												<?php
												$sql_nivel = mysqli_query($con, "SELECT * FROM tbl_nivel WHERE estado_niv = 1");
												while ($row_ni = mysqli_fetch_array($sql_nivel)) {
													?>
													<option value="<?php echo $row_ni['cod_nivel']; ?>"><?php echo $row_ni['nombre_niv']; ?></option>
													<?php
												}
												?>
												<option value="todos">* TODOS *</option>
											</select>
										</div>
										<div class="col-sm-5">
											<label class="col-form-label">CARRERA</label>
											<select class="form-control select2-all" id="carrera" name="carrera">
												<option value=""></option>
											</select>
										</div>
									</div>
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
		<!-- jQuery Localize-->
		<script src="vendor/jquery-localize/dist/jquery.localize.js"></script>
		<!-- Select2-->
		<script src="vendor/select2/dist/js/select2.js"></script>
		<!-- Clockpicker-->
		<script src="vendor/clockpicker/dist/bootstrap-clockpicker.js"></script>
		<!-- ColorPicker-->
		<script src="vendor/bootstrap-colorpicker/dist/js/bootstrap-colorpicker.js"></script>
		<!-- App script-->
		<script src="js/app.js"></script>


<script
src="vendor/chart/chart.js">
</script>


		<script type="text/javascript">
			$(document).ready(function(){
				$('.select2-all').select2({
					placeholder: "..."
				});

				var mes;
				var gestion;
				var carrera;

				$("#mes, #gestion, #carrera").change(function(event){
					var suc = $("#sucursal").val();
					var niv = $("#nivel").val();
					mes = $("#mes").val();
					gestion = $("#gestion").val();
					carrera = $("#carrera").val();
					if(mes != "" && gestion != "" && carrera != null && carrera != ""){
						$.ajax({
							type: "POST",
							url: "../class/buscar_reporte_inscritos.php",
							data: "mes="+mes+"&ges="+gestion+"&car="+carrera+"&suc="+suc+"&niv="+niv,
							dataType: "html",
							beforeSend: function(){
								$("#resultado").html("");
								$("#resultado").html("<div class='text-center'><img width='10%' src='img/load.gif'/></div>");
								$("#nivel").focus();
							},
							error: function(){
								alert("Error al buscar");
							},
							success: function(data){
								$("#resultado").html("");
								$("#resultado").append(data);
								$("#buscadores").hide();
							}
						});
					}
				});

				$("#nivel").change(function(event){
					$("#carrera").select2('val','...');
					var cod = $("#nivel").find(':selected').val();
					var suc = $("#sucursal").find(':selected').val();
					$("#carrera").load('../class/buscar_select_carrera.php?cod='+cod+'&suc='+suc+'&tipo=rep');	
				});

				$("#sucursal").change(function(event){
    			$("#carrera").select2('val','...');
    			var cod = $("#nivel").find(':selected').val();
    			var suc = $("#sucursal").find(':selected').val();
    			$("#carrera").load('../class/buscar_select_carrera.php?cod='+cod+'&suc='+suc+'&tipo=rep');	
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