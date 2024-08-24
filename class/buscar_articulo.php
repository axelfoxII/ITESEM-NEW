<?php
	include ('../conf/funciones.php');
	include('../conf/mysql.php');
	if (verificar_usuario()){
		$nombre = "";
		if (isset($_POST['suc']) && !isset($_POST['nom'])) {
			$cod_suc = $_POST['suc'];
			$sql_articulo = mysqli_query($con, "SELECT cod_articulo, nombre_art, precio_art, nombre_tipoart, estado_art, sigla_suc 
				FROM tbl_articulo, tbl_tipo_articulo, tbl_sucursal 
				WHERE cod_sucursal = cod_sucursal_art AND cod_tipoarticulo_art = cod_tipoarticulo AND cod_sucursal_art = $cod_suc ORDER BY cod_articulo DESC LIMIT 0, 10");
		}elseif(isset($_POST['nom'])){
			$nombre = $_POST['nom'];
			$cod_suc = $_POST['suc'];
			$sql_articulo = mysqli_query($con, "SELECT cod_articulo, nombre_art, precio_art, nombre_tipoart, estado_art, sigla_suc 
				FROM tbl_articulo, tbl_tipo_articulo, tbl_sucursal 
				WHERE cod_sucursal = cod_sucursal_art AND cod_tipoarticulo_art = cod_tipoarticulo AND cod_sucursal_art = $cod_suc AND nombre_art LIKE '%$nombre%' 
				ORDER BY nombre_art");
		}
		if(mysqli_num_rows($sql_articulo) > 0){
			?>
			<thead>
				<tr align="center">
					<th>#</th>
					<th>ARTÍCULO</th>
					<th>PRECIO</th>
					<th>TIPO</th>
					<th>SUB SEDE</th>
					<th>MODIFICAR</th>
					<th>ELIMINAR</th>
				</tr>
			</thead>
			<tbody>
				<?php
				$item = 1;
				while ($row_a = mysqli_fetch_array($sql_articulo)) {
					$det_estado = "<div class='text-primary'>HABILITADO</div>";
					if($row_a['estado_art'] == 0)
						$det_estado = "<div class='text-danger'>INHABILITADO</div>";
					?>
					<tr>
						<td data-title="#"><?php echo $item++; ?></td>
						<td data-title="ARTICULO"><?php echo $row_a['nombre_art']; ?></td>
						<td data-title="PRECIO"><?php echo $row_a['precio_art']; ?></td>
						<td data-title="TIPO"><?php echo $row_a['nombre_tipoart']; ?></td>
						<td data-title="SUB SEDE"><?php echo $row_a['sigla_suc']; ?></td>
						<td data-title="MODIFICAR" align="center"><a href="articulo_modificar.php?cod=<?php echo $row_a['cod_articulo']; ?>">Modificar</a></td>
						<td data-title="ELIMINAR" align="center"><a href="fun-del/articulo_delete.php?cod=<?php echo $row_a['cod_articulo']; ?>" onclick="return confirm('¿Estás seguro de eliminar este registro?')"><?php echo $det_estado; ?></a></td>
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