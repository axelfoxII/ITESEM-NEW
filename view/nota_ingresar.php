<?php
include ('../conf/funciones.php');
include("../conf/mysql.php");
date_default_timezone_set('America/La_Paz');
$cod_usuario = 0;
if (verificar_usuario()){
	$cod_usuario = $_SESSION['cod_usuario'];
	$nombre_pagina = "nota.php";
	// Verificar el privilegio de la pagina
	$sql_pagina = mysqli_query($con, "SELECT cod_privilegio FROM tbl_submenu, tbl_privilegio, tbl_usuario 
		WHERE cod_submenu = cod_submenu_priv AND cod_perfil_priv = cod_perfil_us AND estado_priv = 1 
		AND cod_usuario = $cod_usuario AND enlace_subm = '$nombre_pagina'");
	if(mysqli_num_rows($sql_pagina) > 0){
		// Obtener el cod_docente
		$cod_docente = 0;
		$sql_docente = mysqli_query($con, "SELECT cod_docente FROM tbl_docente, tbl_usuario 
			WHERE cod_persona_doc = cod_persona_us AND cod_usuario = $cod_usuario");
		while ($row_d = mysqli_fetch_array($sql_docente)) {
			$cod_docente = $row_d['cod_docente'];
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
								<h4><a href="nota.php"><i class="ion-arrow-left-c"></i>Volver Atras</a></h4>
								<?php
								if(isset($_GET['cod']) && $_GET['cod'] != ""){
									$cod_oferta = $_GET['cod'];

									// Obtener los datos de la oferta de materia
									$sql_oferta = mysqli_query($con, "SELECT nombre_mat, sigla_mat, nombre_tur, nombre_gru, nombre_tipmod, nombre_peri, nombre_gest, sigla_titprof, nombre_per, apellido_per 
										FROM tbl_oferta_materia, tbl_materia, tbl_carrera, tbl_turno, tbl_grupo, tbl_periodo, tbl_gestion, tbl_docente, tbl_persona, tbl_titulo_profesional, tbl_tipo_modalidad 
										WHERE cod_materia_of = cod_materia AND cod_carrera_mat = cod_carrera AND cod_turno_of = cod_turno AND cod_grupo_of = cod_grupo AND cod_periodo_of = cod_periodo
										AND cod_gestion_peri = cod_gestion AND cod_docente_of = cod_docente AND cod_persona_doc = cod_persona AND cod_tituloprofesional_doc = cod_tituloprofesional 
										AND cod_tipomodalidad_of = cod_tipomodalidad AND estado_of = 1 AND estado_of = 1 AND cod_oferta_materia = $cod_oferta LIMIT 0,1");
									while ($row = mysqli_fetch_array($sql_oferta)) {
										?>
										<form id="validation-form" method="POST" action="nota_guardar.php" data-parsley-priority-enabled="false" novalidate="novalidate" enctype="multipart/form-data">
											<input type="hidden" name = "cod_oferta_materia" value = "<?php echo $cod_oferta; ?>">
											<hr>
											<div class="row">
												<div class="col-md-5">
													<label class="control-label"><b>MATERIA: </b></label>
													<font><?php echo $row['sigla_mat'].' - '.$row['nombre_mat']; ?></font>
												</div>
												<div class="col-md-7">
													<label class="control-label"><b>DOCENTE: </b></label>
													<font><?php echo $row['sigla_titprof'].' '.$row['nombre_per'].' '.$row['apellido_per']; ?></font>
												</div>
											</div>
											<div class="row">
												<div class="col-md-5"></div>
												<div class="col-md-3">
													<label class="control-label"><b>TURNO: </b></label>
													<font><?php echo $row['nombre_tur']." / ".$row['nombre_gru']." / ".$row['nombre_tipmod']; ?></font>
												</div>
												<div class="col-md-4">
													<label class="control-label"><b>PERIODO / GESTIÓN: </b></label>
													<font><?php echo $row['nombre_gest']." - ".$row['nombre_peri']; ?></font>
												</div>
											</div>
											<hr>
											<table class="table table-striped">
												<thead>
													<tr class="table-primary">
														<th>N°</th>
														<th>NOMBRE</th>
														<th>CARNET</th>
														<th>NOTA UNO</th>
														<th>NOTA DOS</th>
														<th>NOTA TRES</th>
														<th>NOTA FINAL</th>
													</tr>
												</thead>
												<tbody>
													<?php
													$item = 0;
													$cod_historico = 0;
													// OBTENER LA LISTA DE ESTUDIANTES REGISTRADOS
													$sql_estudiantes = mysqli_query($con, "SELECT cod_historico, nombre_per, apellido_per, carnet_per, nota_final_his FROM tbl_historico, tbl_estudiante, tbl_persona 
														WHERE cod_estudiante_his = cod_estudiante AND cod_persona_est = cod_persona AND cod_oferta_materia_his = $cod_oferta AND estado_his = 1 
														ORDER BY apellido_per, nombre_per");
													if(mysqli_num_rows($sql_estudiantes) > 0){
														while ($row_e = mysqli_fetch_array($sql_estudiantes)) {
															$item++;
															$cod_historico = $row_e['cod_historico'];

															// OBTENER LAS NOTAS DE LA TABLA tbl_nota
															$n1 = ""; $n2 = ""; $n3 = "";
															$required1 = ""; $required2 = ""; $required3 = "";
															$sql_nota = mysqli_query($con, "SELECT nota_uno_not, nota_dos_not, nota_tres_not FROM tbl_nota WHERE cod_historico_not = $cod_historico");
															if(mysqli_num_rows($sql_nota) > 0){
																while ($row_n = mysqli_fetch_array($sql_nota)) {
																	$n1 = $row_n['nota_uno_not'];
																	$n2 = $row_n['nota_dos_not'];
																	$n3 = $row_n['nota_tres_not'];
																}
																if($n1 == NULL)
																	$required1 = ' required = "required" ';
																else{
																	$required1 = ' readonly ';
																	if($n2 == NULL)
																		$required2 = ' required = "required" ';
																	else{
																		$required2 = ' readonly ';
																		if($n3 == NULL)
																			$required3 = ' required = "required" ';
																		else{
																			$required3 = ' readonly ';
																		}
																	}
																}
															}else{
																$required1 = ' required = "required" ';
															}
															?>
															<tr>
																<input type="hidden" name = "cod_historico-<?php echo $item; ?>" value = "<?php echo $cod_historico; ?>">
																<td data-title="Nº"><?php echo $item; ?></td>
																<td data-title="NOMBRE" width="40%"><?php echo $row_e['apellido_per']." - ".$row_e['nombre_per']; ?></td>
																<td data-title="CARNET"><?php echo $row_e['carnet_per']; ?></td>
																<td data-title="NOTA UNO"><input type="text" min="0" max="100" maxlength="3" id="nota1-<?php echo $item; ?>" name="nota1-<?php echo $item; ?>" class = "form-control input_suma-<?php echo $item; ?>" placeholder = "..." <?php echo $required1; ?> onkeyup = "sumar(<?php echo $item; ?>)" value = "<?php echo $n1; ?>"></td>
																<td data-title="NOTA DOS"><input type="text" min="0" max="100" maxlength="3" id="nota2-<?php echo $item; ?>" name="nota2-<?php echo $item; ?>" class = "form-control input_suma-<?php echo $item; ?>" placeholder = "..." <?php echo $required2; ?> onkeyup = "sumar(<?php echo $item; ?>)" value = "<?php echo $n2; ?>"></td>
																<td data-title="NOTA TRES"><input type="text" min="0" max="100" maxlength="3" id="nota3-<?php echo $item; ?>" name="nota3-<?php echo $item; ?>" class = "form-control input_suma-<?php echo $item; ?>" placeholder = "..." <?php echo $required3; ?> onkeyup = "sumar(<?php echo $item; ?>)" value = "<?php echo $n3; ?>"></td>
																<td data-title="NOTA FINAL"><input type="text" min="0" max="100" id="notafinal-<?php echo $item; ?>" name="notafinal-<?php echo $item; ?>" class = "form-control" value = "" placeholder = "..." readonly></td>
															</tr>
															<?php
														}
													}else{
														?>
														<tr>
															<td colspan="7" align="center">NO SE ENCONTRAON ESTUDIANTES REGISTRADOS</td>
														</tr>
														<?php
													}
													?>	
												</tbody>
											</table>
											<div class="row text-right">
												<input type="hidden" name="total_filas" value="<?php echo $item; ?>">
												<?php if($item>0) { ?>	
													<div class="col-sm-12">
														<button type="submit" id="submitd" class="btn btn-danger btn-rounded pull-right">Guardar Notas</button>
													</div>
												<?php } ?>
											</div>
										</form>
										<?php
									}
								}else{
									header('Location:inicio.php');
								}
								?>
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

		<script language="JavaScript" type="text/JavaScript">
    	$(document).ready(function(){
    		$('.form-control').on('input', function (e) {    
    			this.value = this.value.replace(/[^0-9]+/ig,"");    
    		}); 

    		$('#nota_uno, #nota_dos, #nota_tres').keyup(function() {
					var nota1 = $("#nota_uno").val();
					var nota2 = $("#nota_dos").val();
					var nota3 = $("#nota_tres").val();

					var pro = parseFloat(nota1) + parseFloat(nota2) + parseFloat(nota3);
					pro = pro / 3;
					pro = pro.toFixed(0);

					if(pro !== NaN)
						$("#nota_final").val(pro);
					else
						$("#nota_final").val("");
				});
    	});

    	function sumar(fila) {
    		var suma = 0;
    		$(".input_suma-"+fila).each(function() {
    			if (isNaN(parseFloat($(this).val()))) {
    				suma += 0;
    			}else{
    				suma += parseFloat($(this).val());
    			}
    		});
    		suma = suma / 3;
				suma = suma.toFixed(0);
    		$('#notafinal-'+fila).val(suma);
    		if (suma > 100 || suma < 0){
    			$('#notafinal-'+fila).val('NN');
    			$('#notafinal-'+fila).css({ 'color': 'brown', 'font-size': '120%', 'background-color': '#D89C9C' });
    		}else if (suma > 50){
    			$('#notafinal-'+fila).val(suma);
    			$('#notafinal-'+fila).css({ 'color': '#036D43', 'font-size': '125%', 'background-color': '#7DD8B4' });
    		}else if (suma < 51){
    			$('#notafinal-'+fila).val(suma);
    			$('#notafinal-'+fila).css({ 'color': 'brown', 'font-size': '125%', 'background-color': '#D89C9C' });
    		}
    	}
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