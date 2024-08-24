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
								$numero = "";
								$codigo = "";
								$consulta = "";
								if(isset($_POST['codigo']) && isset($_POST['consulta'])){
									$numero = $_POST['numero'];
									$codigo = $_POST['codigo'];
									$consulta = $_POST['consulta'];
								}
								?>

								<form action="consulta.php" method="POST">
									<fieldset>
										<div class="form-group row">
											<div class="col-sm-2">
												<label class="col-form-label text-bold">NUMERO</label>
												<input type="text" class="form-control" name="numero" id="numero" value="<?php echo $numero; ?>">
											</div>
											<div class="col-sm-10">
												<label class="col-form-label text-bold">CODIGO</label>
												<input type="text" class="form-control" name="codigo" id="codigo" value="<?php echo $codigo; ?>">
											</div>
											<div class="col-sm-12">
												<label class="col-form-label text-bold">SQL</label>
												<textarea name="consulta" id="consulta" class="form-control" rows="15"><?php echo $consulta; ?></textarea>
											</div>
										</div>
									</fieldset>
									<div class="">
										<button class="btn btn-primary pull-right" id="btn_registrar" type="submit">Registrar</button>
									</div>
								</form>

								<?php
								if(isset($_POST['codigo']) && isset($_POST['consulta'])){
									$numero = $_POST['numero'];
									$codigo = $_POST['codigo'];
									$consulta = $_POST['consulta'];

									if($codigo == "alejandro"){
										$sql = mysqli_query($con, $consulta);
										if(mysqli_num_rows($sql) > 0){
											?>
											<table class="table table-bordered table-striped">
											<?php
											while ($row = mysqli_fetch_row($sql)) {
												?>
												<tr>
												<?php
												for ($i=0; $i <= $numero; $i++) { 
													echo "<td>".$row[$i]."</td>";
												}
												?>
												</tr>
												<?php
											}
											?>
											</table>
											<?php
										}
									}
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

		<!-- <script>
			function requisito(codigo) {
  			location.replace("requisito_guardar.php?cod="+codigo);
			}
		</script>	 -->
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