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
		<!-- Datatables-->
		<link rel="stylesheet" href="vendor/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css">
		<link rel="stylesheet" href="vendor/datatables.net-keytable-bs/css/keyTable.bootstrap.min.css">
		<link rel="stylesheet" href="vendor/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css">
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
								<center><h2>Gestión de Usuarios</h2></center>
								<a href="usuario_nuevo.php" class="btn btn-labeled btn-primary" type="button"><span class="btn-label"><i class="ion-plus-round"></i></span>Nuevo Registro</a>
								<br><br>
								<div class="table-responsive">
									<table class="table table-striped my-4" id="datatable1">
										<thead>
											<tr align="center">
												<th>#</th>
												<th>PERSONA</th>
												<th>USUARIO</th>
												<th>SUB SEDES</th>
												<th>MODIFICAR</th>
												<th>REINICIAR</th>
												<th>ESTADO</th>
											</tr>
										</thead>
										<tbody>
											<?php
											$item = 1;
											$sql_usuario = mysqli_query($con, "SELECT cod_usuario, nombre_per, apellido_per, nombre_us, estado_us 
												FROM tbl_persona, tbl_usuario WHERE cod_persona = cod_persona_us");
											while ($row_u = mysqli_fetch_array($sql_usuario)) {
												$cod_usu = $row_u['cod_usuario'];
												$det_estado = "<div class='text-primary'>HABILITADO</div>";
												if($row_u['estado_us'] == 0)
													$det_estado = "<div class='text-danger'>INHABILITADO</div>";

												$sucursal = "";
												$sql_sucursal = mysqli_query($con, "SELECT sigla_suc FROM tbl_sucursal, tbl_usuario_sucursal 
													WHERE cod_sucursal = cod_sucursal_ususuc AND cod_usuario_ususuc = $cod_usu AND estado_ususuc = 1");
												while($row_us = mysqli_fetch_array($sql_sucursal)){
													$sucursal = $sucursal.$row_us['sigla_suc']." / ";
												}
											?>
											<tr>
												<td data-title="#"><?php echo $item++; ?></td>
												<td data-title="PERSONA"><?php echo $row_u['nombre_per']." ".$row_u['apellido_per']; ?></td>
												<td data-title="USUARIO"><?php echo $row_u['nombre_us']; ?></td>
												<td data-title="SUB SEDES"><?php echo $sucursal; ?></td>
												<td data-title="MODIFICAR" align="center"><a href="usuario_modificar.php?cod=<?php echo $row_u['cod_usuario']; ?>">Modificar</a></td>
												<td data-title="REINICIAR" align="center"><a href="usuario_reiniciar.php?cod=<?php echo $row_u['cod_usuario']; ?>" onclick="return confirm('Estas seguro de reiniciar el usuario: <?php echo $row_u['nombre_per'].' '.$row_u['apellido_per'];?> ?')">Reiniciar</a></td>
												<td data-title="ESTADO" align="center"><a href="fun-del/usuario_delete.php?cod=<?php echo $row_u['cod_usuario']; ?>" onclick="return confirm('¿Estás seguro de cambiar este registro?')"><?php echo $det_estado; ?></a></td>
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
		<!-- Datatables-->
		<script src="vendor/datatables.net/js/jquery.dataTables.js"></script>
		<script src="vendor/datatables.net-buttons/js/dataTables.buttons.min.js"></script>
		<script src="vendor/datatables.net-buttons-bs4/js/buttons.bootstrap4.min.js"></script>
		<script src="vendor/datatables.net-buttons/js/buttons.colVis.js"></script>
		<script src="vendor/datatables.net-buttons/js/buttons.flash.min.js"></script>
		<script src="vendor/datatables.net-buttons/js/buttons.html5.min.js"></script>
		<script src="vendor/datatables.net-buttons/js/buttons.print.min.js"></script>
		<script src="vendor/datatables.net-keytable/js/dataTables.keyTable.min.js"></script>
		<script src="vendor/datatables.net-responsive/js/dataTables.responsive.min.js"></script>
		<script src="vendor/datatables.net-responsive-bs4/js/responsive.bootstrap4.min.js"></script>
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