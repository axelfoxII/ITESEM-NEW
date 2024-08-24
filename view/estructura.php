<?php
include ('../conf/funciones.php');
include("../conf/mysql.php");
$cod_usuario = 0;
if (verificar_usuario()){
	$cod_usuario = $_SESSION['cod_usuario'];
	$nombre_pagina = "estructura.php";
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
						<center><h2>Estructura de datos para el Sistema</h2></center>
						<h3>Sección de Académico</h3>
						<div class="row">
							<div class="col-sm-3">
                <div class="cardbox color-red-500" style="border-left: 4px solid">
                  <div class="cardbox-body">
                    <div class="d-flex justify-content-start align-items-center">
                      <div>
                        <p class="mb-0"><b>Nivel</b></p>
                        <span class="text-muted">Niveles de las Carreras.</span>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <div class="col-sm-3">
              	<a href="estructura_nuevo.php?tipo=modalidad">
	                <div class="cardbox color-red-500" style="border-left: 4px solid">
	                  <div class="cardbox-body">
	                    <div class="d-flex justify-content-start align-items-center">
	                      <div>
	                        <p class="mb-0"><b>Modalidad</b></p>
	                        <span class="text-muted">Tipos de Modalidad.</span>
	                      </div>
	                    </div>
	                  </div>
	                </div>
	              </a>
              </div>
						</div>

						<h3>Sección de Estudiantil</h3>
						<div class="row">
							<div class="col-sm-3">
								<a href="estructura_nuevo.php?tipo=requisito">
	                <div class="cardbox color-red-500" style="border-left: 4px solid">
	                  <div class="cardbox-body">
	                    <div class="d-flex justify-content-start align-items-center">
	                      <div>
	                        <p class="mb-0"><b>Requisitos</b></p>
	                        <span class="text-muted">Requisitos de Inscripción.</span>
	                      </div>
	                    </div>
	                  </div>
	                </div>
	               </a>
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