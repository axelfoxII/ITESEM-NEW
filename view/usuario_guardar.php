<?php
include ('../conf/funciones.php');
include("../conf/mysql.php");
$cod_usuario = 0;
if (verificar_usuario()){
	$cod_usuario = $_SESSION['cod_usuario'];
	$nombre_pagina = "usuario.php";
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
								if(isset($_POST['codigo']) && isset($_POST['nombre_us']) && isset($_POST['correo']) && isset($_POST['perfil'])){
									$cod_persona = $_POST['codigo'];
									$nombre_us = $_POST['nombre_us'];
									$correo = $_POST['correo'];
									$cod_perfil = $_POST['perfil'];
									$sucursal = $_POST['sucursal'];

									if($_POST['tipo'] == "guardar"){
										// Verificar si la persona esta registrada como usuario
										$sql_usuario = mysqli_query($con, "SELECT cod_usuario FROM tbl_usuario WHERE cod_persona_us = $cod_persona");
										if(mysqli_num_rows($sql_usuario) > 0){
											echo '<div class="text-center">
												<h2 class="text-danger">El usuario ya se encuentra registrado.!</h2>
											</div>';
										}else{
											// INSERT tbl_usuario
											$salt = substr(base64_encode(openssl_random_pseudo_bytes('30')), 0, 22);
											$salt = strtr($salt, array('+' => '.'));
											$hash = crypt('123', '$2y$10$' . $salt);

											$insert_persona = mysqli_query($con, "INSERT INTO tbl_usuario(cod_persona_us, nombre_us, correo_us, contrasena_us, cod_perfil_us) 
												VALUES($cod_persona, '$nombre_us', '$correo', '$hash', $cod_perfil)");
											if(mysqli_affected_rows($con) > 0){
												// Obtener el codigo de la tabla
												$cod_tabla = 0;
												$sql_tabla = mysqli_query($con, "SELECT cod_tabla FROM tbl_tabla WHERE nombre_tabla = 'tbl_usuario'");
												while ($row_ta = mysqli_fetch_array($sql_tabla)) {
													$cod_tabla = $row_ta['cod_tabla'];
												}
												// Obtener el ultimo cod_usuario
												$codigo = 0;
												$sql_materia = mysqli_query($con, "SELECT cod_usuario FROM tbl_usuario ORDER BY cod_usuario DESC LIMIT 0,1");
												while ($row = mysqli_fetch_array($sql_materia)) {
													$codigo = $row['cod_usuario'];
												}
												// tbl_log
												$sql_log = mysqli_query($con, "INSERT INTO tbl_log(cod_tipolog_log, cod_tabla_log, codigo_log, cod_usuario_log) 
													VALUES(1, $cod_tabla, $codigo, $cod_usuario)");

												// INSERT USUARIO-SUCURSAL
												for ($i=0; $i<count($sucursal); $i++){
													// Verificar si el usuario y la sucursal estan en la tabla
													$sql_ususuc = mysqli_query($con, "SELECT cod_usuario_sucursal FROM tbl_usuario_sucursal 
														WHERE cod_usuario_ususuc = $codigo AND cod_sucursal_ususuc = $sucursal[$i] AND estado_ususuc = 1");
													if(mysqli_num_rows($sql_ususuc) == 0){
														$insert_ususuc = mysqli_query($con, "INSERT INTO tbl_usuario_sucursal (cod_usuario_ususuc, cod_sucursal_ususuc) 
															VALUES ($codigo, $sucursal[$i])");
													}
												}
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
										}
									}elseif($_POST['tipo'] == "modificar"){
										$cod_usu = $_POST['codigo'];
										$data = 0;

										$delete_ususuc = mysqli_query($con, "UPDATE tbl_usuario_sucursal SET estado_ususuc = 0 
											WHERE cod_usuario_ususuc = $cod_usu");
										// INSERT USUARIO-SUCURSAL
										for ($i=0; $i<count($sucursal); $i++){
											// Verificar si el usuario y la sucursal estan en la tabla
											$sql_ususuc = mysqli_query($con, "SELECT cod_usuario_sucursal FROM tbl_usuario_sucursal 
												WHERE cod_usuario_ususuc = $cod_usu AND cod_sucursal_ususuc = $sucursal[$i]");
											if(mysqli_num_rows($sql_ususuc) == 0){
												$insert_ususuc = mysqli_query($con, "INSERT INTO tbl_usuario_sucursal (cod_usuario_ususuc, cod_sucursal_ususuc) 
													VALUES ($cod_usu, $sucursal[$i])");
												$data = 1;
											}else{
												while($row_cs = mysqli_fetch_array($sql_ususuc)){
													$cod_usuario_sucursal = $row_cs['cod_usuario_sucursal'];
													$update_ususuc = mysqli_query($con, "UPDATE tbl_usuario_sucursal SET estado_ususuc = 1 
														WHERE cod_usuario_sucursal = $cod_usuario_sucursal");
													$data = 1;
												}
											}
										}

										// UPDATE tbl_persona
										$update_persona = mysqli_query($con, "UPDATE tbl_usuario SET nombre_us = '$nombre_us', correo_us = '$correo', cod_perfil_us = $cod_perfil 
											WHERE cod_usuario = $cod_usu");
										if(mysqli_affected_rows($con) > 0 || $data == 1){

											// Obtener el codigo de la tabla
											$cod_tabla = 0;
											$sql_tabla = mysqli_query($con, "SELECT cod_tabla FROM tbl_tabla WHERE nombre_tabla = 'tbl_usuario'");
											while ($row_ta = mysqli_fetch_array($sql_tabla)) {
												$cod_tabla = $row_ta['cod_tabla'];
											}
											// tbl_log
											$sql_log = mysqli_query($con, "INSERT INTO tbl_log(cod_tipolog_log, cod_tabla_log, codigo_log, cod_usuario_log) 
												VALUES(2, $cod_tabla, $cod_usu, $cod_usuario)");
											?>
											<div class="text-center">
												<h2 class="text-primary">Registro Modificado.!</h2>
											</div>
											<?php
										}else{
											?>
											<div class="text-center">
												<h2 class="text-danger">No se ha modificado el registro.!</h2>
											</div>
											<?php
										}
									}

								}
								?>
								<div class="text-center">
									<a href="usuario.php">Volver al Inicio</a>
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