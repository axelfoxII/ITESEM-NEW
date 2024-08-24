<?php
include ('../conf/funciones.php');
include("../conf/mysql.php");
$cod_usuario = 0;
if (verificar_usuario()){
	date_default_timezone_set('America/La_Paz');
	$cod_usuario = $_SESSION['cod_usuario'];
	$nombre_pagina = "estudiante.php";
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
		<!-- Application styles-->
		<link rel="stylesheet" href="css/app.css">
		<!-- Alertify -->
    <link rel="stylesheet" href="vendor/alertifyjs/css/alertify.css" />
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
								<h4><a href="requisito.php"><i class="ion-arrow-left-c"></i>Volver Atras</a></h4>
								<center><h2>Registro de los Requisitos de Inscripción</h2></center>
								<?php
								$mens = 0;
								if(isset($_GET['mens']) && $_GET['mens'] == '1')
									$mens = 1;

								if(isset($_GET['cod'])){
									$cod_estudiante = $_GET['cod'];

									$nombre = "";
									$carrera = "";
									$carnet = "";
									$expedido = "";
									$cod_nivel = 0;
									$direccion = ""; $celular = "";
									$carrera_est = ""; $duracion = "";
									$precio = 0; $turno = 0;
									$fecha_est = "";
									$sql_estudiante = mysqli_query($con, "SELECT nombre_per, apellido_per, carnet_per, sigla_expedido, cod_nivel_car, nombre_car, sigla_car, resolucion_ministerial_car, 
										direccion_per, celular_per, precio_plan, duracion_car, cod_turno_est, fecha_est 
										FROM tbl_estudiante, tbl_persona, tbl_carrera, tbl_expedido, tbl_plan 
										WHERE cod_persona_est = cod_persona AND cod_carrera_est = cod_carrera AND cod_expedido_per = cod_expedido AND cod_plan_est = cod_plan AND cod_estudiante = $cod_estudiante");
									while ($row_e = mysqli_fetch_array($sql_estudiante)) {
										$nombre = $row_e['nombre_per']." ".$row_e['apellido_per'];
										$carrera = $row_e['sigla_car']." - ".$row_e['nombre_car'].", R.M.: ".$row_e['resolucion_ministerial_car'];
										$carnet = $row_e['carnet_per'];
										$cod_nivel = $row_e['cod_nivel_car'];
										$expedido = $row_e['sigla_expedido'];
										$direccion = $row_e['direccion_per'];
										$celular = $row_e['celular_per'];
										$carrera_est = $row_e['nombre_car'];
										$duracion = $row_e['duracion_car'];
										$precio = $row_e['precio_plan'];
										$turno = $row_e['cod_turno_est'];
										$fecha_est = date_format(date_create($row_e['fecha_est']), 'd-m-Y');
									}

									// FECHA
									$dia = date('d');
									$mes = date('n');
									$array_mes = array('', 'ENE', 'FEB', 'MAR', 'ABR', 'MAY', 'JUN', 'JUL', 'AGO', 'SEP', 'OCT', 'NOV', 'DIC');
									$mes = $array_mes[$mes];
									$anho = date('Y');

									// GENERAR QR
									include ('qr_libreria/qrlib.php');
									$dir = 'img/qr';

									// Crear directorio en caso de no existir
									if ( !file_exists($dir) ) {
										mkdir ($dir, 0744);
									}

									QRcode::png($carnet."|".$nombre."|".$carrera."|".$precio."|".$fecha_est, "img/qr/".$cod_estudiante.".png", QR_ECLEVEL_L,3,1);
									$patch='img/qr/'.$cod_estudiante.'.png';
									// Abrimos una Imagen PNG
									$imagen = imagecreatefrompng($patch);
									$patch_grabar='img/qr/'.$cod_estudiante.'.png';
									imagejpeg($imagen, $patch_grabar,100);
									$image='img/qr/'.$cod_estudiante.'.png';
									$imageData = base64_encode(file_get_contents($image));
									$srcc = 'data:'.mime_content_type($image).';base64,'.$imageData;
									?>
									<center>
										<p>Estudiante: <b><?php echo $nombre; ?></b></p>
										<p>Carrera: <b><?php echo $carrera; ?></b></p>
										<p>C.I.: <b><?php echo $carnet." - ".$expedido; ?></b></p>

										<h3>Documentos Requeridos</h3>
									</center>

									<form action="requisito_guardar.php" method="POST">
										<input type="hidden" name="cod_estudiante" value="<?php echo $cod_estudiante; ?>">
										<input type="hidden" name="cod_nivel" value="<?php echo $cod_nivel; ?>">
										<div class="no-more-tables">
											<table id="datatable_buscador" class="table table-striped table-sm">
												<thead>
													<tr align="center">
														<th>#</th>
														<th>NOMBRE DE DOCUMENTO</th>
														<th>SELECCIONAR</th>
														<th>CANTIDAD</th>
														<th></th>
													</tr>
												</thead>
													<?php
													$item = 1;
													$cod_requisito_inscripcion = 0;
													$sql_requisito = mysqli_query($con, "SELECT cod_requisito_inscripcion, nombre_reqins FROM tbl_requisito_inscripcion 
														WHERE estado_reqins = 1 AND cod_nivel_reqins = $cod_nivel");
													while ($row_r = mysqli_fetch_array($sql_requisito)) {
														$cod_requisito_inscripcion = $row_r['cod_requisito_inscripcion'];

														// VERIFICAR SI EL ESTUDIANTE YA TIENE EL REQUISITO
														$det_check = "";
														$cantidad_estreq = "";
														$sql_estreq = mysqli_query($con, "SELECT cod_estudiante_requisito, cantidad_estreq FROM tbl_estudiante_requisito 
															WHERE cod_estudiante_estreq = $cod_estudiante AND cod_requisito_estreq = $cod_requisito_inscripcion AND estado_estreq = 1");
														if (mysqli_num_rows($sql_estreq) > 0) {
															$det_check = "checked='checked'";
															while ($row_er = mysqli_fetch_array($sql_estreq)) {
																$cantidad_estreq = $row_er['cantidad_estreq'];
															}
														}
														?>
														<tr>
															<td data-title="#" align="center"><?php echo $item++; ?></td>
															<td align="center"><?php echo $row_r['nombre_reqins']; ?></td>
															<td>
																<input type="checkbox" class="form-control" name="requisito-<?php echo $row_r['cod_requisito_inscripcion']; ?>" <?php echo $det_check; ?> value="<?php echo $row_r['cod_requisito_inscripcion']; ?>">
															</td>
															<td align="center"><input type="text" style="width: 30%" class="form-control" name="cantidad-<?php echo $row_r['cod_requisito_inscripcion']; ?>" placeholder="..." value="<?php echo $cantidad_estreq; ?>"></td>
															<td align="center">
																<?php
																if($row_r['nombre_reqins'] == 'Contrato de Estudiante'){
																	?>
																	<a id ="contrato_pdf" class="btn btn-sm btn-success text-white width-80 mb-xs" role="button">Imprimir Contrato</a>
																	<?php
																}
																?>
															</td>
														</tr>
														<?php
													}
													?>
												<tbody>
												</tbody>
											</table>
										</div>
										<div class="text-right"><button type="submit" class="btn btn-primary btn-rounded">Guardar requisitos</button></div>
									</form>
									<?php
								}
								?>
							</div>
						</div>
					</div>
				</section>
			</main>
		</div>

		<!-- MODAL DE CONTRATO -->
		<div class="modal fade bd-example-modal-lg" id="modal_pdf" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
			<div class="modal-dialog modal-lg" role="document">
				<div class="modal-content">
					<div class="modal-body">
						<embed id="pdf_file" frameborder="0" width="100%" height="600px">
					</div>
				</div>
			</div>
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
		<!-- Alertify -->
    <script src="vendor/alertifyjs/alertify.js"></script>
    <!-- PDF -->
    <script src="vendor/pdff/js_table/libre/jspdf.min.js"></script>
		<script src="vendor/pdff/js_table/libre/jspdf.plugin.autotable.src.js"></script>

    <script>
    	$(document).ready(function(){
    		var mens = "<?php echo $mens; ?>";
    		if (mens == '1') {
    			alertify.notify('<div class="text-white">DATOS REGISTRADOS</div>', 'success', 10).dismissOthers();
    		}

    		$("#contrato_pdf").click(function(){
					var doc = new jsPDF('p', 'pt', 'letter');
					var turno = "<?php echo $turno; ?>";
					var img_QR = "<?php echo $srcc; ?>";

					doc.setFontType('bold');

					doc.setFontSize(12);
					doc.setTextColor(255, 0, 0);
					doc.text("SIS-<?php echo $cod_estudiante; ?>", 524, 162);

					doc.setFontSize(7);
					doc.setTextColor(0, 0, 0);
					doc.text("<?php echo $nombre; ?>", 138, 302);
					doc.setFontSize(9);
					doc.text("<?php echo $carnet; ?>", 400, 302);
					doc.text("<?php echo $expedido; ?>", 510, 302);

					doc.setFontSize(7);
					doc.text("<?php echo $direccion; ?>", 95, 319);
					doc.setFontSize(9);
					doc.text("<?php echo $celular; ?>", 390, 319);

					doc.setFontSize(7);
					doc.text("<?php echo $carrera_est; ?>", 68, 369);
					doc.setFontSize(9);
					doc.text("<?php echo $duracion; ?>", 465, 369);

					if(turno == 1 || turno == '1')
						doc.text("X", 287, 388);
					if(turno == 2 || turno == '2')
						doc.text("X", 340, 388);
					if(turno == 3 || turno == '3')
						doc.text("X", 395, 388);

					doc.text("<?php echo $carnet; ?>", 523, 389);
					doc.setFontSize(7);
					doc.text("<?php echo $nombre; ?>", 50, 425);
					doc.setFontSize(9);
					doc.text("<?php echo $precio; ?> Bs.", 265, 442);

					doc.text("<?php echo $dia; ?>", 345, 646);
					doc.text("<?php echo $mes; ?>", 413, 646);
					doc.text("<?php echo $anho; ?>", 464, 646);

					doc.addImage(img_QR, 'JPEG', 490, 680, 85, 85);

					document.getElementById('pdf_file').setAttribute('src', doc.output('bloburl'));
					$('#modal_pdf').modal('show');
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