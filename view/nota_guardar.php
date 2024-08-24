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
								<?php $cod_oferta_materia = $_POST['cod_oferta_materia']; ?>
								<h4><a href="nota_ingresar.php?cod=<?php echo $cod_oferta_materia; ?>"><i class="ion-arrow-left-c"></i>Volver a la Lista</a></h4>
								<hr>
								<table class="table table-striped">
									<thead>
										<tr class="table-primary">
											<th>N°</th>
											<th>NOMBRE</th>
											<th>CARNET</th>
											<th>NOTA FINAL</th>
											<th>ESTADO</th>
										</tr>
									</thead>
									<tbody>
										<?php
										$fila = $_POST['total_filas'];
										$cs_notafinal_1 = "";

										if ($fila == "" || $fila == "0"){
											header('Location:nota.php');
										}else{
											$item = 1;
											while($item <= $fila){
												$cod_historico = $_POST['cod_historico-'.$item];

												$nota1 = $_POST['nota1-'.$item];

												$nota2 = $_POST['nota2-'.$item];
												if($nota2 == "")
													$nota2 = "NULL";

												$nota3 = $_POST['nota3-'.$item];
												if($nota3 == "")
													$nota3 = "NULL";

												$notafinal = $_POST['notafinal-'.$item];
												?>
												<tr>
													<td data-title="Nº"><?php echo $item; ?></td>
													<?php
													$sql_estudiante = mysqli_query($con, "SELECT nombre_per, apellido_per, carnet_per FROM tbl_historico, tbl_estudiante, tbl_persona 
														WHERE cod_estudiante_his = cod_estudiante AND cod_persona_est = cod_persona AND cod_historico = $cod_historico");
													while ($row_e = mysqli_fetch_array($sql_estudiante)) {
														?>
														<td data-title="NOMBRE"><?php echo $row_e['apellido_per']." - ".$row_e['nombre_per']; ?></td>
														<td data-title="CARNET"><?php echo $row_e['carnet_per']; ?></td>
														<?php
													}
													// Delete tbl_nota
													$delete_nota = mysqli_query($con, "DELETE FROM tbl_nota WHERE cod_historico_not = $cod_historico");

													// INSERT INTO tbl_nota
													$insert_into = mysqli_query($con, "INSERT INTO tbl_nota (cod_historico_not, nota_uno_not, nota_dos_not, nota_tres_not) 
														VALUES($cod_historico, $nota1, $nota2, $nota3)");

													if(mysqli_affected_rows($con) > 0){
														if ($notafinal != "" && $nota2 !== "NULL" && $nota3 !== "NULL"){
															$update_historico = mysqli_query($con, "UPDATE tbl_historico SET nota_final_his = $notafinal WHERE cod_historico = $cod_historico");
															if ($notafinal < 51) {
																?>
																<td data-title="NOTA FINAL" class="text-danger"><?php echo $notafinal; ?></td>
																<?php
															}else{
																?>
																<td data-title="NOTA FINAL" class="text-success"><?php echo $notafinal; ?></td>
																<?php
															}
															?>
															<td data-title="ESTADO">Guardado</td>
															<?php
														}else{
															?>
															<td data-title="NOTA FINAL"></td>
															<td data-title="ESTADO" class="text-danger">Notas pendientes</td>
															<?php
														}
													}else{
														?>
														<td data-title="NOTA FINAL"></td>
														<td data-title="ESTADO" class="text-danger">No se registro la nota</td>
														<?php
													}
													?>
												</tr>
												<?php
												$item++;
											}
										}
										?>
									</tbody>
								</table>
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