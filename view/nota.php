<?php
include ('../conf/funciones.php');
include("../conf/mysql.php");
date_default_timezone_set('America/La_Paz');
$cod_usuario = 0;
$cod_docente = 0;
if (verificar_usuario()){
	$cod_usuario = $_SESSION['cod_usuario'];
	$nombre_pagina = "nota.php";
	// Verificar el privilegio de la pagina
	$sql_pagina = mysqli_query($con, "SELECT cod_privilegio FROM tbl_submenu, tbl_privilegio, tbl_usuario 
		WHERE cod_submenu = cod_submenu_priv AND cod_perfil_priv = cod_perfil_us AND estado_priv = 1 
		AND cod_usuario = $cod_usuario AND enlace_subm = '$nombre_pagina'");
	if(mysqli_num_rows($sql_pagina) > 0){
		// Obtener el cod_docente
		
		$sql_docente = mysqli_query($con, "SELECT cod_docente FROM tbl_docente, tbl_usuario 
			WHERE cod_persona_doc = cod_persona_us AND cod_usuario = $cod_usuario");
		while ($row_d = mysqli_fetch_array($sql_docente)) {
			$cod_docente = $row_d['cod_docente'];
		}

		$det_docente = " AND cod_docente_of = ".$cod_docente;
		// Verificar si el usuario es Administrador
		$sql_privilegio = mysqli_query($con, "SELECT cod_usuario FROM tbl_usuario WHERE cod_usuario = $cod_usuario AND cod_perfil_us = 1");
		if(mysqli_num_rows($sql_privilegio) > 0){
			$det_docente = "";
		}
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
								<center><h2>Gestión de Registro de Notas</h2></center>
								<br>
								<h5 class="text-primary">Buscar:</h5>
								<div class="row">
									<div class="col-sm-2">
										<label class="col-form-label">SUCURSAL</label>
										<select name="sucursal" id="sucursal" class="form-control select2-all">
											<?php
											$sql_sucursal = mysqli_query($con, "SELECT cod_sucursal, sigla_suc FROM tbl_sucursal, tbl_usuario_sucursal 
												WHERE cod_sucursal = cod_sucursal_ususuc AND cod_usuario_ususuc = $cod_usuario AND estado_ususuc = 1 ORDER BY cod_sucursal");
											while($row_us = mysqli_fetch_array($sql_sucursal)){
												?>
												<option value="<?php echo $row_us['cod_sucursal']; ?>"><?php echo $row_us['sigla_suc']; ?></option>
												<?php
											}
											?>
										</select>
									</div>
									<div class="col-sm-2">
										<label class="col-form-label">GESTIÓN</label>
										<select class="form-control select2-all" name="gestion" id="gestion" style="width: 100%;" placeholder="..." required="">
											<option value=""></option>
											<?php
											$anho = date("Y");
											$cod_gestion = 0;
											$sql_gestion = mysqli_query($con, "SELECT cod_gestion, nombre_gest FROM tbl_gestion WHERE nombre_gest = '$anho'");
											while ($row_g = mysqli_fetch_array($sql_gestion)) {
												$cod_gestion = $row_g['cod_gestion'];
											}
											$sql_gestion = mysqli_query($con, "SELECT cod_gestion, nombre_gest FROM tbl_gestion");
											while ($row_g = mysqli_fetch_array($sql_gestion)) {
												?>
												<option value="<?php echo $row_g['cod_gestion']; ?>"><?php echo $row_g['nombre_gest']; ?></option>
												<?php
											}
											?>
										</select>
									</div>
									<div class="col-sm-3">
										<div class="row">
											<div class="col-sm-12">
												<label class="col-form-label">NIVEL</label>
												<select class="form-control select2-all" name="nivel" id="nivel" style="width: 100%;" placeholder="..." required="">
													<option value="">...</option>
													<?php
													$sql_tipo = mysqli_query($con, "SELECT cod_nivel, nombre_niv FROM tbl_nivel WHERE estado_niv = 1");
													while ($row_ti = mysqli_fetch_array($sql_tipo)) {
														?>
														<option value="<?php echo $row_ti['cod_nivel']; ?>"><?php echo $row_ti['nombre_niv']; ?></option>
														<?php
													}
													?>
												</select>
											</div>
											<div class="col-sm-12">
												<label class="col-form-label text-bold">CARRERA</label>
												<select class="form-control select2-all" id="carrera" name="carrera" placeholder="..." required="">
												</select>
											</div>
										</div>
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
									<div class="col-sm-3">
										<label class="col-form-label">MATERIA</label>
										<input type="text" id="materia" name="materia" class="form-control" placeholder="...">
									</div>
								</div>
								<br>
								<div class="no-more-tables">
									<table id="datatable_buscador" class="table table-striped table-sm">
										<thead>
											<tr align="center">
												<th>#</th>
												<th>MATERIA</th>
												<th>CARRERA</th>
												<th>TIPO</th>
												<th>PERIODO</th>
												<th>TURNO/GRUPO</th>
												<th>DOCENTE</th>
												<th>EST.</th>
												<th>INGRESAR</th>
											</tr>
										</thead>
										<tbody>
											<?php
											$item = 1;
											$cod_oferta = 0;
											$sql_oferta = mysqli_query($con, "SELECT cod_oferta_materia, sigla_mat, nombre_mat, sigla_car, resolucion_ministerial_car, sigla_titprof, 
												nombre_per, apellido_per, nombre_tur, nombre_gru, estado_of, nombre_gest, nombre_peri, nombre_tipper, nombre_tipmod 
												FROM tbl_oferta_materia, tbl_materia, tbl_carrera, tbl_docente, tbl_titulo_profesional, tbl_turno, tbl_persona, tbl_periodo, tbl_tipo_periodo, tbl_gestion, tbl_grupo, tbl_tipo_modalidad 
												WHERE cod_materia_of = cod_materia AND cod_carrera_mat = cod_carrera AND cod_docente_of = cod_docente 
												AND cod_tituloprofesional_doc = cod_tituloprofesional AND cod_persona_doc = cod_persona AND cod_turno_of = cod_turno AND cod_gestion = $cod_gestion 
												AND cod_tipomodalidad_of = cod_tipomodalidad $det_docente 
												AND cod_grupo_of = cod_grupo AND cod_periodo_of = cod_periodo AND cod_tipoperiodo_peri = cod_tipoperiodo AND cod_gestion_peri = cod_gestion AND estado_of = 1 
												ORDER BY cod_turno, cod_oferta_materia DESC LIMIT 0, 10");
											if(mysqli_num_rows($sql_oferta) > 0){
												while ($row_o = mysqli_fetch_array($sql_oferta)) {
													$cod_oferta = $row_o['cod_oferta_materia'];
													// Obtener los estudiantes registrados
													$cantidad = 0;
													$sql_historico = mysqli_query($con, "SELECT cod_historico FROM tbl_historico WHERE cod_oferta_materia_his = $cod_oferta AND estado_his = 1");
													$cantidad = mysqli_num_rows($sql_historico);
												?>
												<tr>
													<td data-title="#"><?php echo $item++; ?></td>
													<td data-title="MATERIA"><?php echo $row_o['sigla_mat']." - ".$row_o['nombre_mat']; ?></td>
													<td data-title="CARRERA"><?php echo $row_o['sigla_car']." - <small>".$row_o['resolucion_ministerial_car']."</small>"; ?></td>
													<td data-title="TIPO"><?php echo $row_o['nombre_tipper']; ?></td>
													<td data-title="PERIODO"><?php echo $row_o['nombre_gest']." - ".$row_o['nombre_peri']; ?></td>
													<td data-title="TURNO/GRUPO"><?php echo $row_o['nombre_tur']." / ".$row_o['nombre_gru']." / ".$row_o['nombre_tipmod']; ?></td>
													<td data-title="DOCENTE"><?php echo $row_o['sigla_titprof']." ".$row_o['nombre_per']." ".$row_o['apellido_per']; ?></td>
													<td data-title="EST." align="center"><?php echo $cantidad; ?></td>
													<td data-title="INGRESAR" align="center"><a href="nota_ingresar.php?cod=<?php echo $row_o['cod_oferta_materia']; ?>" target = "_blank">Ingresar</a></td>
												</tr>
												<?php } 
											}else{
												?>
												<tr>
													<td colspan="7" align="center">No se encontraron registros.</td>
												</tr>
												<?php
											} ?>
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

				$("#nivel").change(function(event){
    			$("#carrera").select2('val','...');
    			var cod = $("#nivel").find(':selected').val();
    			var suc = $("#sucursal").find(':selected').val();
    			$("#carrera").load('../class/buscar_select_carrera.php?cod='+cod+'&suc='+suc);	
    		});

    		$("#gestion, #carrera, #turno").change(function(event){
    			var ges = $("#gestion").find(':selected').val();
    			var suc = $("#sucursal").val();
    			var mat = $("#materia").val();
    			var usu = "<?php echo $cod_usuario; ?>";
    			var doc = "<?php echo $cod_docente; ?>";

    			var tur = $("#turno").val();
    			if(tur > 0 && tur != ""){
    				var tur = $("#turno").val();
    			}else{
    				var tur = "0";
    			}

    			var car = $("#carrera").val();
    			if(car > 0 && car != ""){
    				var car = $("#carrera").val();
    			}else{
    				var car = "0";
    			}

    			var mat = $("#materia").val();
    			if(mat.length > 0){
    				var mat = $("#materia").val();
    			}else{
    				var mat = "0";
    			}

    			if(ges > 0 && ges != ""){
	    			$.ajax({
		    			type: "POST",
		    			url: "../class/buscar_materia_nota.php",
		    			data: 'funcion=buscar_oferta&ges='+ges+'&suc='+suc+'&tur='+tur+'&usu='+usu+'&doc='+doc+'&car='+car+'&mat='+mat,
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
	    		}else{
	    			alert("SELECCIONAR UNA GESTION");
	    		}
    		});

    		$("#materia").keyup(function(e){
    			var ges = $("#gestion").find(':selected').val();
    			var suc = $("#sucursal").val();
    			var mat = $("#materia").val();
    			var usu = "<?php echo $cod_usuario; ?>";
    			var doc = "<?php echo $cod_docente; ?>";

    			var tur = $("#turno").val();
    			if(tur > 0 && tur != ""){
    				var tur = $("#turno").val();
    			}else{
    				var tur = "0";
    			}

    			var car = $("#carrera").val();
    			if(car > 0 && car != ""){
    				var car = $("#carrera").val();
    			}else{
    				var car = "0";
    			}

    			var mat = $("#materia").val();
    			if(mat.length > 0){
    				var mat = $("#materia").val();
    			}else{
    				var mat = "0";
    			}

    			if(ges > 0 && ges != ""){
	    			$.ajax({
		    			type: "POST",
		    			url: "../class/buscar_materia_nota.php",
		    			data: 'funcion=buscar_oferta&ges='+ges+'&suc='+suc+'&tur='+tur+'&usu='+usu+'&doc='+doc+'&car='+car+'&mat='+mat,
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
	    		}else{
	    			alert("SELECCIONAR UNA GESTION");
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