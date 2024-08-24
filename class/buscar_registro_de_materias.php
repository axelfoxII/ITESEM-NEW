<?php
	include ('../conf/funciones.php');
	include('../conf/mysql.php');
	if (verificar_usuario()){
	
		$sucursal = 0;
		if(!empty($_POST['suc']))
			$sucursal = $_POST['suc'];

		$periodo = 0;
		if(!empty($_POST['per']))
			$periodo = $_POST['per'];

		if($sucursal != 0 && $periodo != 0){
			$sql_oferta = mysqli_query($con, "SELECT cod_oferta_materia, sigla_mat, nombre_mat, sigla_car, resolucion_ministerial_car, sigla_titprof, nombre_per, apellido_per, nombre_tur, 
				cupo_max_of, estado_of, nombre_gru, nombre_tipmod 
				FROM tbl_oferta_materia, tbl_materia, tbl_carrera, tbl_docente, tbl_titulo_profesional, tbl_turno, tbl_persona, tbl_grupo, tbl_tipo_modalidad 
				WHERE cod_materia_of = cod_materia AND cod_carrera_mat = cod_carrera AND cod_docente_of = cod_docente 
				AND cod_tituloprofesional_doc = cod_tituloprofesional AND cod_persona_doc = cod_persona AND cod_turno_of = cod_turno AND cod_grupo_of = cod_grupo AND cod_tipomodalidad_of = cod_tipomodalidad 
				AND cod_periodo_of = $periodo AND cod_sucursal_of = $sucursal AND estado_of = 1 
				ORDER BY cod_turno, cod_oferta_materia");

			if(mysqli_num_rows($sql_oferta) > 0){
				?>
				<thead>
					<tr align="center" class="table-primary">
						<th>#</th>
						<th>COD-OF</th>
						<th>MATERIA</th>
						<th>CARRERA</th>
						<th>TURNO</th>
						<th>DOCENTE</th>
						<th>CUPO MAX.</th>
						<th>REG.</th>
						<th>LISTA</th>
					</tr>
				</thead>
				<tbody>
					<?php
					$item = 1;
					while ($row_o = mysqli_fetch_array($sql_oferta)) {
						$cod_oferta = $row_o['cod_oferta_materia'];
						// Obtener los estudiantes registrados
						$cantidad = 0;
						$sql_historico = mysqli_query($con, "SELECT cod_historico FROM tbl_historico WHERE cod_oferta_materia_his = $cod_oferta 
							AND estado_his = 1");
						$cantidad = mysqli_num_rows($sql_historico);
						?>
						<tr>
							<td data-title="#"><?php echo $item++; ?></td>
							<td data-title="COD-OF"><?php echo $row_o['cod_oferta_materia']; ?></td>
							<td data-title="MATERIA"><?php echo $row_o['sigla_mat']." - ".$row_o['nombre_mat']; ?></td>
							<td data-title="CARRERA"><?php echo $row_o['sigla_car']." - <small>".$row_o['resolucion_ministerial_car']."</small>"; ?></td>
							<td data-title="TURNO"><?php echo $row_o['nombre_tur']." / ".$row_o['nombre_gru']." / ".$row_o['nombre_tipmod']; ?></td>
							<td data-title="DOCENTE"><?php echo $row_o['sigla_titprof']." ".$row_o['nombre_per']." ".$row_o['apellido_per']; ?></td>
							<td data-title="CUPO MAX." align="center"><?php echo $row_o['cupo_max_of']; ?></td>
							<td data-title="REG." align="center"><?php echo $cantidad; ?></td>
							<td data-title="LISTA" align="center"><a target="blank" href="registro_de_materias_lista.php?cod=<?php echo $row_o['cod_oferta_materia']; ?>">Ver</a></td>
						</tr>
					<?php } ?>
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