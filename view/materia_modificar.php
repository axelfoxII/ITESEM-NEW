<?php
include ('../conf/funciones.php');
include("../conf/mysql.php");
$cod_usuario = 0;
if (verificar_usuario()){
	$cod_usuario = $_SESSION['cod_usuario'];
	$nombre_pagina = "materia.php";
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
								<center><h2>Gestión de Materias</h2></center>
								<h4><a href="materia.php"><i class="ion-arrow-left-c"></i>Volver Atras</a></h4>
								<?php
								$cod_materia = 0;
								if(isset($_GET['cod'])){
									$cod_materia = $_GET['cod'];

									$cod_nivel = ""; $nombre_niv = "";
									$materia = ""; $sigla = ""; $cod_tipomateria = 0;
									$cod_carrera = 0; $nombre_car = "";
									$cod_prerequisito = 0;
									$cod_estructura = 0;
									$horas = ""; $contenido = "";
									$sql_materia = mysqli_query($con, "SELECT nombre_mat, sigla_mat, cod_carrera, nombre_car, cod_tipomateria, nombre_tipomat, cod_nivel, nombre_niv, 
										cod_prerequisito_mat, cod_estructura_materia_mat, horas_mat, contenido_mat 
										FROM tbl_materia, tbl_carrera, tbl_tipo_materia, tbl_nivel 
										WHERE cod_carrera = cod_carrera_mat AND cod_tipomateria = cod_tipomateria_mat AND cod_nivel = cod_nivel_car AND cod_materia = $cod_materia");
									while ($row_m = mysqli_fetch_array($sql_materia)) {
										$materia = $row_m['nombre_mat'];
										$sigla = $row_m['sigla_mat'];
										$cod_carrera = $row_m['cod_carrera'];
										$nombre_car = $row_m['nombre_car'];
										$cod_tipomateria = $row_m['cod_tipomateria'];
										$cod_nivel = $row_m['cod_nivel'];
										$nombre_niv = $row_m['nombre_niv'];
										$cod_prerequisito = $row_m['cod_prerequisito_mat'];
										$cod_estructura = $row_m['cod_estructura_materia_mat'];
										$horas = $row_m['horas_mat'];
										$contenido = $row_m['contenido_mat'];
									}
								}
								?>
								<form action="materia_guardar.php" method="POST">
									<input type="hidden" name="cod_materia" value="<?php echo $cod_materia; ?>">
									<input type="hidden" name="tipo" value="modificar">

									<fieldset>
										<div class="form-group row">
											<div class="col-sm-5">
												<label class="col-form-label">NIVEL</label>
												<select class="form-control select2-all" name="nivel" id="nivel" placeholder="..." required="" disabled="">
													<option value="<?php echo $cod_nivel; ?>"><?php echo $nombre_niv; ?></option>
												</select>
											</div>
											<div class="col-sm-7">
												<label class="col-form-label">CARRERA</label>
												<input type="hidden" name="carrera" value="<?php echo $cod_carrera; ?>">
												<select class="form-control select2-all" name="carrera_s" placeholder="..." required="" disabled="">
													<option value="<?php echo $cod_carrera; ?>"><?php echo $nombre_car; ?></option>
												</select>
											</div>
										</div>
									</fieldset>

									<fieldset>
										<div class="form-group row">
											<div class="col-sm-5">
												<label class="col-form-label">MATERIA</label>
												<input class="form-control" type="text" name="materia" placeholder="..." required="" value="<?php echo $materia; ?>">
											</div>
											<div class="col-sm-2">
												<label class="col-form-label">SIGLA</label>
												<input class="form-control" type="text" name="sigla" placeholder="..." required="" value="<?php echo $sigla; ?>">
											</div>
											<div class="col-sm-5">
												<label class="col-form-label">PRE REQUISITO</label>
												<select class="form-control select2-all" name="prerequisito" id="prerequisito" placeholder="...">
													<?php if($cod_prerequisito == 0){ ?>
														<option value="0">*SIN PRE-REQUISITO*</option>
													<?php }else{
														$sql_pre = mysqli_query($con, "SELECT cod_materia, sigla_mat, nombre_mat FROM tbl_materia WHERE cod_materia = $cod_prerequisito");
														while ($row_p = mysqli_fetch_array($sql_pre)) {
															?>
															<option value="<?php echo $row_p['cod_materia']; ?>"><?php echo $row_p['sigla_mat']." - ".$row_p['nombre_mat']; ?></option>
															<?php
														}
													}
													// Las demas materias de la carrera
													$sql_pre = mysqli_query($con, "SELECT cod_materia, sigla_mat, nombre_mat FROM tbl_materia 
														WHERE cod_carrera_mat = $cod_carrera AND cod_materia != $cod_prerequisito");
													while ($row_p = mysqli_fetch_array($sql_pre)) {
														?>
														<option value="<?php echo $row_p['cod_materia']; ?>"><?php echo $row_p['sigla_mat']." - ".$row_p['nombre_mat']; ?></option>
														<?php
													}
													?>
												</select>
											</div>
										</div>
									</fieldset>

									<fieldset>
										<div class="form-group row">
											<div class="col-sm-3">
												<label class="col-form-label">ESTRUCTURA</label>
												<?php
												$disabled = "";
												if($cod_estructura == 0)
													$disabled = "disabled=''";
												?>
												<select class="form-control select2-all" name="estructura" id="estructura" placeholder="..." <?php echo $disabled; ?>>
													<?php
													if($cod_estructura > 0){
														$sql_estructura = mysqli_query($con, "SELECT cod_estructura_materia, nombre_estmat FROM tbl_estructura_materia 
															WHERE cod_estructura_materia = $cod_estructura");
														while ($row_em = mysqli_fetch_array($sql_estructura)) {
															?>
															<option value="<?php echo $row_em['cod_estructura_materia']; ?>"><?php echo $row_em['nombre_estmat']; ?></option>
															<?php
														}

														$sql_estructura = mysqli_query($con, "SELECT cod_estructura_materia, nombre_estmat FROM tbl_estructura_materia 
															WHERE cod_estructura_materia != $cod_estructura");
														while ($row_em = mysqli_fetch_array($sql_estructura)) {
															?>
															<option value="<?php echo $row_em['cod_estructura_materia']; ?>"><?php echo $row_em['nombre_estmat']; ?></option>
															<?php
														}
													}
													?>
												</select>
											</div>
											<div class="col-sm-3">
												<label class="col-form-label">TIPO MATERIA</label>
												<select class="form-control select2-all" name="tipo_materia" placeholder="..." required="">
													<?php
													$sql_tipo = mysqli_query($con, "SELECT cod_tipomateria, nombre_tipomat FROM tbl_tipo_materia 
														WHERE cod_tipomateria = $cod_tipomateria");
													while ($row_ti = mysqli_fetch_array($sql_tipo)) {
														?>
														<option value="<?php echo $row_ti['cod_tipomateria']; ?>"><?php echo $row_ti['nombre_tipomat']; ?></option>
														<?php
													}
													$sql_tipo = mysqli_query($con, "SELECT cod_tipomateria, nombre_tipomat FROM tbl_tipo_materia 
														WHERE cod_tipomateria != $cod_tipomateria AND estado_tipomat = 1");
													while ($row_ti = mysqli_fetch_array($sql_tipo)) {
														?>
														<option value="<?php echo $row_ti['cod_tipomateria']; ?>"><?php echo $row_ti['nombre_tipomat']; ?></option>
														<?php
													}
													?>
												</select>
											</div>
											<div class="col-sm-3">
												<label class="col-form-label">HORAS SEMANALES</label>
												<input class="form-control" type="text" name="horas" placeholder="..." value="<?php echo $horas; ?>">
											</div>
											<div class="col-sm-3">
												<label class="col-form-label">CONTENIDO</label>
												<input type="text" class="form-control" name="contenido" id="contenido" value="<?php echo $contenido; ?>" required="">
											</div>
										</div>
									</fieldset>

									<div class="text-center">
										<button class="btn btn-primary pull-right" type="submit">Modificar el Registro</button>
									</div>
								</form>
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

    <script>
    	$(document).ready(function(){
    		$('.select2-all').select2({
					placeholder: "..."
				});
    	});
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