<?php
	include ('../conf/funciones.php');
	include('../conf/mysql.php');
	if (verificar_usuario()){
	
		$carnet = "";
		if(isset($_POST['car'])){
			$carnet = $_POST['car'];
			$sql_persona = mysqli_query($con, "SELECT cod_persona, nombre_per, apellido_per, carnet_per, complemento_carnet_per 
			FROM tbl_persona WHERE carnet_per LIKE '$carnet%' AND estado_per = 1 ORDER BY nombre_per, apellido_per");
		}else{
			$nombre = "";
			if(!empty($_POST['nom']))
				$nombre = $_POST['nom'];

			$apellido = "";
			if(!empty($_POST['ape']))
				$apellido = $_POST['ape'];
			$sql_persona = mysqli_query($con, "SELECT cod_persona, nombre_per, apellido_per, carnet_per, complemento_carnet_per 
				FROM tbl_persona WHERE nombre_per LIKE '%$nombre%' AND apellido_per LIKE '%$apellido%' 
				AND estado_per = 1 ORDER BY nombre_per, apellido_per");
		}
		if(mysqli_num_rows($sql_persona) > 0){
			?>
			<thead>
				<tr align="center">
					<th>#</th>
					<th>NOMBRE</th>
					<th>APELLIDOS</th>
					<th>CARNET</th>
					<th>MODIFICAR</th>
					<th>ELIMINAR</th>
				</tr>
			</thead>
			<tbody>
				<?php
				$item = 1;
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
						<td data-title="MODIFICAR" align="center"><a href="persona_modificar.php?cod=<?php echo $row_p['cod_persona']; ?>">Modificar</a></td>
						<td data-title="ELIMINAR" align="center"><a href="fun-del/persona_delete.php?cod=<?php echo $row_p['cod_persona']; ?>" onclick="return confirm('¿Estás seguro de eliminar este registro?')">Eliminar</a></td>
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