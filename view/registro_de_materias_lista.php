<?php
include ('../conf/funciones.php');
include("../conf/mysql.php");
$cod_usuario = 0;
if (verificar_usuario()){
	$cod_usuario = $_SESSION['cod_usuario'];
	date_default_timezone_set('America/La_Paz');
	$nombre_pagina = "registro_de_materias.php";
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
								<h4><a href="registro_de_materias.php"><i class="ion-arrow-left-c"></i>Volver Atras</a></h4>
								<center><h4><font face="courier">LISTA DE ESTUDIANTES</font></h4></center>
								<?php
								$cod_oferta = 0;
								if (isset($_GET['cod'])) {
									$cod_oferta = $_GET['cod'];

									$materia = "";
									$carrera = "";
									$docente = "";
									$turno = "";
									$mes_gestion = ""; $fecha_ini = "0000-00-00";
									$sql_oferta = mysqli_query($con, "SELECT sigla_mat, nombre_mat, sigla_car, sigla_titprof, 
										nombre_per, apellido_per, nombre_tur, nombre_peri, nombre_gest, fecha_inicio_of 
										FROM tbl_oferta_materia, tbl_materia, tbl_carrera, tbl_docente, tbl_titulo_profesional, tbl_turno, tbl_persona, tbl_periodo, tbl_gestion 
										WHERE cod_materia_of = cod_materia AND cod_carrera_mat = cod_carrera AND cod_docente_of = cod_docente 
										AND cod_tituloprofesional_doc = cod_tituloprofesional AND cod_persona_doc = cod_persona AND cod_turno_of = cod_turno 
										AND cod_periodo_of = cod_periodo AND cod_gestion_peri = cod_gestion 
										AND cod_oferta_materia = $cod_oferta");
									while ($row_o = mysqli_fetch_array($sql_oferta)) {
										$materia = $row_o['sigla_mat']." - ".$row_o['nombre_mat'];
										$mes_gestion = $row_o['nombre_gest']."-".$row_o['nombre_peri'];
										$docente = $row_o['sigla_titprof']." ".$row_o['nombre_per']." ".$row_o['apellido_per'];
										$turno = $row_o['nombre_tur'];
										$fecha_ini = date_format(date_create($row_o['fecha_inicio_of']), "d-m-Y");
									}
									?>
									<div class="row">
										<div class="col-sm-12">
											<font face="courier">
												<label>Materia : <?php echo $materia; ?></label><br>
												<label>Docente : <?php echo $docente; ?></label><label style="float:right;">Turno : <?php echo $turno; ?></label><br>
												<label>Fecha de Inicio : <?php echo $fecha_ini; ?></label><label style="float:right;">Periodo : <?php echo $mes_gestion; ?></label>
											</font>
										</div>
									</div>
									<div class="no-more-tables">
										<table id="datatable_buscador" class="table table-striped table-sm">
											<thead>
												<tr align="center" class="table-primary">
													<th>#</th>
													<th>CARNET</th>
													<th>ESTUDIANTE</th>
													<th>PLAN</th>
													<th>CARRERA</th>
													<th>NOTA</th>
												</tr>
											</thead>
											<tbody>
												<?php
												// Obtener los estudiantes de la materia
												$item = 1;
												$estado = "";
												$sql_historico = mysqli_query($con, "SELECT nombre_per, apellido_per, carnet_per, sigla_plan, sigla_car, nota_final_his 
													FROM tbl_historico, tbl_estudiante, tbl_persona, tbl_plan, tbl_carrera 
													WHERE cod_estudiante_his = cod_estudiante AND cod_persona = cod_persona_est AND cod_plan_est = cod_plan 
													AND cod_carrera_est = cod_carrera AND cod_oferta_materia_his = $cod_oferta AND estado_his = 1 
													ORDER BY apellido_per, nombre_per");
												if(mysqli_num_rows($sql_historico) > 0){
													while ($row_h = mysqli_fetch_array($sql_historico)) {
														if($row_h['nota_final_his'] > 50)
															$estado = "<div class='text-success'>".$row_h['nota_final_his']."</div>";
														elseif($row_h['nota_final_his'] == "")
															$estado = "<div class='text-primary'>INSCRITA</div>";
														elseif($row_h['nota_final_his'] < 51)
															$estado = "<div class='text-danger'>".$row_h['nota_final_his']."</div>";
														?>
														<tr>
															<td data-title="#"><?php echo $item++; ?></td>
															<td data-title="CARNET"><?php echo $row_h['carnet_per']; ?></td>
															<td data-title="ESTUDIANTE"><?php echo $row_h['apellido_per']." - ".$row_h['nombre_per']; ?></td>
															<td data-title="PLAN"><?php echo $row_h['sigla_plan']; ?></td>
															<td data-title="CARRERA"><?php echo $row_h['sigla_car']; ?></td>
															<td data-title="NOTA" align="center"><?php echo $estado; ?></td>
														</tr>
														<?php
													}
												}else{
													?>
													<tr align='center'><td colspan="6">No se encontraron resultados.</td></tr>
													<?php
												}
												?>
											</tbody>
										</table>
									</div>
								<?php } ?>
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