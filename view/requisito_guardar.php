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
								<?php
								if(isset($_POST['cod_estudiante'])){
									$cod_estudiante = $_POST['cod_estudiante'];
									$cod_nivel = $_POST['cod_nivel'];
									$data = 0;

									// DESACTIVAR REQUISITOS DEL ESTUDIANTE
									$update_requisito = mysqli_query($con, "UPDATE tbl_estudiante_requisito SET estado_estreq = 0 
										WHERE cod_estudiante_estreq = $cod_estudiante");

									// REQUISITOS DEL NIVEL
									$cod_requisito = 0;
									echo "SELECT cod_requisito_inscripcion FROM tbl_requisito_inscripcion 
										WHERE estado_reqins = 1 AND cod_nivel_reqins = $cod_nivel";
									$sql_requisito = mysqli_query($con, "SELECT cod_requisito_inscripcion FROM tbl_requisito_inscripcion 
										WHERE estado_reqins = 1 AND cod_nivel_reqins = $cod_nivel");
									while ($row_r = mysqli_fetch_array($sql_requisito)) {
										$cod_requisito = $row_r['cod_requisito_inscripcion'];
										$cantidad = 0;

										if(isset($_POST['requisito-'.$cod_requisito])){
											$cantidad = $_POST['cantidad-'.$cod_requisito];

											$sql_estreq = mysqli_query($con, "SELECT cod_estudiante_requisito FROM tbl_estudiante_requisito 
												WHERE cod_estudiante_estreq = $cod_estudiante AND cod_requisito_estreq = $cod_requisito");
											if (mysqli_num_rows($sql_estreq) == 0) {
												// INSERT tbl_estudiante_requisito
												$insert_requisito = mysqli_query($con, "INSERT INTO tbl_estudiante_requisito (cod_estudiante_estreq, cod_requisito_estreq, cantidad_estreq) 
													VALUES($cod_estudiante, $cod_requisito, $cantidad)");
												if(mysqli_affected_rows($con) > 0)
													$data = 1;
											}else{
												$update_requisito = mysqli_query($con, "UPDATE tbl_estudiante_requisito SET estado_estreq = 1 
													WHERE cod_estudiante_estreq = $cod_estudiante AND cod_requisito_estreq = $cod_requisito");
												$data = 1;
											}

											if($data > 0){
												?>
												<div class="text-center">
													<h2 class="text-primary">Registro Requisitos guardados.!</h2>
												</div>

												<script>
													location.replace("requisito_registro.php?cod=<?php echo $cod_estudiante; ?>&mens=1");
												</script>
												<?php
											}else{
												?>
												<div class="text-center">
													<h2 class="text-danger">Error al guardar los requisitos.!</h2>
												</div>
												<?php
											}
										}

									}
								}
								?>
								<div class="text-center">
									<a href="estudiante.php">Volver al Inicio</a>
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