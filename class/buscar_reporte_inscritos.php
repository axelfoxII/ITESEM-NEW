<?php
	include ('../conf/funciones.php');
	include('../conf/mysql.php');
	if (verificar_usuario()){

		$mes = 0;
		if(isset($_POST['mes']))
			$mes = $_POST['mes'];

		$gestion = 0;
		if(isset($_POST['ges']))
			$gestion = $_POST['ges'];

		$cod_carrera = 0;
		if(isset($_POST['car']))
			$cod_carrera = $_POST['car'];

		$cod_sucursal = 0;
		if(isset($_POST['suc']))
			$cod_sucursal = $_POST['suc'];

		$cod_nivel = 0;
		if(isset($_POST['niv']))
			$cod_nivel = $_POST['niv'];

		$fecha_inicio = "";
		$fecha_fin = "";
		if($mes == "todos"){
			$fecha_inicio = $gestion."-01-01 00:00:00";
			$fecha_fin = $gestion."-12-31 23:59:00";
			$mes = "";
		}else{
			$fecha_inicio = $gestion."-".$mes."-01 00:00:00";
			$fecha_fin = $gestion."-".$mes."-31 23:59:00";
			$mes = $mes."-";
		}

		$det_carrera = " AND cod_carrera = ".$cod_carrera;
		if($cod_carrera == "todos")
			$det_carrera = " AND cod_nivel_car = ".$cod_nivel." ";

		// Obtener la cantidad de estudiantes
		$sql_carrera = mysqli_query($con, "SELECT cod_carrera, sigla_car, nombre_car, COUNT(cod_estudiante) AS cantidad 
			FROM tbl_estudiante, tbl_carrera 
			WHERE cod_carrera_est = cod_carrera AND estado_est = 1 $det_carrera AND cod_sucursal_est = $cod_sucursal 
			AND cod_estudiante IN (SELECT cod_estudiante_cuenta FROM tbl_cuenta_estudiante WHERE estado_cuenta = 1 AND cod_tipocuenta_cuenta = 2) 
			AND fecha_est >= '$fecha_inicio' AND fecha_est <= '$fecha_fin' GROUP BY cod_carrera ORDER BY cantidad DESC, nombre_car");
		?>
		<h5><a href="reporte_inscritos.php"><i class="ion-arrow-left-c"></i> Volver</a></h5>
		<hr>
		<h5 class="text-primary text-center">Reporte de: <?php echo $mes.$gestion; ?></h5>
		<div class="row">
			<div class="col-sm-7">
				<div class="cardbox">
					<div class="cardbox-body">
						<font size="2">
							<table class="table table-bordered table-striped">
								<thead>
									<tr align="center" class="table-primary">
										<th>#</th>
										<th>CARRERA</th>
										<th>SIGLA</th>
										<th>CANT</th>
									</tr>
								</thead>
								<tbody>
									<?php
									$nun_rows = mysqli_num_rows($sql_carrera);
									if($nun_rows > 0){
										$item = 1;
										$total = 0;
										$sigla = array(); $valores = array();
										while ($row_c = mysqli_fetch_array($sql_carrera)) {
											$total = $total + $row_c['cantidad'];
											$sigla[] = $row_c['sigla_car'];
											$valores[] = $row_c['cantidad'];
											echo "<tr>
												<td align='center'>".$item++."</td>
												<td>".$row_c['nombre_car']."</td>
												<td>".$row_c['sigla_car']."</td>
												<td align='right'>".$row_c['cantidad']."</td>
											</tr>";
										}
										echo "<tr>
											<td align='right' colspan='3'><b>TOTAL: </b></td>
											<td align='right'><b>".$total."</b></td>
										</tr>";
									}else{
										echo "<tr><td colspan='3' align='center'>No se encontraron registros.</td></tr>";
									}
									?>
								</tbody>
							</table>
						</font>
					</div>
				</div>
			</div>
			<div class="col-sm-5">
				<div class="cardbox">
					<div class="cardbox-body">
						<canvas id="myChart"></canvas>
					</div>
				</div>
			</div>
		</div>
		<h5 class="text-primary text-center">Detalle de Estudiantes</h5>
		<div class="row">
			<div class="col-sm-12">
				<table class="table table-bordered">
					<thead>
						<tr class="table-primary text">
							<th>#</th>
							<th>ESTUDIANTE</th>
							<th>CARNET</th>
							<th>CARRERA</th>
							<th>PLAN</th>
							<th>PAGOS</th>
							<th>FECHA INSC.</th>
							<th>CELULAR</th>
							<th>COMO LLEGO</th>
							<th>USUARIO</th>
						</tr>
					</thead>
					<tbody>
						<?php
						$item = 1;
						$cod_estudiante = 0;
						$sql_estudiante = mysqli_query($con, "SELECT cod_estudiante, nombre_per, apellido_per, carnet_per, sigla_car, sigla_plan, fecha_est, 
							celular_per, nombre_formalle 
							FROM tbl_estudiante, tbl_persona, tbl_carrera, tbl_plan, tbl_forma_llegada 
							WHERE cod_persona = cod_persona_est AND cod_carrera_est = cod_carrera AND cod_plan_est = cod_plan AND cod_sucursal_est = $cod_sucursal 
							AND cod_formallegada = cod_formallegada_est AND estado_est = 1 $det_carrera 
							AND cod_estudiante IN (SELECT cod_estudiante_cuenta FROM tbl_cuenta_estudiante WHERE estado_cuenta = 1 AND cod_tipocuenta_cuenta = 2) 
							AND fecha_est >= '$fecha_inicio' AND fecha_est <= '$fecha_fin' ORDER BY fecha_est DESC");
						if (mysqli_num_rows($sql_estudiante) > 0) {
							while ($row_e = mysqli_fetch_array($sql_estudiante)) {
								$cod_estudiante = $row_e['cod_estudiante'];
								// Obtener el usuario que registro al estudiante
								$usuario = "";
								$sql_log = mysqli_query($con, "SELECT nombre_per, apellido_per 
									FROM tbl_log, tbl_usuario, tbl_persona, tbl_tabla 
									WHERE cod_tabla_log = cod_tabla AND nombre_tabla = 'tbl_estudiante' AND cod_tipolog_log = 1 
									AND cod_usuario_log = cod_usuario AND cod_persona_us = cod_persona AND codigo_log = $cod_estudiante");
								while ($row_l = mysqli_fetch_array($sql_log)) {
									$usuario = $row_l['nombre_per']." ".$row_l['apellido_per'];
								}

								// OBTNER LOS PAGOS DEL ESTUDIANTE
								$pagos = 0;
								$sql_cuenta = mysqli_query($con, "SELECT SUM(precio_haber_cuenta) AS pagos FROM tbl_cuenta_estudiante 
									WHERE cod_estudiante_cuenta = $cod_estudiante AND estado_cuenta = 1 AND cod_tipocuenta_cuenta = 2");
								while ($row_c = mysqli_fetch_array($sql_cuenta)) {
									$pagos = $row_c['pagos'];
								}
								?>
								<tr>
									<td data-title="#"><?php echo $item++; ?></td>
									<td data-title="ESTUDIANTE"><?php echo $row_e['nombre_per']." ".$row_e['apellido_per']; ?></td>
									<td data-title="CARNET"><?php echo $row_e['carnet_per']; ?></td>
									<td data-title="CARRERA"><?php echo $row_e['sigla_car']; ?></td>
									<td data-title="PLAN"><?php echo $row_e['sigla_plan']; ?></td>
									<td data-title="PAGOS"><?php echo number_format((abs ($pagos)), 2); ?></td>
									<td data-title="FECHA INSC."><?php echo date_format(date_create($row_e['fecha_est']), "d-m-Y H:i"); ?></td>
									<td data-title="CELULAR"><?php echo $row_e['celular_per']; ?></td>
									<td data-title="COMO LLEGO"><?php echo $row_e['nombre_formalle']; ?></td>
									<td data-title="USUARIO"><?php echo $usuario; ?></td>
								</tr>
								<?php
							}
						}else{
							?>
							<tr>
								<td align="center" colspan="9">No se encontraron registros.</td>
							</tr>
							<?php
						}
						?>
					</tbody>
				</table>
			</div>
		</div>
		<div class="clearfix"></div>
		<script
	src="vendor/chart/chart.js">
	</script>
		<script type="text/javascript">
			var nun_rows = "<?php echo $nun_rows ?>";
			
			if(nun_rows > 0){
				const sigla = <?php echo json_encode($sigla);?>;
				const valores = <?php echo json_encode($valores);?>;
				const colores = ["#007bff", "#6610f2", "#dc3545", "#ffc107", "#fd7e14", "#28a745", "#e83e8c", "#6f42c1", "#e83e8c", "#20c997"];

				var ctx = document.getElementById('myChart').getContext('2d');
				var chart = new Chart(ctx, {
					type: 'pie',
					data:{
						datasets: [{
							data: valores,
							backgroundColor: colores,
							label: 'Comparacion de navegadores'
						}],
						labels: sigla
					},
					options: {
						responsive: true,
						title: {
							display: true,
							text: "Registro de Estudiantes"
						}
					}
				});
			}
		</script>
		<?php
	}else {
		header('Location:../index.php');
	}
?>