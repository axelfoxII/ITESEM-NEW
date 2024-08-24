<?php
include ('../conf/funciones.php');
include("../conf/mysql.php");
$cod_usuario = 0;
if (verificar_usuario()){
	$cod_usuario = $_SESSION['cod_usuario'];
	$nombre_pagina = "docente.php";
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
								<center><h2>Gestión de Docentes</h2></center>
								<a href="docente_nuevo.php" class="btn btn-labeled btn-primary" type="button"><span class="btn-label"><i class="ion-plus-round"></i></span>Nuevo Registro</a>
								<div class="row">
									<div class="col-sm-6 text-right">
										<h5>Buscar:</h5>
									</div>
									<div class="col-sm-2">
										<input class="form-control" type="text" id="buscar_nombre" placeholder="...Nombre" value="">
									</div>
									<div class="col-sm-2">
										<input class="form-control" type="text" id="buscar_apellido" placeholder="...Apellido" value="">
									</div>
									<div class="col-sm-2">
										<input class="form-control" type="text" id="buscar_carnet" placeholder="...C.I." value="">
									</div>
								</div>
								<br>
								<div class="no-more-tables">
									<table id="datatable_buscador" class="table table-striped table-sm">
										<thead>
											<tr align="center">
												<th>#</th>
												<th>DOCNETE</th>
												<th>SUB SEDES</th>
												<th>MODIFICAR</th>
												<th>ELIMINAR</th>
											</tr>
										</thead>
										<tbody>
											<?php
											$item = 1;
											$sql_docente = mysqli_query($con, "SELECT cod_docente, nombre_per, apellido_per, sigla_titprof, estado_doc 
												FROM tbl_docente, tbl_persona, tbl_titulo_profesional 
												WHERE cod_persona_doc = cod_persona AND cod_tituloprofesional_doc = cod_tituloprofesional ORDER BY cod_docente DESC LIMIT 0, 10");
											while ($row_d = mysqli_fetch_array($sql_docente)) {
												$cod_docente = $row_d['cod_docente'];
												$det_estado = "<div class='text-primary'>HABILITADO</div>";
												if($row_d['estado_doc'] == 0)
													$det_estado = "<div class='text-danger'>INHABILITADO</div>";

												$sucursal = "";
												$sql_sucursal = mysqli_query($con, "SELECT sigla_suc FROM tbl_sucursal, tbl_docente_sucursal 
													WHERE cod_sucursal = cod_sucursal_docsuc AND cod_docente_docsuc = $cod_docente AND estado_docsuc = 1");
												while($row_ds = mysqli_fetch_array($sql_sucursal)){
													$sucursal = $sucursal.$row_ds['sigla_suc']." / ";
												}
											?>
											<tr>
												<td data-title="#"><?php echo $item++; ?></td>
												<td data-title="DOCNETE"><?php echo $row_d['sigla_titprof']." ".$row_d['nombre_per']." ".$row_d['apellido_per']; ?></td>
												<td data-title="SUB SEDES"><?php echo $sucursal; ?></td>
												<td data-title="MODIFICAR" align="center"><a href="docente_modificar.php?cod=<?php echo $row_d['cod_docente']; ?>">Modificar</a></td>
												<td data-title="ELIMINAR" align="center"><a href="fun-del/docente_delete.php?cod=<?php echo $row_d['cod_docente']; ?>" onclick="return confirm('¿Estás seguro de cambiar este registro?')"><?php echo $det_estado; ?></a></td>
											</tr>
											<?php } ?>
										</tbody>
									</table>
									<div class="row" id="resultado"></div>
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

		<script type="text/javascript">
			$(document).ready(function(){
				var nombre;
				var apellido;
				var carnet;
				$("#buscar_carnet").focus();
				$("#buscar_carnet").keyup(function(e){
					if($(this).val().length > 2){
						carnet = $("#buscar_carnet").val();
						$.ajax({
							type: "POST",
							url: "../class/buscar_docente.php",
							data: "car="+carnet,
							dataType: "html",
							beforeSend: function(){
								$("#datatable_buscador").empty();
								$("#resultado").html("<div class='text-center'><img width='10%' src='img/load.gif'/></div>");
							},
							error: function(){
								alert("Error al buscar");
							},
							success: function(data){
								$("#datatable_buscador").empty();
								$("#resultado").empty();
								$("#datatable_buscador").append(data);
							}
						});
					}
				});

				$("#buscar_nombre, #buscar_apellido").keyup(function(e){
					if($(this).val().length > 2){
						nombre = $("#buscar_nombre").val();
						apellido = $("#buscar_apellido").val();
						$.ajax({
							type: "POST",
							url: "../class/buscar_docente.php",
							data: "nom="+nombre+"&ape="+apellido,
							dataType: "html",
							beforeSend: function(){
								$("#datatable_buscador").empty();
								$("#resultado").html("<div class='text-center'><img width='10%' src='img/load.gif'/></div>");
							},
							error: function(){
								alert("Error al buscar");
							},
							success: function(data){
								$("#datatable_buscador").empty();
								$("#resultado").empty();
								$("#datatable_buscador").append(data);
							}
						});
					}
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