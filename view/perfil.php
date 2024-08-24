<?php
include ('../conf/funciones.php');
include("../conf/mysql.php");
$cod_usuario = 0;
if (verificar_usuario()){
	$cod_usuario = $_SESSION['cod_usuario'];
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
								$nombre = "";
								$nombre_us = "";
								$correo = "";
								if ($cod_usuario > 0) {
									$sql_usuario = mysqli_query($con, "SELECT nombre_per, apellido_per, nombre_us, correo_us 
										FROM tbl_usuario, tbl_persona 
										WHERE cod_persona_us = cod_persona AND cod_usuario = $cod_usuario");
									while ($row_u = mysqli_fetch_array($sql_usuario)) {
										$nombre = $row_u['nombre_per']." - ".$row_u['apellido_per'];
										$nombre_us = $row_u['nombre_us'];
										$correo = $row_u['correo_us'];
									}
								}
								?>
								<center><h2>Perfil de Usuario</h2></center>
								<div class="row">
									<div class="col-sm-3"></div>
									<div class="col-sm-9">
										<form action="perfil_guardar.php" method="POST">
											<input type="hidden" name="codigo" value="<?php echo $cod_usuario; ?>">
											<input type="hidden" name="tipo" value="modificar">
											<fieldset>
												<div class="form-group row">
													<div class="col-sm-6">
														<label class="col-form-label">NOMBRE</label>
														<input class="form-control" type="text" name="nombre" placeholder="..." readonly="" value="<?php echo $nombre; ?>">
													</div>
												</div>
											</fieldset>
											<fieldset>
												<div class="form-group row">
													<div class="col-sm-4">
														<label class="col-form-label">USUARIO</label>
														<input type="text" name="nombre_us" class="form-control" placeholder="..." required="" value="<?php echo $nombre_us; ?>">
													</div>
													<div class="col-sm-8">
														<label class="col-form-label">CORREO</label>
														<input type="email" name="correo" class="form-control" placeholder="mail@example.com" required="" value="<?php echo $correo; ?>">
													</div>
												</div>
											</fieldset>
											<div class="text-right">
												<button class="btn btn-primary pull-right" type="submit" <?php if($cod_usuario == 0){echo "disabled"; } ?> >Guardar</button>
											</div>
										</form>
										<hr>
										<form action="perfil_guardar.php" method="POST" class="form-validate" id="form-register" name="registerForm" novalidate="">
											<input type="hidden" name="codigo" value="<?php echo $cod_usuario; ?>">
											<input type="hidden" name="tipo" value="cambio_contraseña">
											<h5 class="text-primary">Cambiar Contraseña</h5>
											<fieldset>
												<div class="form-group row">
													<div class="col-sm-6">
														<label class="col-form-label">CONTRASEÑA</label>
														<input class="form-control" id="id-password" type="password" name="password1" required="" minlength="6">
													</div>
													<div class="col-sm-6">
														<label class="col-form-label">REPETIR CONTRASEÑA</label>
														<input class="form-control" type="password" name="confirm_match" required="" minlength="6">
													</div>
												</div>
											</fieldset>
											<div class="float-right">
                      	<button class="btn btn-info" type="submit">Cambiar Contraeña</button>
                    	</div>
										</form>
									</div>
								</div>
								
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
    <!-- PaceJS-->
    <script src="vendor/pace-progress/pace.min.js"></script>
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
    <!-- jQuery Form Validation-->
    <script src="vendor/jquery-validation/dist/jquery.validate.js"></script>
    <script src="vendor/jquery-validation/dist/additional-methods.js"></script>
    <!-- App script-->
    <script src="js/app.js"></script>
	</body>
</html>
<?php
}else {
	header('Location:../inicio.html');
}
?>