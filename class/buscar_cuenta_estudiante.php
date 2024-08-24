<?php
	include ('../conf/funciones.php');
	include('../conf/mysql.php');
	if (verificar_usuario()){

		$sucursal = 0;
		if(!empty($_POST['suc']))
			$sucursal = $_POST['suc'];
		
		$carnet = "";
		if (isset($_POST['suc']) && !isset($_POST['car']) && !isset($_POST['nom']) && !isset($_POST['ape'])) {
			$cod_suc = $_POST['suc'];
			$sql_estudiante = mysqli_query($con, "SELECT cod_estudiante, nombre_per, apellido_per, carnet_per, celular_per, nombre_car, resolucion_ministerial_car 
				FROM tbl_estudiante, tbl_persona, tbl_carrera 
				WHERE cod_persona_est = cod_persona AND cod_carrera_est = cod_carrera AND estado_est = 1 AND cod_sucursal_est = $sucursal ORDER BY cod_estudiante DESC LIMIT 0, 10");
		}elseif(isset($_POST['car'])){
			$carnet = $_POST['car'];
			$sql_estudiante = mysqli_query($con, "SELECT cod_estudiante, nombre_per, apellido_per, carnet_per, celular_per, nombre_car, resolucion_ministerial_car 
			FROM tbl_estudiante, tbl_persona, tbl_carrera 
			WHERE cod_persona_est = cod_persona AND cod_carrera_est = cod_carrera AND carnet_per LIKE '$carnet%' AND estado_per = 1 AND cod_sucursal_est = $sucursal ORDER BY nombre_per, apellido_per");
		}else{
			$nombre = "";
			if(!empty($_POST['nom']))
				$nombre = $_POST['nom'];

			$apellido = "";
			if(!empty($_POST['ape']))
				$apellido = $_POST['ape'];
			$sql_estudiante = mysqli_query($con, "SELECT cod_estudiante, nombre_per, apellido_per, carnet_per, celular_per, nombre_car, resolucion_ministerial_car 
			FROM tbl_estudiante, tbl_persona, tbl_carrera 
			WHERE cod_persona_est = cod_persona AND cod_carrera_est = cod_carrera AND estado_per = 1 AND nombre_per LIKE '%$nombre%' AND apellido_per LIKE '%$apellido%' AND cod_sucursal_est = $sucursal 
				ORDER BY nombre_per, apellido_per");
		}
		if(mysqli_num_rows($sql_estudiante) > 0){
			?>
			<thead>
				<tr align="center">
					<th>#</th>
					<th>ESTUDIANTE</th>
					<th>CARNET</th>
					<th>CELULAR</th>
					<th>CARRERA</th>
					<th>CUENTA EST.</th>
				</tr>
			</thead>
			<tbody>
				<?php
				$item = 1;
				while ($row_e = mysqli_fetch_array($sql_estudiante)) {
					?>
					<tr>
						<td data-title="#"><?php echo $item++; ?></td>
						<td data-title="ESTUDIANTE"><?php echo $row_e['nombre_per']." ".$row_e['apellido_per']; ?></td>
						<td data-title="CARNET"><b><?php echo $row_e['carnet_per']; ?></b></td>
						<td data-title="CELULAR"><?php echo $row_e['celular_per']; ?></td>
						<td data-title="CARRERA"><?php echo $row_e['nombre_car']." - R.M. ".$row_e['resolucion_ministerial_car']; ?></td>
						<td data-title="CUENTA EST." align="center"><a href="cuenta_estudiante_ver.php?cod=<?php echo $row_e['cod_estudiante']; ?>">Ver</a></td>
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