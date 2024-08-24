<?php
include ('../conf/funciones.php');
include("../conf/mysql.php");
$cod_usuario = 0;
if (verificar_usuario()){
	$cod_usuario = $_SESSION['cod_usuario'];
	$nombre_pagina = "inventario.php";
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
								if(isset($_POST['cod_articulo']) && isset($_POST['cantidad'])){
									$cantidad = $_POST['cantidad'];

									if($_POST['tipo'] == "ingresar"){
										$cod_articulo = $_POST['cod_articulo'];
										$tipo_inventario = $_POST['tipo_inventario'];
										$observacion = $_POST['observacion'];
										// INSERT tbl_articulo
										$insert_inventario = mysqli_query($con, "INSERT INTO tbl_inventario (cod_articulo_inv, cod_tipoinventario_inv, cantidad_inv, observacion_inv, cod_usuario_inv) 
											VALUES($cod_articulo, $tipo_inventario, $cantidad, '$observacion', $cod_usuario)");
										if(mysqli_affected_rows($con) > 0){
											// Obtener el codigo de la tabla
											$cod_tabla = 0;
											$sql_tabla = mysqli_query($con, "SELECT cod_tabla FROM tbl_tabla WHERE nombre_tabla = 'tbl_inventario'");
											while ($row_ta = mysqli_fetch_array($sql_tabla)) {
												$cod_tabla = $row_ta['cod_tabla'];
											}
											// Obtener el ultimo cod_inventario
											$codigo = 0;
											$sql_inventario = mysqli_query($con, "SELECT cod_inventario FROM tbl_inventario ORDER BY cod_inventario DESC LIMIT 0,1");
											while ($row_in = mysqli_fetch_array($sql_inventario)) {
												$codigo = $row_in['cod_inventario'];
											}
											// tbl_log
											$sql_log = mysqli_query($con, "INSERT INTO tbl_log(cod_tipolog_log, cod_tabla_log, codigo_log, cod_usuario_log) 
												VALUES(1, $cod_tabla, $codigo, $cod_usuario)");
											?>
											<div class="text-center">
												<h2 class="text-primary">Registro Guardado.!</h2>
											</div>
											<?php
										}else{
											?>
											<div class="text-center">
												<h2 class="text-danger">Error al guardar el registro.!</h2>
											</div>
											<?php
										}
									}elseif($_POST['tipo'] == "actualizar"){
										$cod_articulo = $_POST['cod_articulo'];
										$cod_inventario = $_POST['cod_inventario'];
										$update = mysqli_query($con, "UPDATE tbl_inventario SET cantidad_inv = $cantidad WHERE cod_inventario = $cod_inventario");
										if(mysqli_affected_rows($con) > 0){
											// Obtener el codigo de la tabla
											$cod_tabla = 0;
											$sql_tabla = mysqli_query($con, "SELECT cod_tabla FROM tbl_tabla WHERE nombre_tabla = 'tbl_inventario'");
											while ($row_ta = mysqli_fetch_array($sql_tabla)) {
												$cod_tabla = $row_ta['cod_tabla'];
											}
											// tbl_log
											$sql_log = mysqli_query($con, "INSERT INTO tbl_log(cod_tipolog_log, cod_tabla_log, codigo_log, cod_usuario_log) 
												VALUES(2, $cod_tabla, $cod_inventario, $cod_usuario)");
											?>
											<div class="text-center">
												<h2 class="text-primary">Registro Guardado.!</h2>
											</div>
											<?php
										}else{
											?>
											<div class="text-center">
												<h2 class="text-danger">Error al modificar el registro.!</h2>
											</div>
											<?php
										}
									}

								}
								?>
								<div class="text-center">
									<a href="inventario_ingresar.php?cod=<?php echo $cod_articulo; ?>">Volver al Inicio</a>
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