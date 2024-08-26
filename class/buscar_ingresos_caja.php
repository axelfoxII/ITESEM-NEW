<?php
	include ('../conf/funciones.php');
	include('../conf/mysql.php');
	if (verificar_usuario()){
		date_default_timezone_set('America/La_Paz');

		$sucursal = 0;
		if(!empty($_POST['suc']))
			$sucursal = $_POST['suc'];

		$fecha_inicio = "";
		if(!empty($_POST['ini'])){
			$fecha_inicio = $_POST['ini'];
			$fec1 = $fecha_inicio;
		}

		$fecha_fin = "";
		if(!empty($_POST['fin'])){
			$fecha_fin = $_POST['fin'];
			$fec2 = $fecha_fin;
		}

		if ($fecha_inicio != "" && $fecha_fin != "") {
			$fecha_inicio = date_format(date_create($fecha_inicio." 00:00:00"), 'Y-m-d H:i:s');
			// $date = new DateTime($fecha_inicio, new DateTimeZone('America/La_Paz'));
			// $date->format('Y-m-d H:i:s');

			// $date->setTimezone(new DateTimeZone('Europe/Madrid')); 
			// $fecha_inicio =  $date->format('Y-m-d H:i:s');

			$fecha_fin = date_format(date_create($fecha_fin." 23:59:59"), 'Y-m-d H:i:s');
			// $date = new DateTime($fecha_fin, new DateTimeZone('America/La_Paz'));
			// $date->format('Y-m-d H:i:s');

			// $date->setTimezone(new DateTimeZone('Europe/Madrid')); 
			// $fecha_fin =  $date->format('Y-m-d H:i:s');

			$sql_usuario_venta = mysqli_query($con, "SELECT cod_usuario, nombre_per, apellido_per 
				FROM tbl_venta, tbl_usuario, tbl_persona 
				WHERE cod_usuario_venta = cod_usuario AND cod_persona_us = cod_persona AND fecha_venta >= '$fecha_inicio' AND fecha_venta <= '$fecha_fin' AND cod_sucursal_venta = $sucursal 
				AND estado_venta = 1 GROUP BY cod_usuario");
			if (mysqli_num_rows($sql_usuario_venta) > 0) {
				?>
				<thead>
					<tr>
						<td colspan="8"><center><b>REPORTE DE INGRESOS <?php echo $fec1." A ".$fec2 ?></b></center></td>
					</tr>
					<tr align="center" class="table-primary">
						<th bgcolor="#D8D8D8"><font color="black">USUARIO</font></th>
						<th bgcolor="#D8D8D8"><font color="black">COD</font></th>
						<th bgcolor="#D8D8D8"><font color="black">ESTUDIANTE</font></th>
						<th bgcolor="#D8D8D8"><font color="black">PLAN</font></th>
						<th bgcolor="#D8D8D8"><font color="black">CAR</font></th>
						<th bgcolor="#D8D8D8"><font color="black">ARTICULO</font></th>
						<th bgcolor="#D8D8D8"><font color="black">TIPO PAGO</font></th>
						<th bgcolor="#D8D8D8"><font color="black">MONTO Bs.</font></th>
					</tr>
				</thead>
				<tbody>
					<?php
					$cod_usuario = 0;
					$monto_total = 0; $monto_usuario = 0;
					while ($row_uv = mysqli_fetch_array($sql_usuario_venta)) {
						$cod_usuario = $row_uv['cod_usuario'];
						$monto_usuario = 0;
						echo "<tr><td colspan='8'><b>".$row_uv['nombre_per']." - ".$row_uv['apellido_per']."</b></td></tr>";

						$sql_caja = mysqli_query($con, "SELECT cod_venta, nombre_per, apellido_per, carnet_per, sigla_plan, sigla_car, nombre_art, nombre_tipopago, monto_bs_detven 
							FROM tbl_venta, tbl_persona, tbl_estudiante, tbl_plan, tbl_carrera, tbl_tipo_pago, tbl_detalle_venta, tbl_articulo 
							WHERE cod_estudiante_venta = cod_estudiante AND cod_persona_est = cod_persona AND cod_plan_est = cod_plan 
							AND cod_carrera_est = cod_carrera AND cod_venta = cod_venta_detven AND cod_articulo_detven = cod_articulo 
							AND cod_tipopago_venta = cod_tipopago AND fecha_venta >= '$fecha_inicio' AND fecha_venta <= '$fecha_fin' 
							AND estado_venta = 1 AND cod_usuario_venta = $cod_usuario AND cod_sucursal_venta = $sucursal ORDER BY cod_venta");
						while ($row_v = mysqli_fetch_array($sql_caja)) {
							$monto_usuario = $monto_usuario + $row_v['monto_bs_detven'];
							$monto_total = $monto_total + $row_v['monto_bs_detven'];
							?>
							<tr>
								<td></td>
								<td><b><?php echo $row_v['cod_venta']; ?></b></td>
								<td><?php echo $row_v['nombre_per']." ".$row_v['apellido_per']." - ".$row_v['carnet_per']; ?></td>
								<td><?php echo $row_v['sigla_plan']; ?></td>
								<td><?php echo $row_v['sigla_car']; ?></td>
								<td><b><?php echo $row_v['nombre_art']; ?></b></td>
								<td><?php echo $row_v['nombre_tipopago']; ?></td>
								<td align="right"><?php echo number_format((abs ($row_v['monto_bs_detven'])), 2); ?></td>
							</tr>
							<?php
						}
						$sql_tipo_total = mysqli_query($con, "SELECT cod_tipopago, nombre_tipopago, SUM(monto_bs_detven) AS monto_bs
							FROM tbl_venta, tbl_tipo_pago, tbl_detalle_venta  
							WHERE cod_venta = cod_venta_detven  
							AND cod_tipopago_venta = cod_tipopago AND fecha_venta >= '$fecha_inicio' AND fecha_venta <= '$fecha_fin' 
							AND estado_venta = 1 AND cod_usuario_venta = $cod_usuario AND cod_sucursal_venta = $sucursal GROUP BY cod_tipopago ORDER BY cod_venta");
						while ($row_tt = mysqli_fetch_array($sql_tipo_total)) {
							?>
							<tr class="bg-grey-100">
								<td colspan="5"></td>
								<td colspan="2" align="right"><b>TOTAL <?php echo $row_tt['nombre_tipopago']; ?>:</b></td>
								<td align="right"><b><?php echo number_format((abs ($row_tt['monto_bs'])), 2); ?></b></td>
							</tr>
							<?php
						}
						?>
						<tr class="bg-grey-300">
							<td colspan="7"></td>
							<td align="right" class="text-danger text-bold"><?php echo number_format((abs ($monto_usuario)), 2); ?></td>
						</tr>
						<?php
					}
					if($monto_total > 0){
						?>
						<tr class="bg-grey-100">
							<td colspan="7"><b>TOTAL DE INGRESOS:</b></td>
							<td align="right" class="text-danger"><b><?php echo number_format((abs ($monto_total)), 2); ?></b></td>
						</tr>
						<?php
					}
					?>
				</tbody>
				<?php
			}else{
				echo "<tr align='center'><td>No se encontraron resultados.</td></tr>";
			}
		}
	}else {
		header('Location:../index.php');
	}
?>