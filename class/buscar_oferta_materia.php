<?php
	include ('../conf/funciones.php');
	include('../conf/mysql.php');
	if (verificar_usuario()){
		date_default_timezone_set('America/La_Paz');

		if(isset($_REQUEST['funcion']) && !empty($_REQUEST['funcion'])) {
			$funcion = $_REQUEST['funcion'];

			switch ($funcion) {
				case 'buscar_oferta':
					$cod_periodo = $_REQUEST['per'];
					$cod_sucursal = $_REQUEST['suc'];
					$sigla = $_REQUEST['sig'];
					?>
							<thead>
								<tr align="center">
									<th>#</th>
									<th>MATERIA</th>
									<th>CARRERA</th>
									<th>TIPO</th>
									<th>PERIODO</th>
									<th>TURNO/GRUPO</th>
									<th>DOCENTE</th>
									<th>MODIFICAR</th>
									<th>ELIMINAR</th>
								</tr>
							</thead>
							<tbody>
								<?php
								$cod_oferta = 0;
								$item = 1;
								$sql_oferta = mysqli_query($con, "SELECT cod_oferta_materia, sigla_mat, nombre_mat, sigla_car, resolucion_ministerial_car, sigla_titprof, 
									nombre_per, apellido_per, nombre_tur, nombre_gru, estado_of, nombre_gest, nombre_peri, nombre_tipper, nombre_tipmod 
									FROM tbl_oferta_materia, tbl_materia, tbl_carrera, tbl_docente, tbl_titulo_profesional, tbl_turno, tbl_persona, tbl_periodo, tbl_tipo_periodo, tbl_gestion, tbl_grupo, tbl_tipo_modalidad 
									WHERE cod_materia_of = cod_materia AND cod_carrera_mat = cod_carrera AND cod_docente_of = cod_docente 
									AND cod_tituloprofesional_doc = cod_tituloprofesional AND cod_persona_doc = cod_persona AND cod_turno_of = cod_turno 
									AND cod_grupo_of = cod_grupo AND cod_periodo_of = cod_periodo AND cod_tipoperiodo_peri = cod_tipoperiodo AND cod_gestion_peri = cod_gestion AND estado_of = 1 AND cod_tipomodalidad_of = cod_tipomodalidad 
									AND cod_periodo_of = $cod_periodo AND cod_sucursal_of = $cod_sucursal AND sigla_mat LIKE '$sigla%' ORDER BY cod_turno, cod_oferta_materia");
								while ($row_o = mysqli_fetch_array($sql_oferta)) {
									?>
									<tr>
										<td data-title="#"><?php echo $item++; ?></td>
										<td data-title="MATERIA"><?php echo $row_o['sigla_mat']." - ".$row_o['nombre_mat']; ?></td>
										<td data-title="CARRERA"><?php echo $row_o['sigla_car']." - <small>".$row_o['resolucion_ministerial_car']."</small>"; ?></td>
										<td data-title="TIPO"><?php echo $row_o['nombre_tipper']; ?></td>
										<td data-title="PERIODO"><?php echo $row_o['nombre_gest']." - ".$row_o['nombre_peri']; ?></td>
										<td data-title="TURNO/GRUPO"><?php echo $row_o['nombre_tur']." / ".$row_o['nombre_gru']." / ".$row_o['nombre_tipmod']; ?></td>
										<td data-title="DOCENTE"><?php echo $row_o['sigla_titprof']." ".$row_o['nombre_per']." ".$row_o['apellido_per']; ?></td>
										<td data-title="MODIFICAR" align="center"><a href="oferta_materia_modificar.php?cod=<?php echo $row_o['cod_oferta_materia']; ?>">Modificar</a></td>
										<td data-title="ELIMINAR" align="center"><a href="fun-del/oferta_materia_delete.php?cod=<?php echo $row_o['cod_oferta_materia']; ?>" onclick="return confirm('¿Estás seguro de eliminar este registro?')">Eliminar</a></td>
									</tr>
									<?php
								}
								?>
							</tbody>
					<?php
					break;

				case 'buscar_oferta2':
					$cod_periodo = $_REQUEST['per'];
					$cod_sucursal = $_REQUEST['suc'];

					$cod_turno = $_REQUEST['tur'];
					$det_turno = " AND cod_turno_of = ".$cod_turno;
					if($cod_turno == "0")
						$det_turno = "";

					$cod_grupo = $_REQUEST['gru'];
					$det_grupo = " AND cod_grupo_of = ".$cod_grupo;
					if($cod_grupo == "0")
						$det_grupo = "";
					?>
							<thead>
								<tr align="center">
									<th>#</th>
									<th>MATERIA</th>
									<th>CARRERA</th>
									<th>TIPO</th>
									<th>PERIODO</th>
									<th>TURNO/GRUPO</th>
									<th>CUPO</th>
									<th>DOCENTE</th>
									<th>MODIFICAR</th>
									<th>ELIMINAR</th>
								</tr>
							</thead>
							<tbody>
								<?php
								$cod_oferta = 0;
								$item = 1;
								$sql_oferta = mysqli_query($con, "SELECT cod_oferta_materia, sigla_mat, nombre_mat, sigla_car, resolucion_ministerial_car, sigla_titprof, 
									nombre_per, apellido_per, nombre_tur, nombre_gru, estado_of, nombre_gest, nombre_peri, nombre_tipper, nombre_tipmod, cupo_max_of 
									FROM tbl_oferta_materia, tbl_materia, tbl_carrera, tbl_docente, tbl_titulo_profesional, tbl_turno, tbl_persona, tbl_periodo, tbl_tipo_periodo, tbl_gestion, tbl_grupo, tbl_tipo_modalidad 
									WHERE cod_materia_of = cod_materia AND cod_carrera_mat = cod_carrera AND cod_docente_of = cod_docente 
									AND cod_tituloprofesional_doc = cod_tituloprofesional AND cod_persona_doc = cod_persona AND cod_turno_of = cod_turno 
									AND cod_grupo_of = cod_grupo AND cod_periodo_of = cod_periodo AND cod_tipoperiodo_peri = cod_tipoperiodo AND cod_gestion_peri = cod_gestion AND estado_of = 1 AND cod_tipomodalidad_of = cod_tipomodalidad 
									AND cod_periodo_of = $cod_periodo AND cod_sucursal_of = $cod_sucursal $det_turno $det_grupo ORDER BY cod_turno, cod_oferta_materia");
								while ($row_o = mysqli_fetch_array($sql_oferta)) {
									?>
									<tr>
										<td data-title="#"><?php echo $item++; ?></td>
										<td data-title="MATERIA"><?php echo $row_o['sigla_mat']." - ".$row_o['nombre_mat']; ?></td>
										<td data-title="CARRERA"><?php echo $row_o['sigla_car']." - <small>".$row_o['resolucion_ministerial_car']."</small>"; ?></td>
										<td data-title="TIPO"><?php echo $row_o['nombre_tipper']; ?></td>
										<td data-title="PERIODO"><?php echo $row_o['nombre_gest']." - ".$row_o['nombre_peri']; ?></td>
										<td data-title="TURNO/GRUPO"><?php echo $row_o['nombre_tur']." / ".$row_o['nombre_gru']." / ".$row_o['nombre_tipmod']; ?></td>
										<td data-title="CUPO" align="center"><?php echo $row_o['cupo_max_of']; ?></td>
										<td data-title="DOCENTE"><?php echo $row_o['sigla_titprof']." ".$row_o['nombre_per']." ".$row_o['apellido_per']; ?></td>
										<td data-title="MODIFICAR" align="center"><a href="oferta_materia_modificar.php?cod=<?php echo $row_o['cod_oferta_materia']; ?>">Modificar</a></td>
										<td data-title="ELIMINAR" align="center"><a href="fun-del/oferta_materia_delete.php?cod=<?php echo $row_o['cod_oferta_materia']; ?>" onclick="return confirm('¿Estás seguro de eliminar este registro?')">Eliminar</a></td>
									</tr>
									<?php
								}
								?>
							</tbody>
					<?php
					break;
			}
		}
	}else {
		header('Location:../index.php');
	}
?>