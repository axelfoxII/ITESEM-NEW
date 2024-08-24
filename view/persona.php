<?php
include ('../conf/funciones.php');
include("../conf/mysql.php");
$cod_usuario = 0;
if (verificar_usuario()){
	$cod_usuario = $_SESSION['cod_usuario'];
	$nombre_pagina = "persona.php";
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
								<center><h2>Gestión de Personas</h2></center>
								<a href="persona_nuevo.php" class="btn btn-labeled btn-primary" type="button"><span class="btn-label"><i class="ion-plus-round"></i></span>Nuevo Registro</a>
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
												<th>NOMBRE</th>
												<th>APELLIDOS</th>
												<th>CARNET</th>
												<th>CELULAR</th>
												<th>MODIFICAR</th>
												<th>ELIMINAR</th>
											</tr>
										</thead>
										<tbody>
											<?php
											$item = 1;
											$sql_persona = mysqli_query($con, "SELECT cod_persona, nombre_per, apellido_per, carnet_per, complemento_carnet_per, celular_per 
												FROM tbl_persona WHERE estado_per = 1 ORDER BY cod_persona DESC LIMIT 0, 10");
											while ($row_p = mysqli_fetch_array($sql_persona)) {
												$complemento = "";
												if($row_p['complemento_carnet_per'] != "" && $row_p['complemento_carnet_per'] != NULL)
													$complemento = "-".$row_p['complemento_carnet_per'];
											?>
											<tr>
												<td data-title="#"><?php echo $item++; ?></td>
												<td data-title="NOMBRE"><?php echo $row_p['nombre_per']; ?></td>
												<td data-title="APELLIDOS"><?php echo $row_p['apellido_per']; ?></td>
												<td data-title="CARNET"><?php echo $row_p['carnet_per'].$complemento; ?></td>
												<td data-title="CELULAR"><?php echo $row_p['celular_per']; ?></td>
												<td data-title="MODIFICAR" align="center"><a href="persona_modificar.php?cod=<?php echo $row_p['cod_persona']; ?>">Modificar</a></td>
												<td data-title="ELIMINAR" align="center"><a href="fun-del/persona_delete.php?cod=<?php echo $row_p['cod_persona']; ?>" onclick="return confirm('¿Estás seguro de eliminar este registro?')">Eliminar</a></td>
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
							url: "../class/buscar_persona.php",
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
							url: "../class/buscar_persona.php",
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