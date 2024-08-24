<?php
	include ('../conf/funciones.php');
	include('../conf/mysql.php');
	if (verificar_usuario()){
	
		$carnet = "";
		if(isset($_POST['car'])){
			$carnet = $_POST['car'];
			$sql_docente = mysqli_query($con, "SELECT cod_docente, nombre_per, apellido_per, sigla_titprof, estado_doc 
				FROM tbl_docente, tbl_persona, tbl_titulo_profesional 
				WHERE cod_persona_doc = cod_persona AND cod_tituloprofesional_doc = cod_tituloprofesional AND carnet_per LIKE '$carnet%' ORDER BY nombre_per, apellido_per");
		}else{
			$nombre = "";
			if(!empty($_POST['nom']))
				$nombre = $_POST['nom'];

			$apellido = "";
			if(!empty($_POST['ape']))
				$apellido = $_POST['ape'];
			$sql_docente = mysqli_query($con, "SELECT cod_docente, nombre_per, apellido_per, sigla_titprof, estado_doc 
				FROM tbl_docente, tbl_persona, tbl_titulo_profesional 
				WHERE cod_persona_doc = cod_persona AND cod_tituloprofesional_doc = cod_tituloprofesional AND nombre_per LIKE '%$nombre%' AND apellido_per LIKE '%$apellido%' 
				ORDER BY nombre_per, apellido_per");
		}
		if(mysqli_num_rows($sql_docente) > 0){
			?>
			<thead>
				<tr align="center">
					<th>#</th>
					<th>DOCENTE</th>
					<th>MODIFICAR</th>
					<th>ELIMINAR</th>
				</tr>
			</thead>
			<tbody>
				<?php
				$item = 1;
				while ($row_d = mysqli_fetch_array($sql_docente)) {
					$det_estado = "<div class='text-primary'>HABILITADO</div>";
					if($row_d['estado_car'] == 0)
						$det_estado = "<div class='text-danger'>INHABILITADO</div>";
					?>
					<tr>
						<td data-title="#"><?php echo $item++; ?></td>
						<td data-title="DOCNETE"><?php echo $row_d['sigla_titprof']." ".$row_d['nombre_per']." ".$row_d['apellido_per']; ?></td>
						<td data-title="MODIFICAR" align="center"><a href="docente_modificar.php?cod=<?php echo $row_d['cod_docente']; ?>">Modificar</a></td>
						<td data-title="ELIMINAR" align="center"><a href="fun-del/docente_delete.php?cod=<?php echo $row_d['cod_docente']; ?>" onclick="return confirm('¿Estás seguro de cambiar este registro?')">Eliminar</a></td>
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