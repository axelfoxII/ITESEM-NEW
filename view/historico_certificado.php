<?php
include ('../conf/funciones.php');
include("../conf/mysql.php");
$cod_usuario = 0;
if (verificar_usuario()){
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
								<h4><a href="historico.php"><i class="ion-arrow-left-c"></i>Volver Atras</a></h4>
								<center><h2><font face="courier">Certificado de Estudiante</font></h2></center>
								<?php
								$cod_estudiante = 0;
								if (isset($_GET['cod'])) {
									$cod_estudiante = $_GET['cod'];

									$nombre = "";
									$carnet = "";
									$carrera = ""; $cod_carrera = 0;
									$sql_estudiante = mysqli_query($con, "SELECT nombre_per, apellido_per, carnet_per, sigla_expedido, nombre_car, resolucion_ministerial_car, cod_carrera 
										FROM tbl_estudiante, tbl_persona, tbl_carrera, tbl_expedido 
										WHERE cod_persona = cod_persona_est AND cod_carrera_est = cod_carrera AND cod_expedido_per = cod_expedido 
										AND cod_estudiante = $cod_estudiante");
									while ($row_e = mysqli_fetch_array($sql_estudiante)) {
										$nombre = $row_e['apellido_per']." ".$row_e['nombre_per'];
										$carnet = $row_e['carnet_per']." ".$row_e['sigla_expedido'];
										$carrera = $row_e['nombre_car']." - R.M. ".$row_e['resolucion_ministerial_car'];
										$cod_carrera = $row_e['cod_carrera'];
									}
									?>
									<br>
									<div class="row">
										<div class="col-sm-12">
											<font size="3" face="courier">
												C.I. &nbsp;&nbsp;: <?php echo $carnet." - ".$nombre; ?><br>
												Carrera : <?php echo $carrera; ?>
											</font>
										</div>
									</div>
									<hr>
									<font size="2" face="courier">
										<table class="table table-striped">
											<?php
											$cod_estructura_materia = 0;
											$cant_mat_vencidas = 0;
											$total_mat = 0;
											$sql_estmat = mysqli_query($con, "SELECT valor_estmat, cod_estructura_materia FROM tbl_materia, tbl_estructura_materia 
												WHERE cod_estructura_materia_mat = cod_estructura_materia AND cod_carrera_mat = $cod_carrera GROUP BY valor_estmat, cod_estructura_materia");
											while ($row_em = mysqli_fetch_array($sql_estmat)) {
												$cod_estructura_materia = $row_em['cod_estructura_materia'];
												?>
												<thead>
													<tr class="table-primary">
														<th><font color="black"><?php echo $row_em['valor_estmat']; ?></font></th>
														<th><font color="black">NOTA</font></th>
														<th><font color="black">CÓDIGO</font></th>
														<th><font color="black">ASIGNATURA</font></th>
														<th><font color="black">PRE-REQUISITO</font></th>
													</tr>
												</thead>
												<tbody>
													<?php
													// OBTENER LAS MATERIAS
													$cod_materia = 0; 
													$cod_prerequisito = 0;
													$sql_materia = mysqli_query($con, "SELECT cod_materia, nombre_mat, sigla_mat, cod_prerequisito_mat FROM tbl_materia 
														WHERE cod_carrera_mat = $cod_carrera AND estado_mat = 1 AND cod_tipomateria_mat = 1 AND cod_estructura_materia_mat = $cod_estructura_materia 
														ORDER BY cod_materia");
													$total_mat = mysqli_num_rows($sql_materia) + $total_mat;
													while ($row_m = mysqli_fetch_array($sql_materia)) {
														$cod_prerequisito = $row_m['cod_prerequisito_mat'];
														$cod_materia = $row_m['cod_materia'];
														?>
														<tr>
															<td></td>
															<?php
															// OBTENER LA NOTA DE LA MATERIA DEL ESTUDIANTE
															$sql_historico = mysqli_query($con, "SELECT nota_final_his FROM tbl_historico, tbl_oferta_materia 
																WHERE cod_estudiante_his = $cod_estudiante AND cod_materia_of = $cod_materia 
																AND cod_oferta_materia = cod_oferta_materia_his AND (nota_final_his IS NOT NULL OR nota_final_his > 50)");
															if(mysqli_num_rows($sql_historico) > 0){
																while ($row_h = mysqli_fetch_array($sql_historico)) {
																	echo "<td align='center'>".$row_h['nota_final_his']."</td>";
																	$cant_mat_vencidas++;
																}
															}else{
																echo "<td></td>";
															}
															?>
															<td><?php echo $row_m['sigla_mat']; ?></td>
															<td><?php echo $row_m['nombre_mat']; ?></td>
															<?php
															if($cod_prerequisito > 0){
																$sql_pre = mysqli_query($con, "SELECT sigla_mat FROM tbl_materia WHERE cod_materia = $cod_prerequisito");
																while ($row_p = mysqli_fetch_array($sql_pre)) {
																	echo "<td align='center'>".$row_p['sigla_mat']."</td>";
																}
															}else{
																echo "<td align='center'>--</td>";
															}
															?>
														</tr>
														<?php
													}
													?>
												</tbody>
												<?php
											}
											?>
										</table>
									</font>
									<hr>
									<div class="row">
										<div class="col-sm-12 text-center">
											<font size="2" face="courier">
												TOTAL MATERIAS VENCIDAS <?php echo $cant_mat_vencidas." / ".$total_mat; ?>
											</font>
										</div>
									</div>
									<?php
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