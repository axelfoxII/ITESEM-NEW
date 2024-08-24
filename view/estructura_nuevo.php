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
		<!-- Datepicker-->
    <link rel="stylesheet" href="vendor/bootstrap-datepicker/dist/css/bootstrap-datepicker3.css">
		<!-- Select2-->
    <link rel="stylesheet" href="vendor/select2/dist/css/select2.css">
    <!-- ColorPicker-->
    <link rel="stylesheet" href="vendor/bootstrap-colorpicker/dist/css/bootstrap-colorpicker.css">
		<!-- Application styles-->
		<link rel="stylesheet" href="css/app.css">
		<!-- Datatables-->
		<link rel="stylesheet" href="vendor/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css">
		<link rel="stylesheet" href="vendor/datatables.net-keytable-bs/css/keyTable.bootstrap.min.css">
		<link rel="stylesheet" href="vendor/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css">
		<!-- Alertify -->
    <link rel="stylesheet" href="vendor/alertifyjs/css/alertify.css" />
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
								<h4><a href="estructura.php"><i class="ion-arrow-left-c"></i>Volver Atras</a></h4>
								<?php
								if(isset($_GET['tipo'])){
									$tipo = $_GET['tipo'];
									switch ($tipo) {
										case 'requisito':
											?>
											<center><h3>Requisitos de Documentos</h3></center>
											<a data-toggle='modal' data-target='#modal_requisito' data-whatever='0' class="btn btn-primary text-white">Nuevo Registro</a>
											<br><br>
											<div class="table-responsive">
												<table class="table table-striped my-4" id="datatable1">
													<thead>
														<tr align="center">
															<th>#</th>
															<th>REQUISITO</th>
															<th>NIVEL</th>
															<th>MODIFICAR</th>
															<th>ELIMINAR</th>
														</tr>
													</thead>
													<tbody>
														<?php
														$item = 1;
														$sql_requiaito = mysqli_query($con, "SELECT cod_requisito_inscripcion, nombre_reqins, nombre_niv, estado_reqins 
															FROM tbl_requisito_inscripcion, tbl_nivel WHERE cod_nivel_reqins = cod_nivel ORDER BY cod_nivel");
														while ($row_ri = mysqli_fetch_array($sql_requiaito)) {
															$det_estado = "<div class='text-primary'>HABILITADO</div>";
															if($row_ri['estado_reqins'] == 0)
																$det_estado = "<div class='text-danger'>INHABILITADO</div>";
														?>
														<tr>
															<td data-title="#"><?php echo $item++; ?></td>
															<td data-title="REQUISITO" align="center"><?php echo $row_ri['nombre_reqins']; ?></td>
															<td data-title="NIVEL" align="center"><?php echo $row_ri['nombre_niv']; ?></td>
															<td data-title="MODIFICAR" align="center"><a href="" data-toggle='modal' data-target='#modal_requisito' data-whatever='<?php echo $row_ri['cod_requisito_inscripcion']; ?>'>Modificar</a></td>
															<td data-title="ELIMINAR" align="center"><a href="fun-del/periodo_delete.php?cod=<?php echo $row_ri['cod_requisito_inscripcion']; ?>" onclick="return confirm('¿Estás seguro de cambiar este registro?')"><?php echo $det_estado; ?></a></td>
														</tr>
														<?php } ?>
													</tbody>
												</table>
											</div>


											<!-- VENTANA MODAL -->
											<div class="modal fade" id="modal_requisito" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
												<div class="modal-dialog" role="document">
													<div class="modal-content">
														<div class="modal-header">
															<h5 class="modal-title" id="exampleModalLabel">REQUISITO</h5>
															<button type="button" class="close" data-dismiss="modal" aria-label="Close">
																<span aria-hidden="true">&times;</span>
															</button>
														</div>
														<div class="modal-body">
															<input type="hidden" name="cod_requisito" id="cod_requisito" value="">
															<fieldset>
																<div class="form-group row">
																	<div class="col-sm-12">
																		<label for="recipient-name" class="col-form-label">Nombre del Requisito</label>
																		<input type="text" class="form-control" id="nombre_reqins" name="nombre_reqins" placeholder="..."  value="">
																	</div>
																</div>
															</fieldset>
															<fieldset>
																<div class="form-group row">
																	<div class="col-sm-12">
																		<label class="col-form-label">Nivel</label>
																		<select class="form-control" style="width: 100%" name="cod_nivel_reqins" id="cod_nivel_reqins" placeholder="..." required="">
																			<option value="">...</option>
																			<?php
																			$sql_nivel = mysqli_query($con, "SELECT cod_nivel, nombre_niv FROM tbl_nivel");
																			while ($row_n = mysqli_fetch_array($sql_nivel)) {
																				?>
																				<option value="<?php echo $row_n['cod_nivel']; ?>"><?php echo $row_n['nombre_niv']; ?></option>
																				<?php
																			}
																			?>
																		</select>
																	</div>
																</div>
															</fieldset>
															<div class="clearfix"></div>
														</div>
														<div class="modal-footer">
															<button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
															<input type="submit" class="btn btn-primary" id="update_requisito" value="Guardad Registro">
														</div>
													</div>
												</div>
											</div>
											<?php
											break;

										case 'modalidad':
											?>
											<center><h3>Tipos de Modalidad</h3></center>
											<a data-toggle='modal' data-target='#modal_modalidad' data-whatever='0' class="btn btn-primary text-white">Nuevo Registro</a>
											<br><br>
											<div class="table-responsive">
												<table class="table table-striped my-4" id="datatable1">
													<thead>
														<tr align="center">
															<th>#</th>
															<th>MODALIDAD</th>
															<th>MODIFICAR</th>
														</tr>
													</thead>
													<tbody>
														<?php
														$item = 1;
														$sql_modalidad = mysqli_query($con, "SELECT cod_tipomodalidad, nombre_tipmod FROM tbl_tipo_modalidad");
														while ($row_mo = mysqli_fetch_array($sql_modalidad)) {
														?>
														<tr>
															<td data-title="#"><?php echo $item++; ?></td>
															<td data-title="MODALIDAD" align="center"><?php echo $row_mo['nombre_tipmod']; ?></td>
															<td data-title="MODIFICAR" align="center"><a href="" data-toggle='modal' data-target='#modal_modalidad' data-whatever='<?php echo $row_mo['cod_tipomodalidad']; ?>'>Modificar</a></td>
														</tr>
														<?php } ?>
													</tbody>
												</table>
											</div>


											<!-- VENTANA MODAL -->
											<div class="modal fade" id="modal_modalidad" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
												<div class="modal-dialog" role="document">
													<div class="modal-content">
														<div class="modal-header">
															<h5 class="modal-title" id="exampleModalLabel">TIPO MODALIDAD</h5>
															<button type="button" class="close" data-dismiss="modal" aria-label="Close">
																<span aria-hidden="true">&times;</span>
															</button>
														</div>
														<div class="modal-body">
															<input type="hidden" name="cod_modalidad" id="cod_modalidad" value="">
															<fieldset>
																<div class="form-group row">
																	<div class="col-sm-12">
																		<label for="recipient-name" class="col-form-label">Nombre Modalidad</label>
																		<input type="text" class="form-control" id="nombre_modalidad" name="nombre_modalidad" placeholder="..."  value="">
																	</div>
																</div>
															</fieldset>
															<div class="clearfix"></div>
														</div>
														<div class="modal-footer">
															<button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
															<input type="submit" class="btn btn-primary" id="update_modalidad" value="Guardad Registro">
														</div>
													</div>
												</div>
											</div>
											<?php
											break;
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
		<!-- Alertify -->
    <script src="vendor/alertifyjs/alertify.js"></script>

		<script>
			$(document).ready(function(){
				$('.select2-all').select2({
					placeholder: "..."
				});

				// REQUISITOS DOCUMENTOS
				$('#modal_requisito').on('show.bs.modal', function (event) {
					var button = $(event.relatedTarget);
					var recipient = button.data('whatever');
					var modal = $(this);
					modal.find('#cod_requisito').val(recipient);

					$("#nombre_reqins").val("");
					$("#cod_nivel_reqins option:first").prop('selected',true);
					$('#cod_requisito').val(recipient).trigger('change');
		    });

		    $('#update_requisito').click(function() {
		    	var nombre = $("#nombre_reqins").val();
		    	var cod_nivel = $("#cod_nivel_reqins").val();
		    	var cod_requisito = $("#cod_requisito").val();
		    	if(nombre != "" && cod_nivel != ""){ 	
				    $.ajax({
							type: "POST",
							url: "../class/estructura_funciones.php",
							data: "tipo=requisito&nom="+nombre+"&cod_niv="+cod_nivel+"&cod_req="+cod_requisito,
							dataType: "html",
							beforeSend: function(){
								alertify.notify('<div class="text-center"><i style="color: #0175b1;" class="fa fa-spinner fa-spin fa-2x fa-fw"></i><span class="sr-only">Loading...</span></div>','warning', 0).dismissOthers();
							},
							error: function(){
								alertify.notify('<div class="text-center text-white">ERROR AL REALIZAR EL PROCESO.!</div>','error', 10).dismissOthers();
							},
							success: function(data){
								if(data == 1){
									alertify.notify('<div class="text-center text-white">REGISTRO GUARDADO.!</div>','success', 10).dismissOthers();
									// window.setTimeout(function, 200);
									location.reload();
								}
								else{
									alertify.notify('<div class="text-center text-white">NO SE REGISTRO LOS DATOS</div>','error', 10).dismissOthers();
								}
							}
						});
				  }else{
				  	alertify.notify('<div class="text-center text-white">COMPLETE LOS DATOS.</div>','error', 10).dismissOthers();
				  }
		    });

		    $("#cod_requisito").change(function(){
		    	var cod_requisito = $("#cod_requisito").val();
		    	if(cod_requisito != 0 && cod_requisito != "0"){
		    		$.ajax({
							type: "POST",
							url: "../class/estructura_funciones.php",
							data: "tipo=requisito_buscar&cod_req="+cod_requisito,
							dataType: "json",
							error: function(){
								alertify.notify('<div class="text-center text-white">ERROR AL REALIZAR EL PROCESO.!</div>','error', 10).dismissOthers();
							},
							success: function(data){
								$("#nombre_reqins").val(data.nombre_reqins);
								$("#cod_nivel_reqins option[value="+data.cod_nivel_reqins+"]").attr("selected", true);
							}
						});
		    	}
		    });

		    // TIPO MODALIDAD
		    $('#modal_modalidad').on('show.bs.modal', function (event) {
					var button = $(event.relatedTarget);
					var recipient = button.data('whatever');
					var modal = $(this);
					modal.find('#cod_modalidad').val(recipient);

					$("#nombre_modalidad").val("");
					$('#cod_modalidad').val(recipient).trigger('change');
		    });

		    $('#update_modalidad').click(function() {
		    	var nombre = $("#nombre_modalidad").val();
		    	var cod_modalidad = $("#cod_modalidad").val();
		    	if(nombre != ""){ 	
				    $.ajax({
							type: "POST",
							url: "../class/estructura_funciones.php",
							data: "tipo=modalidad&nom="+nombre+"&cod_mod="+cod_modalidad,
							dataType: "html",
							beforeSend: function(){
								alertify.notify('<div class="text-center"><i style="color: #0175b1;" class="fa fa-spinner fa-spin fa-2x fa-fw"></i><span class="sr-only">Loading...</span></div>','warning', 0).dismissOthers();
							},
							error: function(){
								alertify.notify('<div class="text-center text-white">ERROR AL REALIZAR EL PROCESO.!</div>','error', 10).dismissOthers();
							},
							success: function(data){
								if(data == 1){
									alertify.notify('<div class="text-center text-white">REGISTRO GUARDADO.!</div>','success', 10).dismissOthers();
									// window.setTimeout(function, 200);
									location.reload();
								}
								else{
									alertify.notify('<div class="text-center text-white">NO SE REGISTRO LOS DATOS</div>','error', 10).dismissOthers();
								}
							}
						});
				  }else{
				  	alertify.notify('<div class="text-center text-white">COMPLETE LOS DATOS.</div>','error', 10).dismissOthers();
				  }
		    });

		    $("#cod_modalidad").change(function(){
		    	var cod_modalidad = $("#cod_modalidad").val();
		    	if(cod_modalidad != 0 && cod_modalidad != "0"){
		    		$.ajax({
							type: "POST",
							url: "../class/estructura_funciones.php",
							data: "tipo=modalidad_buscar&cod_mod="+cod_modalidad,
							dataType: "json",
							error: function(){
								alertify.notify('<div class="text-center text-white">ERROR AL REALIZAR EL PROCESO.!</div>','error', 10).dismissOthers();
							},
							success: function(data){
								$("#nombre_modalidad").val(data.nombre_modalidad);
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