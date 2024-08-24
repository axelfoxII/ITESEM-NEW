<?php
include ('../conf/funciones.php');
include("../conf/mysql.php");
$cod_usuario = 0;
if (verificar_usuario()){
	$cod_usuario = $_SESSION['cod_usuario'];
	$nombre_pagina = "menu_sistema.php";
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
								<center><h2>Gestión de Menús</h2></center>
								<h4><a href="menu_sistema.php"><i class="ion-arrow-left-c"></i>Volver Atras</a></h4>
								<?php
								$cod_menu = 0;
								if(isset($_GET['cod'])){
									$cod_menu = $_GET['cod'];

									$menu = ""; $icono = "";
									$sql_menu = mysqli_query($con, "SELECT * FROM tbl_menu WHERE cod_menu = $cod_menu");
									while ($row_m = mysqli_fetch_array($sql_menu)) {
										$menu = $row_m['nombre_menu'];
										$icono = $row_m['icono_menu'];
									}
								}
								?>
								<form action="menu_sistema_guardar.php" method="POST">
									<input type="hidden" name="cod_menu" value="<?php echo $cod_menu; ?>">
									<input type="hidden" name="tipo" value="modificar">
									<fieldset>
										<div class="form-group row">
											<div class="col-sm-1"></div>
											<div class="col-sm-6">
												<label class="col-form-label">MENÚ</label>
												<input class="form-control" type="text" name="menu" placeholder="..." required="" value="<?php echo $menu; ?>">
											</div>
											<div class="col-sm-4">
												<label class="col-form-label">ICONO</label>
												<input class="form-control" type="text" id="icono" name="icono" placeholder="..." required="" value="<?php echo $icono; ?>">
											</div>
										</div>
									</fieldset>
									<div class="text-center">
										<button class="btn btn-primary pull-right" type="submit">Modificar el Registro</button>
									</div>
								</form>
								<hr>
								<div class="row">
									<div class="col-sm-12">
										<h6 class="text-center"><b>Seleccione un icono</b></h6>
										<ul class="icons-list">
											<li class="ion-ionic" data-pack="default" onclick="icono('ion-ionic')"></li>
				              <li class="ion-navicon-round" data-pack="default" onclick="icono('ion-navicon-round')"></li>
				              <li class="ion-information-circled" data-pack="default" onclick="icono('ion-information-circled')"></li>
				              <li class="ion-help-circled" data-pack="default" onclick="icono('ion-help-circled')"></li>
				              <li class="ion-backspace" data-pack="default" onclick="icono('ion-backspace')"></li>
				              <li class="ion-help-buoy" data-pack="default" onclick="icono('ion-help-buoy')"></li>
				              <li class="ion-alert-circled" data-pack="default" onclick="icono('ion-alert-circled')"></li>
				              <li class="ion-home" data-pack="default" onclick="icono('ion-home')"></li>
				              <li class="ion-flag" data-pack="default" onclick="icono('ion-flag')"></li>
				              <li class="ion-star" data-pack="default" onclick="icono('ion-star')"></li>
				              <li class="ion-heart" data-pack="default" onclick="icono('ion-heart')"></li>
				              <li class="ion-gear-b" data-pack="default" onclick="icono('ion-gear-b')"></li>
				              <li class="ion-settings" data-pack="default" onclick="icono('ion-settings')"></li>
				              <li class="ion-edit" data-pack="default" onclick="icono('ion-edit')"></li>
				              <li class="ion-clipboard" data-pack="default" onclick="icono('ion-clipboard')"></li>
				              <li class="ion-bookmark" data-pack="default" onclick="icono('ion-bookmark')"></li>
				              <li class="ion-folder" data-pack="default" onclick="icono('ion-folder')"></li>
				              <li class="ion-paper-airplane" data-pack="default" onclick="icono('ion-paper-airplane')"></li>
				              <li class="ion-briefcase" data-pack="default" onclick="icono('ion-briefcase')"></li>
				              <li class="ion-cloud" data-pack="default" onclick="icono('ion-cloud')"></li>
				              <li class="ion-clock" data-pack="default" onclick="icono('ion-clock')"></li>
				              <li class="ion-stats-bars" data-pack="default" onclick="icono('ion-stats-bars')"></li>
				              <li class="ion-pie-graph" data-pack="default" onclick="icono('ion-pie-graph')"></li>
				              <li class="ion-chatbox" data-pack="default" onclick="icono('ion-chatbox')"></li>
				              <li class="ion-person" data-pack="default" onclick="icono('ion-person')"></li>
				              <li class="ion-person-stalker" data-pack="default" onclick="icono('ion-person-stalker')"></li>
				              <li class="ion-calculator" data-pack="default" onclick="icono('ion-calculator')"></li>
				              <li class="ion-eye" data-pack="default" onclick="icono('ion-eye')"></li>
				              <li class="ion-flash" data-pack="default" onclick="icono('ion-flash')"></li>
				              <li class="ion-image" data-pack="default" onclick="icono('ion-image')"></li>
				              <li class="ion-monitor" data-pack="default" onclick="icono('ion-monitor')"></li>
				              <li class="ion-laptop" data-pack="default" onclick="icono('ion-laptop')"></li>
				              <li class="ion-disc" data-pack="default" onclick="icono('ion-disc')"></li>
				              <li class="ion-cash" data-pack="default" onclick="icono('ion-cash')"></li>
				              <li class="ion-university" data-pack="default" onclick="icono('ion-university')"></li>
				              <li class="ion-earth" data-pack="default" onclick="icono('ion-earth')"></li>
				              <li class="ion-lightbulb" data-pack="default" onclick="icono('ion-lightbulb')"></li>
				              <li class="ion-cube" data-pack="default" onclick="icono('ion-cube')"></li>
				              <li class="ion-leaf" data-pack="default" onclick="icono('ion-leaf')"></li>
				              <li class="ion-nuclear" data-pack="default" onclick="icono('ion-nuclear')"></li>
										</ul>
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
    <script type="text/javascript">
    	function	icono(nombre_icono){
    		$('#icono').val(nombre_icono);
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