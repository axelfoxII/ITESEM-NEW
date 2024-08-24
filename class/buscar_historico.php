<?php
	include ('../conf/funciones.php');
	include('../conf/mysql.php');
	if (verificar_usuario()){
	
		$carnet = "";
		if (isset($_POST['suc']) && !isset($_POST['car']) && !isset($_POST['nom']) && !isset($_POST['ape'])) {
			$cod_suc = $_POST['suc'];
			$sql_estudiante = mysqli_query($con, "SELECT cod_estudiante, nombre_per, apellido_per, carnet_per, complemento_carnet_per, nombre_car, resolucion_ministerial_car 
			FROM tbl_estudiante, tbl_persona, tbl_carrera 
			WHERE cod_persona_est = cod_persona AND cod_carrera_est = cod_carrera AND cod_sucursal_est = $cod_suc AND estado_per = 1 ORDER BY cod_estudiante DESC LIMIT 0, 10");
		}elseif(isset($_POST['car'])){
			$carnet = $_POST['car'];
			$cod_suc = $_POST['suc'];
			$sql_estudiante = mysqli_query($con, "SELECT cod_estudiante, nombre_per, apellido_per, carnet_per, complemento_carnet_per, nombre_car, resolucion_ministerial_car 
			FROM tbl_estudiante, tbl_persona, tbl_carrera 
			WHERE cod_persona_est = cod_persona AND cod_carrera_est = cod_carrera AND carnet_per LIKE '$carnet%' AND estado_per = 1 AND cod_sucursal_est = $cod_suc 
			ORDER BY nombre_per, apellido_per");
		}else{
			$cod_suc = $_POST['suc'];
			$nombre = "";
			if(!empty($_POST['nom']))
				$nombre = $_POST['nom'];

			$apellido = "";
			if(!empty($_POST['ape']))
				$apellido = $_POST['ape'];
			$sql_estudiante = mysqli_query($con, "SELECT cod_estudiante, nombre_per, apellido_per, carnet_per, complemento_carnet_per, nombre_car, resolucion_ministerial_car 
			FROM tbl_estudiante, tbl_persona, tbl_carrera 
			WHERE cod_persona_est = cod_persona AND cod_carrera_est = cod_carrera AND estado_per = 1 AND nombre_per LIKE '%$nombre%' AND apellido_per LIKE '%$apellido%' 
				AND cod_sucursal_est = $cod_suc ORDER BY nombre_per, apellido_per");
		}
		if(mysqli_num_rows($sql_estudiante) > 0){
			?>
			<thead>
				<tr align="center">
					<th>#</th>
					<th>ESTUDIANTE</th>
					<th>CARNET</th>
					<th>CARRERA</th>
					<th>CERT. EST.</th>
					<th>HIST. NOTAS</th>
				</tr>
			</thead>
			<tbody>
				<?php
				$item = 1;
				while ($row_e = mysqli_fetch_array($sql_estudiante)) {
					$complemento = "";
					if($row_e['complemento_carnet_per'] != "" && $row_e['complemento_carnet_per'] != NULL)
						$complemento = "-".$row_e['complemento_carnet_per'];
					?>
					<tr>
						<td data-title="#"><?php echo $item++; ?></td>
						<td data-title="ESTUDIANTE"><?php echo $row_e['nombre_per']." ".$row_e['apellido_per']; ?></td>
						<td data-title="CARNET"><?php echo $row_e['carnet_per'].$complemento; ?></td>
						<td data-title="CARRERA"><?php echo $row_e['nombre_car']." - ".$row_e['resolucion_ministerial_car']; ?></td>
						<td data-title="CERT. EST." align="center"><a href="historico_certificado.php?cod=<?php echo $row_e['cod_estudiante']; ?>">Ver</a></td>
						<td data-title="HIST. NOTAS" align="center"><a href="historico_notas.php?cod=<?php echo $row_e['cod_estudiante']; ?>">Ver</a></td>
					</tr>
				<?php } ?>
			</tbody>
			<?php
		}else{
			echo "<tr align='center'><td>No se encontraron resultados.</td></tr>";
		}
	}else {
		header('Location:../index.php');
	}
?>