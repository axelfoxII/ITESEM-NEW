<?php
	include ('../conf/funciones.php');
	include('../conf/mysql.php');
	if (verificar_usuario()){
	
		$datos = "";
		if(isset($_REQUEST['tipo'])){
			$tipo = $_REQUEST['tipo'];

			switch ($tipo) {
				case 'colegio':
					$datos = "";
					if(isset($_GET['cod_t']) && isset($_GET['cod_d']) && $_GET['cod_t'] != "" && $_GET['cod_d'] != ""){
						$cod_t = $_GET['cod_t'];
						$cod_d = $_GET['cod_d'];
						$sql = mysqli_query($con, "SELECT cod_colegio, nombre_col, canton_col FROM tbl_colegio WHERE estado_col = 1 AND cod_tipocolegio_col = $cod_t 
							AND cod_departamento_col = $cod_d");
						while ($row = mysqli_fetch_array($sql)) {
							$datos = $datos.'<option value="'.$row['cod_colegio'].'">'.$row['nombre_col'].' - ('.$row['canton_col'].')</option>';
						}
					}
					echo "<option value=''>...</option>".$datos;
					break;

				case 'prerequisito':
					$datos = "";
					if(isset($_GET['cod']) && $_GET['cod'] != ""){
						$carrera = $_GET['cod'];
						$sql = mysqli_query($con, "SELECT cod_materia, sigla_mat, nombre_mat FROM tbl_materia WHERE estado_mat = 1 AND cod_carrera_mat = $carrera");
						while ($row = mysqli_fetch_array($sql)) {
							$datos = $datos.'<option value="'.$row['cod_materia'].'">'.$row['sigla_mat'].' - '.$row['nombre_mat'].'</option>';
						}
					}
					echo "<option value='0'>*SIN PRE-REQUISITO*</option>".$datos;
					break;

				case 'periodo':
					$datos = "";
					if(isset($_GET['tipper']) && $_GET['tipper'] != "" && isset($_GET['gest']) && $_GET['gest'] != ""){
						$tipo_periodo = $_GET['tipper'];
						$gestion = $_GET['gest'];
						$sql = mysqli_query($con, "SELECT cod_periodo, nombre_peri, nombre_gest FROM tbl_periodo, tbl_gestion 
							WHERE cod_gestion_peri = cod_gestion AND cod_tipoperiodo_peri = $tipo_periodo AND cod_gestion = $gestion ORDER BY cod_tipoperiodo_peri");
						while ($row = mysqli_fetch_array($sql)) {
							$datos = $datos.'<option value="'.$row['cod_periodo'].'">'.$row['nombre_gest'].' - '.$row['nombre_peri'].'</option>';
						}
					}
					echo "<option value=''>...</option>".$datos;
					break;

				case 'aula':
					$datos = "";
					if(isset($_GET['suc']) && $_GET['suc'] != ""){
						$sucursal = $_GET['suc'];
						$sql = mysqli_query($con, "SELECT cod_aula, nombre_au, capacidad_au FROM tbl_aula 
							WHERE cod_sucursal_au = $sucursal ORDER BY nombre_au");
						while ($row = mysqli_fetch_array($sql)) {
							$datos = $datos.'<option value="'.$row['cod_aula'].'">'.$row['nombre_au'].' - ('.$row['capacidad_au'].')</option>';
						}
					}
					echo "<option value=''>...</option>".$datos;
					break;

				case 'cupo_aula':
					$cupo = 0;
					if(isset($_GET['aula']) && $_GET['aula'] != ""){
						$cod_aula = $_GET['aula'];
						$sql = mysqli_query($con, "SELECT capacidad_au FROM tbl_aula WHERE cod_aula = $cod_aula");
						while ($row = mysqli_fetch_array($sql)) {
							$cupo = $row['capacidad_au'];
						}
					}
					echo $cupo;
					break;

				case 'fechas_periodo':
					$data = array();
					$data['fecha_inicio'] = "";
					$data['fecha_fin'] = "";
					if(isset($_POST['per'])){
						$cod_periodo = $_POST['per'];

						$sql_per = mysqli_query($con, "SELECT fecha_ini_peri, fecha_fin_peri FROM tbl_periodo WHERE cod_periodo = $cod_periodo");
						while ($row_p = mysqli_fetch_array($sql_per)) {
							$data['fecha_inicio'] = date_format(date_create($row_p['fecha_ini_peri']), "d-m-Y");
							$data['fecha_fin'] = date_format(date_create($row_p['fecha_fin_peri']), "d-m-Y");
						}
					}

					echo json_encode($data);
					break;

				case 'articulo_caja':
					$datos = "";
					if(isset($_GET['suc']) && $_GET['suc'] != ""){
						$sucursal = $_GET['suc'];
						$sql = mysqli_query($con, "SELECT cod_articulo, nombre_art, cod_tipoarticulo_art FROM tbl_articulo 
							WHERE estado_art = 1 AND cod_sucursal_art = $sucursal ORDER BY nombre_art");
						while ($row = mysqli_fetch_array($sql)) {
							$disabled = "";
							$detalle = "";
							if($row['cod_tipoarticulo_art'] == 1){
								// Verificar si hay unidades disponibles del articulo
								$cod_articulo = $row['cod_articulo'];
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
								if($cantidad <= 0){
									$disabled = "disabled";
									$detalle = " **(NO DISPONIBLE)**";
								}else{
									$detalle = " (CANT. DISP. = ".$cantidad.")";
								}
							}
							$datos = $datos.'<option '.$disabled.' value="'.$row['cod_articulo'].'">'.$row['nombre_art'].$detalle.'</option>';
						}
					}
					echo "<option value=''>...</option>".$datos;
					break;

				case 'plan':
					$datos = "";
					if(isset($_GET['cod_s']) && $_GET['cod_s'] != "" && isset($_GET['cod_n']) && $_GET['cod_n'] != ""){
						$sucursal = $_GET['cod_s'];
						$nivel = $_GET['cod_n'];
						$sql = mysqli_query($con, "SELECT cod_plan, sigla_plan, descripcion_plan, precio_total_plan FROM tbl_plan 
							WHERE estado_plan = 1 AND cod_sucursal_plan = $sucursal AND cod_nivel_plan = $nivel ORDER BY sigla_plan");
						while ($row = mysqli_fetch_array($sql)) {
							$datos = $datos.'<option value="'.$row['cod_plan'].'">'.$row['sigla_plan'].' / '.$row['descripcion_plan'].' / ('.$row['precio_total_plan'].' Bs.)</option>';
						}
					}
					echo "<option value=''>...</option>".$datos;
					break;

				case 'grupo':
					$datos = "";
					if(isset($_GET['cod_est']) && $_GET['cod_est'] != "" && isset($_GET['tur']) && $_GET['tur'] != ""){
						$cod_estudiante = $_GET['cod_est'];
						$turno = $_GET['tur'];
						$cod_nivel = 0;
						$sql_nivel = mysqli_query($con, "SELECT cod_nivel_car FROM tbl_estudiante, tbl_carrera WHERE cod_carrera_est = cod_carrera AND cod_estudiante = $cod_estudiante");
						while ($row_n = mysqli_fetch_array($sql_nivel)) {
							$cod_nivel = $row_n['cod_nivel_car'];
						}

						$sql = mysqli_query($con, "SELECT cod_grupo, nombre_gru FROM tbl_grupo WHERE cod_turno_gru = $turno AND cod_nivel_gru = $cod_nivel AND estado_gru = 1 ORDER BY nombre_gru");
						while ($row = mysqli_fetch_array($sql)) {
							$datos = $datos.'<option value="'.$row['cod_grupo'].'">'.$row['nombre_gru'].'</option>';
						}
					}
					echo "<option value=''>...</option>".$datos;
					break;

				case 'grupo_of':
					$datos = "";
					if(isset($_GET['tur']) && $_GET['tur'] != ""){
						$turno = $_GET['tur'];

						$sql = mysqli_query($con, "SELECT cod_grupo, nombre_gru FROM tbl_grupo WHERE cod_turno_gru = $turno AND estado_gru = 1 ORDER BY nombre_gru");
						while ($row = mysqli_fetch_array($sql)) {
							$datos = $datos.'<option value="'.$row['cod_grupo'].'">'.$row['nombre_gru'].'</option>';
						}
					}
					echo "<option value=''>...</option>".$datos;
					break;
			}
		}
	}else {
		header('Location:../index.php');
	}
?>