<?php
	include ('../conf/funciones.php');
	include('../conf/mysql.php');
	if (verificar_usuario()){
		date_default_timezone_set('America/La_Paz');

		if(isset($_REQUEST['funcion']) && !empty($_REQUEST['funcion'])) {
			$funcion = $_REQUEST['funcion'];

			switch ($funcion) {
				case 'buscar_oferta':
					$cod_materia = $_POST['mat'];
					$cod_sucursal = $_POST['suc'];
					?>
					<div class="no-more-tables">
						<table id="datatable_buscador" class="table table-striped table-sm">
							<thead>
								<tr align="center">
									<th>MATERIA</th>
									<th>CUPO</th>
									<th>FECHA</th>
									<th>DOCENTE</th>
									<th>TURNO</th>
									<th>ACCIÓN</th>
								</tr>
							</thead>
							<tbody>
								<?php
								$cod_oferta = 0;
								$sql_oferta = mysqli_query($con, "SELECT cod_oferta_materia, nombre_mat, sigla_mat, cupo_max_of, fecha_inicio_of, fecha_fin_of, nombre_per, 
									apellido_per, nombre_tur FROM tbl_oferta_materia, tbl_materia, tbl_periodo, tbl_turno, tbl_docente, tbl_persona 
									WHERE cod_materia_of = cod_materia AND cod_periodo_of = cod_periodo AND cod_turno_of = cod_turno AND cod_docente_of = cod_docente 
									AND cod_persona_doc = cod_persona AND cod_materia_of = $cod_materia AND estado_of = 1 AND cod_sucursal_of = $cod_sucursal 
									ORDER BY cod_gestion_peri, cod_periodo DESC LIMIT 0, 5");
								while ($row_o = mysqli_fetch_array($sql_oferta)) {
									$cod_oferta = $row_o['cod_oferta_materia'];

									// CONTAR LOS ESTUDIANTES REGISTRADOS
									$cantidad_of = 0;
									$sql_his_cupo = mysqli_query($con, "SELECT cod_historico FROM tbl_historico WHERE cod_oferta_materia_his = $cod_oferta AND estado_his = 1");
									$cantidad_of = mysqli_num_rows($sql_his_cupo);
									?>
									<tr>
										<td data-title="MATERIA"><?php echo "<font size='2'>".$row_o['sigla_mat']." - ".$row_o['nombre_mat']."</font>"; ?></td>
										<td data-title="CUPO" align="center"><?php echo $cantidad_of." (".$row_o['cupo_max_of'].")"; ?></td>
										<td data-title="FECHA" align="center">
											<?php echo "<font size='2'><b>Desde: </b>".date_format(date_create($row_o['fecha_inicio_of']), "d-m-Y")
											."<br><b>Hasta: </b>".date_format(date_create($row_o['fecha_fin_of']), "d-m-Y")."</font>"; ?>
										</td>
										<td data-title="DOCENTE"><?php echo "<font size='2'>".$row_o['nombre_per']." ".$row_o['apellido_per']."</font>"; ?></td>
										<td data-title="TURNO"><?php echo $row_o['nombre_tur']; ?></td>
										<td data-title="ACCIÓN"><a class="btn btn-primary text-white" onclick="matricular(<?php echo $row_o['cod_oferta_materia']; ?>)">Matricular</a></td>
									</tr>
									<?php
								}
								?>
							</tbody>
						</table>
					</div>
					<?php
					break;

				case 'buscar_oferta2':
					$cod_materia = $_POST['mat'];
					$cod_periodo = $_POST['per'];
					$cod_sucursal = $_POST['suc'];
					?>
					<div class="no-more-tables">
						<table id="datatable_buscador" class="table table-striped table-sm">
							<thead>
								<tr align="center">
									<th>MATERIA</th>
									<th>CUPO</th>
									<th>FECHA</th>
									<th>DOCENTE</th>
									<th>TURNO</th>
									<th>ACCIÓN</th>
								</tr>
							</thead>
							<tbody>
								<?php
								$cod_oferta = 0;
								$sql_oferta = mysqli_query($con, "SELECT cod_oferta_materia, nombre_mat, sigla_mat, cupo_max_of, fecha_inicio_of, fecha_fin_of, nombre_per, 
									apellido_per, nombre_tur FROM tbl_oferta_materia, tbl_materia, tbl_periodo, tbl_turno, tbl_docente, tbl_persona 
									WHERE cod_materia_of = cod_materia AND cod_periodo_of = cod_periodo AND cod_turno_of = cod_turno AND cod_docente_of = cod_docente 
									AND cod_persona_doc = cod_persona AND cod_materia_of = $cod_materia AND cod_periodo_of = $cod_periodo AND estado_of = 1 
									AND cod_sucursal_of = $cod_sucursal ORDER BY cod_gestion_peri, cod_periodo DESC");
								while ($row_o = mysqli_fetch_array($sql_oferta)) {
									$cod_oferta = $row_o['cod_oferta_materia'];

									// CONTAR LOS ESTUDIANTES REGISTRADOS
									$cantidad_of = 0;
									$sql_his_cupo = mysqli_query($con, "SELECT cod_historico FROM tbl_historico WHERE cod_oferta_materia_his = $cod_oferta AND estado_his = 1");
									$cantidad_of = mysqli_num_rows($sql_his_cupo);
									?>
									<tr>
										<td data-title="MATERIA"><?php echo "<font size='2'>".$row_o['sigla_mat']." - ".$row_o['nombre_mat']."</font>"; ?></td>
										<td data-title="CUPO" align="center"><?php echo $cantidad_of." (".$row_o['cupo_max_of'].")"; ?></td>
										<td data-title="FECHA" align="center">
											<?php echo "<font size='2'><b>Desde: </b>".date_format(date_create($row_o['fecha_inicio_of']), "d-m-Y")
											."<br><b>Hasta: </b>".date_format(date_create($row_o['fecha_fin_of']), "d-m-Y")."</font>"; ?>
										</td>
										<td data-title="DOCENTE"><?php echo "<font size='2'>".$row_o['nombre_per']." ".$row_o['apellido_per']."</font>"; ?></td>
										<td data-title="TURNO"><?php echo $row_o['nombre_tur']; ?></td>
										<td data-title="ACCIÓN"><a class="btn btn-primary text-white" onclick="matricular(<?php echo $row_o['cod_oferta_materia']; ?>)">Matricular</a></td>
									</tr>
									<?php
								}
								?>
							</tbody>
						</table>
					</div>
					<?php
					break;

				case 'matricular':
					$data = array();
					$data['mensaje'] = "NO SE REALIZO EL PROCESO";
					$data['tipo'] = 2;

					$cod_oferta = $_POST['cod_of'];
					$cod_estudiante = $_POST['cod_est'];
					$cod_usuario = $_POST['cod_usu'];

					$cod_materia = 0;
					$cod_periodo = 0;
					$cupo = 0;
					$sql_oferta = mysqli_query($con, "SELECT cod_materia_of, cod_periodo_of, cupo_max_of FROM tbl_oferta_materia WHERE cod_oferta_materia = $cod_oferta");
					while ($row_o = mysqli_fetch_array($sql_oferta)) {
						$cod_materia = $row_o['cod_materia_of'];
						$cod_periodo = $row_o['cod_periodo_of'];
						$cupo = $row_o['cupo_max_of'];
					}

					// 1er VERIFICAR SI NO TIENE LA MATERIA REGISTRADA
					$sql_his1 = mysqli_query($con, "SELECT cod_historico FROM tbl_historico, tbl_oferta_materia WHERE cod_oferta_materia_his = cod_oferta_materia AND estado_his = 1 
						AND cod_materia_of = $cod_materia AND cod_estudiante_his = $cod_estudiante AND nota_final_his >= 51");
					if(mysqli_num_rows($sql_his1) == 0){

						// 1.1er VERIFICAR SI LA MATERIA ESTA EN EL MISMO PERIODO
						$sql_his1 = mysqli_query($con, "SELECT cod_historico FROM tbl_historico, tbl_oferta_materia WHERE cod_oferta_materia_his = cod_oferta_materia AND estado_his = 1 
						AND cod_materia_of = $cod_materia AND cod_estudiante_his = $cod_estudiante AND cod_periodo_of = $cod_periodo");
						if(mysqli_num_rows($sql_his1) == 0){

							// 2do VERIFICAR SI LA MATERIA TIENE PRE-REQUISITO Y SI YA LO APROBO
							$pre_res = 1;
							$cod_prerequisito = 0;
							$sql_pre = mysqli_query($con, "SELECT cod_prerequisito_mat FROM tbl_materia WHERE cod_materia = $cod_materia AND cod_prerequisito_mat > 0");
							if(mysqli_num_rows($sql_pre) > 0){
								while ($row_pr = mysqli_fetch_array($sql_pre)) {
									$cod_prerequisito = $row_pr['cod_prerequisito_mat'];
								}

								$sql_his1 = mysqli_query($con, "SELECT cod_historico FROM tbl_historico, tbl_oferta_materia WHERE cod_oferta_materia_his = cod_oferta_materia 
									AND estado_his = 1 AND cod_materia_of = $cod_prerequisito AND nota_final_his > 50 AND cod_estudiante_his = $cod_estudiante");
								if(mysqli_num_rows($sql_his1) == 0){
									$pre_res = 0;
								}
							}

							if($pre_res == 1){
								$cantidad_of = 0;
								// 3er VALIDAR EL CUPO MAXIMO DE LA OFERTA DE LA MATERIA
								$sql_his_cupo = mysqli_query($con, "SELECT cod_historico FROM tbl_historico WHERE cod_oferta_materia_his = $cod_oferta AND estado_his = 1");
								$cantidad_of = mysqli_num_rows($sql_his_cupo);

								if($cantidad_of < $cupo){
									// REGISTRAR EN EL HISTORICO
									$insert_his = mysqli_query($con, "INSERT INTO tbl_historico (cod_estudiante_his, cod_oferta_materia_his, nota_final_his) 
										VALUES ($cod_estudiante, $cod_oferta, NULL)");
									if(mysqli_affected_rows($con) > 0){
										// Obtener el codigo de la tabla
										$cod_tabla = 0;
										$sql_tabla = mysqli_query($con, "SELECT cod_tabla FROM tbl_tabla WHERE nombre_tabla = 'tbl_historico'");
										while ($row_ta = mysqli_fetch_array($sql_tabla)) {
											$cod_tabla = $row_ta['cod_tabla'];
										}
										// Obtener el ultimo cod_historico
										$codigo = 0;
										$sql_historico = mysqli_query($con, "SELECT cod_historico FROM tbl_historico ORDER BY cod_historico DESC LIMIT 0,1");
										while ($row_h = mysqli_fetch_array($sql_historico)) {
											$codigo = $row_h['cod_historico'];
										}
										// tbl_log
										$sql_log = mysqli_query($con, "INSERT INTO tbl_log(cod_tipolog_log, cod_tabla_log, codigo_log, cod_usuario_log) 
											VALUES(1, $cod_tabla, $codigo, $cod_usuario)");

										$data['mensaje'] = "ESTUDIANTE REGISTRADO EXITOSAMENTE";
										$data['tipo'] = 1;
									}
								}else{
									$data['mensaje'] = "EL CUPO MAXIMO DE LA OFERTA YA SE COMPLETO";
									$data['tipo'] = 2;
								}
							}else{
								$data['mensaje'] = "EL ESTUDIANTE NO TIENE EL PRE-REQUISITO APROBADO";
								$data['tipo'] = 2;
							}
						}else{
							$data['mensaje'] = "EL ESTUDIANTE YA TIENE REGISTRADA ESTA MATERIA EN EL PERIODO";
							$data['tipo'] = 2;
						}

					}else{
						$data['mensaje'] = "EL ESTUDIANTE YA LA TIENE LA MATERIA REGISTRADA";
						$data['tipo'] = 2;
					}

					echo json_encode($data);
					break;

				case 'retirar':
					$data = array();
					$data['mensaje'] = "NO SE REALIZO EL PROCESO";
					$data['tipo'] = 2;

					$cod_historico = $_POST['cod_his'];
					$cod_usuario = $_POST['cod_usu'];

					if($cod_historico > 0){
						// RETIRO DE UNA MATERIA
						$update_historico = mysqli_query($con, "UPDATE tbl_historico SET estado_his = 0 WHERE cod_historico = $cod_historico");
						if(mysqli_affected_rows($con) > 0){
							// Obtener el codigo de la tabla
							$cod_tabla = 0;
							$sql_tabla = mysqli_query($con, "SELECT cod_tabla FROM tbl_tabla WHERE nombre_tabla = 'tbl_historico'");
							while ($row_ta = mysqli_fetch_array($sql_tabla)) {
								$cod_tabla = $row_ta['cod_tabla'];
							}
							// tbl_log
							$sql_log = mysqli_query($con, "INSERT INTO tbl_log(cod_tipolog_log, cod_tabla_log, codigo_log, cod_usuario_log) 
								VALUES(3, $cod_tabla, $cod_historico, $cod_usuario)");

							$data['mensaje'] = "MATERIA RETIRADA EXITOSAMENTE";
							$data['tipo'] = 1;
						}
					}

					echo json_encode($data);
					break;

				case 'buscar_oferta3':
					$data = array();

					$cod_oferta = $_POST['cod_of'];

					$sql_oferta = mysqli_query($con, "SELECT nombre_mat, nombre_per, apellido_per, nombre_gest, nombre_peri, fecha_inicio_of, fecha_fin_of, nombre_tur, nombre_au 
						FROM tbl_oferta_materia, tbl_materia, tbl_turno, tbl_docente, tbl_persona, tbl_periodo, tbl_gestion, tbl_aula 
						WHERE cod_materia_of = cod_materia AND cod_turno_of = cod_turno AND cod_docente_of = cod_docente AND cod_persona_doc = cod_persona 
						AND cod_periodo_of = cod_periodo AND cod_gestion_peri = cod_gestion AND cod_aula_of = cod_aula AND cod_oferta_materia = $cod_oferta");
					while ($row_o = mysqli_fetch_array($sql_oferta)) {
						$data['materia_of'] = $row_o['nombre_mat'];
						$data['docente_of'] = $row_o['nombre_per']." ".$row_o['apellido_per'];
						$data['periodo_of'] = $row_o['nombre_gest']."-".$row_o['nombre_peri'];
						$data['fecha_inicio_of'] = date_format(date_create($row_o['fecha_inicio_of']), 'd-m-Y');
						$data['fecha_fin_of'] = date_format(date_create($row_o['fecha_fin_of']), 'd-m-Y');
						$data['turno_of'] = $row_o['nombre_tur'];
						$data['aula_of'] = $row_o['nombre_au'];
					}

					echo json_encode($data);
					break;

				case 'registrar_nota':
					$data = array();
					$data['mensaje'] = "NO SE REALIZO EL PROCESO";
					$data['tipo'] = 2;

					$cod_historico = $_POST['cod_his'];
					$nota_uno = $_POST['nota_uno'];
					$nota_dos = $_POST['nota_dos'];
					$nota_tres = $_POST['nota_tres'];
					$nota_final = $_POST['nota_final'];
					$cod_usuario = $_POST['cod_usu'];

					// INSERT INTO tbl_nota
					$insert_nota = mysqli_query($con, "INSERT INTO tbl_nota (cod_historico_not, nota_uno_not, nota_dos_not, nota_tres_not) VALUES($cod_historico, $nota_uno, $nota_dos, $nota_tres)");
					if (mysqli_affected_rows($con) > 0) {
						// Obtener el codigo de la tabla
						$cod_tabla = 0;
						$sql_tabla = mysqli_query($con, "SELECT cod_tabla FROM tbl_tabla WHERE nombre_tabla = 'tbl_nota'");
						while ($row_ta = mysqli_fetch_array($sql_tabla)) {
							$cod_tabla = $row_ta['cod_tabla'];
						}
						// Obtener el ultimo cod_nota
						$codigo = 0;
						$sql_nota = mysqli_query($con, "SELECT cod_nota FROM tbl_nota ORDER BY cod_nota DESC LIMIT 0,1");
						while ($row_h = mysqli_fetch_array($sql_nota)) {
							$codigo = $row_h['cod_nota'];
						}
						// tbl_log
						$sql_log = mysqli_query($con, "INSERT INTO tbl_log(cod_tipolog_log, cod_tabla_log, codigo_log, cod_usuario_log) 
							VALUES(1, $cod_tabla, $codigo, $cod_usuario)");

						$data['mensaje'] = "NOTA REGISTRADA EXITOSAMENTE";
						$data['tipo'] = 1;

						// UPDATE nota_final tbl_hsitorico
						$update_historico = mysqli_query($con, "UPDATE tbl_historico SET nota_final_his = $nota_final WHERE cod_historico = $cod_historico");
						if(mysqli_affected_rows($con) > 0){
							// Obtener el codigo de la tabla
							$cod_tabla = 0;
							$sql_tabla = mysqli_query($con, "SELECT cod_tabla FROM tbl_tabla WHERE nombre_tabla = 'tbl_historico'");
							while ($row_ta = mysqli_fetch_array($sql_tabla)) {
								$cod_tabla = $row_ta['cod_tabla'];
							}
							// tbl_log
							$sql_log = mysqli_query($con, "INSERT INTO tbl_log(cod_tipolog_log, cod_tabla_log, codigo_log, cod_usuario_log) 
								VALUES(2, $cod_tabla, $cod_historico, $cod_usuario)");
						}
					}

					echo json_encode($data);
					break;
			}
		}
	}else {
		header('Location:../index.php');
	}
?>