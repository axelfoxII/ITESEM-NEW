<?php
	include ('../conf/funciones.php');
	include('../conf/mysql.php');
	if (verificar_usuario()){
		date_default_timezone_set('America/La_Paz');

		if(isset($_REQUEST['funcion']) && !empty($_REQUEST['funcion'])) {
			$funcion = $_REQUEST['funcion'];

			switch ($funcion) {
				case 'buscar_oferta':
					$cod_gestion = $_REQUEST['ges'];
					$cod_sucursal = $_REQUEST['suc'];
					$cod_usuario = $_REQUEST['usu'];
					$cod_docente = $_REQUEST['doc'];

					// Obtener el cod_docente
					$cod_docente = 0;
					$sql_docente = mysqli_query($con, "SELECT cod_docente FROM tbl_docente, tbl_usuario 
						WHERE cod_persona_doc = cod_persona_us AND cod_usuario = $cod_usuario");
					while ($row_d = mysqli_fetch_array($sql_docente)) {
						$cod_docente = $row_d['cod_docente'];
					}

					$det_docente = " AND cod_docente_of = ".$cod_docente;
					// Verificar si el usuario es Administrador
					$sql_privilegio = mysqli_query($con, "SELECT cod_usuario FROM tbl_usuario WHERE cod_usuario = $cod_usuario AND cod_perfil_us = 1");
					if(mysqli_num_rows($sql_privilegio) > 0){
						$det_docente = "";
					}

					$cod_turno = $_REQUEST['tur'];
					$det_turno = " AND cod_turno_of = ".$cod_turno;
					if($cod_turno == "0")
						$det_turno = "";

					$cod_carrera = $_REQUEST['car'];
					$det_carrera = " AND cod_carrera = ".$cod_carrera;
					if($cod_carrera == "0")
						$det_carrera = "";

					$materia = $_REQUEST['mat'];
					$det_materia = " AND nombre_mat LIKE '%".$materia."%'";
					if($materia == "0")
						$det_materia = "";
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
									<th>EST.</th>
									<th>INGRESAR</th>
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
									AND cod_grupo_of = cod_grupo AND cod_periodo_of = cod_periodo AND cod_tipoperiodo_peri = cod_tipoperiodo AND cod_gestion_peri = cod_gestion AND estado_of = 1 AND cod_tipomodalidad_of = cod_tipomodalidad $det_docente $det_carrera $det_materia 
									AND cod_gestion = $cod_gestion AND cod_sucursal_of = $cod_sucursal $det_turno ORDER BY cod_turno, cod_carrera, cod_grupo");
								while ($row_o = mysqli_fetch_array($sql_oferta)) {
									$cod_oferta = $row_o['cod_oferta_materia'];
									// Obtener los estudiantes registrados
									$cantidad = 0;
									$sql_historico = mysqli_query($con, "SELECT cod_historico FROM tbl_historico WHERE cod_oferta_materia_his = $cod_oferta AND estado_his = 1");
									$cantidad = mysqli_num_rows($sql_historico);
									?>
									<tr>
										<td data-title="#"><?php echo $item++; ?></td>
										<td data-title="MATERIA"><?php echo $row_o['sigla_mat']." - ".$row_o['nombre_mat']; ?></td>
										<td data-title="CARRERA"><?php echo $row_o['sigla_car']." - <small>".$row_o['resolucion_ministerial_car']."</small>"; ?></td>
										<td data-title="TIPO"><?php echo $row_o['nombre_tipper']; ?></td>
										<td data-title="PERIODO"><?php echo $row_o['nombre_gest']." - ".$row_o['nombre_peri']; ?></td>
										<td data-title="TURNO/GRUPO"><?php echo $row_o['nombre_tur']." / ".$row_o['nombre_gru']." / ".$row_o['nombre_tipmod']; ?></td>
										<td data-title="DOCENTE"><?php echo $row_o['sigla_titprof']." ".$row_o['nombre_per']." ".$row_o['apellido_per']; ?></td>
										<td data-title="EST." align="center"><?php echo $cantidad; ?></td>
										<td data-title="INGRESAR" align="center"><a href="nota_ingresar.php?cod=<?php echo $row_o['cod_oferta_materia']; ?>" target = "_blank">Ingresar</a></td>
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