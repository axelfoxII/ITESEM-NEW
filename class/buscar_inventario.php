<?php
	include ('../conf/funciones.php');
	include('../conf/mysql.php');
	if (verificar_usuario()){
	
		$nombre = "";
		if (isset($_POST['suc']) && !isset($_POST['nom'])) {
			$cod_suc = $_POST['suc'];
			$sql_articulo = mysqli_query($con, "SELECT cod_articulo, nombre_art, nombre_tipoart, estado_art, sigla_suc 
				FROM tbl_articulo, tbl_tipo_articulo, tbl_sucursal 
				WHERE cod_sucursal = cod_sucursal_art AND cod_tipoarticulo_art = cod_tipoarticulo AND cod_tipoarticulo_art = 1 AND estado_art = 1 
				AND cod_sucursal_art = $cod_suc ORDER BY cod_articulo DESC LIMIT 0, 10");
		}elseif(isset($_POST['nom'])){
			$nombre = $_POST['nom'];
			$cod_suc = $_POST['suc'];
			$sql_articulo = mysqli_query($con, "SELECT cod_articulo, nombre_art, nombre_tipoart, estado_art, sigla_suc 
				FROM tbl_articulo, tbl_tipo_articulo, tbl_sucursal 
				WHERE cod_sucursal = cod_sucursal_art AND cod_tipoarticulo_art = cod_tipoarticulo AND cod_tipoarticulo_art = 1 AND estado_art = 1 
				AND cod_sucursal_art = $cod_suc AND nombre_art LIKE '%$nombre%' ORDER BY nombre_art");
		}
		if(mysqli_num_rows($sql_articulo) > 0){
			?>
			<thead>
				<tr align="center">
					<th>#</th>
					<th>TIPO</th>
					<th>ARTÍCULO</th>
					<th>CANTIDAD</th>
					<th>OPCIONES</th>
				</tr>
			</thead>
			<tbody>
				<?php
				$item = 1;
				$cantidad = 0;
				while ($row_a = mysqli_fetch_array($sql_articulo)) {
					$cod_articulo = $row_a['cod_articulo'];
					$cantidad = 0;
					// CANTIDAD ARTICULO
					$sql_inventario = mysqli_query($con, "SELECT cod_articulo_inv, SUM(cantidad_inv) AS cantidad FROM tbl_inventario 
						WHERE cod_tipoinventario_inv = 1 AND cod_articulo_inv = $cod_articulo AND estado_inv = 1 GROUP BY cod_articulo_inv");
					if(mysqli_num_rows($sql_inventario) > 0){
						while ($row_in = mysqli_fetch_array($sql_inventario)) {
							$cantidad = $row_in['cantidad'];
						}
					}
					$sql_inventario = mysqli_query($con, "SELECT cod_articulo_inv, SUM(cantidad_inv) AS cantidad FROM tbl_inventario 
						WHERE cod_tipoinventario_inv IN (2, 3) AND cod_articulo_inv = $cod_articulo AND estado_inv = 1 GROUP BY cod_articulo_inv");
					if(mysqli_num_rows($sql_inventario) > 0){
						while ($row_in = mysqli_fetch_array($sql_inventario)) {
							$cantidad = $cantidad - $row_in['cantidad'];
						}
					}
					?>
					<tr>
						<td data-title="#"><?php echo $item++; ?></td>
						<td data-title="TIPO"><?php echo $row_a['nombre_tipoart']; ?></td>
						<td data-title="ARTICULO"><?php echo $row_a['nombre_art']; ?></td>
						<td data-title="CANTIDAD" align="center"><b><?php echo $cantidad; ?></b></td>
						<td data-title="OPCIONES" align="center"><a class="btn btn-primary" href="inventario_ingresar.php?cod=<?php echo $row_a['cod_articulo']; ?>">Registrar Evento</a></td>
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