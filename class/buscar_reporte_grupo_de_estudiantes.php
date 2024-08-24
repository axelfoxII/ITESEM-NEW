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

		$cod_turno = $_REQUEST['tur'];
		$det_turno = " AND cod_turno_of = ".$cod_turno;
		if($cod_turno == "0")
			$det_turno = "";

		$cod_grupo = $_REQUEST['gru'];
		$det_grupo = " AND cod_grupo_of = ".$cod_grupo;
		if($cod_grupo == "0")
			$det_grupo = "";

		if($sucursal != 0 && $periodo != 0){
			$sql_oferta = mysqli_query($con, "SELECT cod_grupo_of, cod_periodo_of, nombre_gru, nombre_car, resolucion_ministerial_car, nombre_tur, nombre_tipmod, nombre_au 
				FROM tbl_oferta_materia, tbl_materia, tbl_carrera, tbl_turno, tbl_grupo, tbl_tipo_modalidad, tbl_aula 
				WHERE cod_materia_of = cod_materia AND cod_carrera_mat = cod_carrera AND cod_turno_of = cod_turno AND cod_grupo_of = cod_grupo AND cod_tipomodalidad_of = cod_tipomodalidad 
				AND cod_aula_of = cod_aula AND cod_periodo_of = $periodo AND cod_sucursal_of = $sucursal AND estado_of = 1 $det_turno $det_grupo 
				GROUP BY nombre_gru, nombre_car ORDER BY cod_turno, cod_oferta_materia");

			if(mysqli_num_rows($sql_oferta) > 0){
				?>
				<thead>
					<tr align="center" class="table-primary">
						<th>#</th>
						<th>GRUPO</th>
						<th>CARRERA</th>
						<th>AULA</th>
						<th>REG.</th>
						<th>PAGOS</th>
						<th>MATERIAS</th>
					</tr>
				</thead>
				<tbody>
					<?php
					$item = 1;
					while ($row_o = mysqli_fetch_array($sql_oferta)) {
						$cod_grupo = $row_o['cod_grupo_of'];
						$cod_periodo = $row_o['cod_periodo_of'];
						// Obtener los estudiantes registrados
						$cantidad = 0;
						$sql_historico = mysqli_query($con, "SELECT cod_estudiante_his FROM tbl_historico, tbl_oferta_materia WHERE cod_oferta_materia_his = cod_oferta_materia AND estado_his = 1 
							AND cod_grupo_of = $cod_grupo AND cod_periodo_of = $cod_periodo GROUP BY cod_estudiante_his");
						$cantidad = mysqli_num_rows($sql_historico);
						?>
						<tr>
							<td data-title="#"><?php echo $item++; ?></td>
							<td data-title="GRUPO"><?php echo $row_o['nombre_gru']." / ".$row_o['nombre_tur']." / ".$row_o['nombre_tipmod']; ?></td>
							<td data-title="CARRERA"><?php echo $row_o['nombre_car']." - <small>".$row_o['resolucion_ministerial_car']."</small>"; ?></td>
							<td data-title="AULA"><?php echo $row_o['nombre_au']; ?></td>
							<td data-title="REG." align="center"><?php echo $cantidad; ?></td>
							<td data-title="LISTA" align="center"><a target="_blank" href="reporte_grupo_de_estudiantes_lista.php?gru=<?php echo $cod_grupo; ?>&per=<?php echo $cod_periodo; ?>">Pagos</a></td>
							<td data-title="LISTA" align="center"><a target="_blank" href="reporte_grupo_de_estudiantes_materia.php?gru=<?php echo $cod_grupo; ?>&per=<?php echo $cod_periodo; ?>">Materias</a></td>
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