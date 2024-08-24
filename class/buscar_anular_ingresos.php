<?php
	include ('../conf/funciones.php');
	include('../conf/mysql.php');
	if (verificar_usuario()){

		if(isset($_REQUEST['tipo'])){
			$tipo = $_REQUEST['tipo'];

			switch ($tipo) {
				case 'nro_recibo':
					$cod_sucursal = $_POST['suc'];
					$nro_recibo = $_POST['nro_rec'];

					$sql = mysqli_query($con, "SELECT cod_venta, monto_total_venta, nombre_per, apellido_per, carnet_per, fecha_venta, estado_venta FROM tbl_venta, tbl_estudiante, tbl_persona 
						WHERE cod_venta = $nro_recibo AND cod_estudiante_venta = cod_estudiante AND cod_persona_est = cod_persona AND cod_sucursal_est = $cod_sucursal ORDER BY cod_venta");
					if(mysqli_num_rows($sql) > 0){
						while ($row = mysqli_fetch_array($sql)) {
							$det_estado = "<div class='text-primary'>ANULAR REC.</div>";
							if($row['estado_venta'] == 0)
								$det_estado = "<div class='text-danger'>ANULADO</div>";

							$cod_venta = $row['cod_venta'];
							// Obtener el codigo de la tabla
							$cod_tabla = 0;
							$sql_tabla = mysqli_query($con, "SELECT cod_tabla FROM tbl_tabla WHERE nombre_tabla = 'tbl_venta'");
							while ($row_ta = mysqli_fetch_array($sql_tabla)) {
								$cod_tabla = $row_ta['cod_tabla'];
							}

							// OBTENER EL USUARIO
							$det_usuario = "";
							$sql_us = mysqli_query($con, "SELECT nombre_us FROM tbl_log, tbl_usuario WHERE cod_tabla_log = $cod_tabla AND codigo_log = $cod_venta AND cod_tipolog_log = 1 AND cod_usuario_log = cod_usuario");
							if(mysqli_num_rows($sql_us) > 0){
								while ($row_us = mysqli_fetch_array($sql_us)) {
									$det_usuario = $row_us['nombre_us'];
								}
							}

							$date = new DateTime($row['fecha_venta'], new DateTimeZone('Europe/Madrid'));
							$date->format('Y-m-d H:i');

							$date->setTimezone(new DateTimeZone('America/La_Paz')); 
							$fecha_rec =  $date->format('d-m-Y H:i');
							?>
							<tr>
								<td><b><?php echo $row['cod_venta']; ?></b></td>
								<td><b><?php echo number_format($row['monto_total_venta'], 2, '.', ','); ?></b></td>
								<td><?php echo $row['nombre_per']." ".$row['apellido_per']; ?></td>
								<td><?php echo $row['carnet_per']; ?></td>
								<td><b><?php echo $fecha_rec; ?></b></td>
								<td><?php echo $det_usuario; ?></td>
								<?php 
								if($row['estado_venta'] == 0){
									?>
									<td align="center"><?php echo $det_estado; ?></td>
									<?php
								}else{
									?>
									<td align="center"><a href="anular_recibo_ver.php?cod=<?php echo $cod_venta; ?>"><?php echo $det_estado; ?></a></td>
									<?php
								}
								?>
							</tr>
							<?php
						}
					}else{
						?>
						<tr>
							<td colspan="8" align="center">No hay resultados en la tabla.</td>
						</tr>
						<?php
					}
					break;

				case 'fecha':
					$cod_sucursal = $_POST['suc'];
					$fecha = date_format(date_create($_POST['fecha']), 'Y-m-d');

					$fecha1 = $fecha." 00:01";
					$fecha2 = $fecha." 23:59";

					$fecha1 = date_format(date_create($fecha1), 'Y-m-d H:i');
					$date = new DateTime($fecha1, new DateTimeZone('America/La_Paz'));
					$date->format('Y-m-d H:i');

					$date->setTimezone(new DateTimeZone('Europe/Madrid')); 
					$fecha1 =  $date->format('Y-m-d H:i:s');

					$fecha2 = date_format(date_create($fecha2), 'Y-m-d H:i');
					$date = new DateTime($fecha2, new DateTimeZone('America/La_Paz'));
					$date->format('Y-m-d H:i');

					$date->setTimezone(new DateTimeZone('Europe/Madrid')); 
					$fecha2 =  $date->format('Y-m-d H:i:s');

					$sql = mysqli_query($con, "SELECT cod_venta, monto_total_venta, nombre_per, apellido_per, carnet_per, fecha_venta, estado_venta FROM tbl_venta, tbl_estudiante, tbl_persona 
						WHERE fecha_venta >= '$fecha1' AND fecha_venta <= '$fecha2' AND cod_estudiante_venta = cod_estudiante AND cod_persona_est = cod_persona AND cod_sucursal_est = $cod_sucursal ORDER BY cod_venta");
					if(mysqli_num_rows($sql) > 0){
						while ($row = mysqli_fetch_array($sql)) {
							$det_estado = "<div class='text-primary'>ANULAR REC.</div>";
							if($row['estado_venta'] == 0)
								$det_estado = "<div class='text-danger'>ANULADO</div>";

							$cod_venta = $row['cod_venta'];
							// Obtener el codigo de la tabla
							$cod_tabla = 0;
							$sql_tabla = mysqli_query($con, "SELECT cod_tabla FROM tbl_tabla WHERE nombre_tabla = 'tbl_venta'");
							while ($row_ta = mysqli_fetch_array($sql_tabla)) {
								$cod_tabla = $row_ta['cod_tabla'];
							}

							// OBTENER EL USUARIO
							$det_usuario = "";
							$sql_us = mysqli_query($con, "SELECT nombre_us FROM tbl_log, tbl_usuario WHERE cod_tabla_log = $cod_tabla AND codigo_log = $cod_venta AND cod_tipolog_log = 1 AND cod_usuario_log = cod_usuario");
							if(mysqli_num_rows($sql_us) > 0){
								while ($row_us = mysqli_fetch_array($sql_us)) {
									$det_usuario = $row_us['nombre_us'];
								}
							}

							$date = new DateTime($row['fecha_venta'], new DateTimeZone('Europe/Madrid'));
							$date->format('Y-m-d H:i');

							$date->setTimezone(new DateTimeZone('America/La_Paz')); 
							$fecha_rec =  $date->format('d-m-Y H:i');
							?>
							<tr>
								<td><b><?php echo $row['cod_venta']; ?></b></td>
								<td><b><?php echo number_format($row['monto_total_venta'], 2, '.', ','); ?></b></td>
								<td><?php echo $row['nombre_per']." ".$row['apellido_per']; ?></td>
								<td><?php echo $row['carnet_per']; ?></td>
								<td><b><?php echo $fecha_rec; ?></b></td>
								<td><?php echo $det_usuario; ?></td>
								<?php 
								if($row['estado_venta'] == 0){
									?>
									<td align="center"><?php echo $det_estado; ?></td>
									<?php
								}else{
									?>
									<td align="center"><a href="anular_recibo_ver.php?cod=<?php echo $cod_venta; ?>"><?php echo $det_estado; ?></a></td>
									<?php
								}
								?>
							</tr>
							<?php
						}
					}else{
						?>
						<tr>
							<td colspan="8" align="center">No hay resultados en la tabla.</td>
						</tr>
						<?php
					}
					break;
			}
		}
	}else {
		header('Location:../index.php');
	}
?>