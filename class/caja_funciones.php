<?php
	include('../conf/mysql.php');
	date_default_timezone_set('America/La_Paz');

	if(isset($_REQUEST['funcion']) && !empty($_REQUEST['funcion'])) {
		$funcion = $_REQUEST['funcion'];

		switch ($funcion) {
			case 'estudiante':
				$cod_estudiante = $_REQUEST['cod_est'];
    		$data = array();
    		$sql_est = mysqli_query($con, "SELECT nombre_per, apellido_per, carnet_per, nombre_car, sigla_plan, cod_sucursal, nombre_suc, observacion_est 
    			FROM tbl_estudiante, tbl_persona, tbl_carrera, tbl_plan, tbl_sucursal 
    			WHERE cod_persona_est = cod_persona AND cod_carrera_est = cod_carrera AND cod_plan_est = cod_plan AND cod_sucursal = cod_sucursal_est 
    			AND cod_estudiante = $cod_estudiante");
    		while ($row_c = mysqli_fetch_array($sql_est)) {
    			$data['carnet_per'] =  $row_c['carnet_per'];
    			$data['nombre_per'] =  $row_c['nombre_per'];
    			$data['apellido_per'] =  $row_c['apellido_per'];
    			$data['nombre_car'] =  $row_c['nombre_car'];
    			$data['sigla_plan'] =  $row_c['sigla_plan'];
    			$data['nombre_suc'] =  $row_c['nombre_suc'];
    			$data['cod_sucursal'] =  $row_c['cod_sucursal'];
    			$data['observacion_est'] =  $row_c['observacion_est'];
    			$data['historico'] = 'Ver el historico: <a href="historico_notas.php?cod='.$cod_estudiante.'" target="_blank">'.$row_c['nombre_per'].' '.$row_c['apellido_per'].'</a>';
    		}
    		echo json_encode($data);
				break;

			case 'cuenta':
				// OBTENER EL SALDO DE LA CUENTA DEL ESTUDIANTE
    		$cod_estudiante = $_REQUEST['cod_est'];
    		$cod_usuario = $_REQUEST['cod_usu'];
    		$data = array();

    		$int_saldo = 0;
    		$sql_cuenta = mysqli_query($con, "SELECT SUM(precio_haber_cuenta) - SUM(precio_debe_cuenta)
					FROM tbl_cuenta_estudiante 
					WHERE cod_estudiante_cuenta = $cod_estudiante AND estado_cuenta = 1");
    		if(mysqli_num_rows($sql_cuenta) > 0){
					while($row_cuenta = mysqli_fetch_row($sql_cuenta)){
						$int_saldo = $row_cuenta[0];
					}
				}
				if($int_saldo < 0 && $int_saldo > -5)
					$int_saldo = 0;

				$data['cuenta'] = $int_saldo;
				$data['cuenta_text'] = $int_saldo;
				
				if($int_saldo >= 0 && $int_saldo < 5)
					$data['cuenta_text'] = 0;
				if($int_saldo >= 5){
					$data['cuenta_text'] = $int_saldo." BS. SALDO A FAVOR";
				}if($int_saldo < 0) {
					$int_saldo_text = abs($int_saldo);
					$data['cuenta_text'] = $int_saldo_text." BS. DEUDA PENDIENTE";
				}

				// if($int_saldo < 0){
				// 	// Insertar en el detalle La DEUDA PENDIENTE que tiene el estudiante
				// 	$sql_verificar_deuda = mysqli_query($con, "SELECT cod_articulo_nmov FROM tbl_detalle_movimiento, tbl_articulo
				// 		WHERE cod_articulo_nmov = cod_articulo AND nombre_art LIKE '%DEUDA PENDIENTE%' AND cod_usuario_nmov = $cod_usuario");
				// 	if(mysqli_num_rows($sql_verificar_deuda) == 0){
				// 		$cod_deuda_art = 0;
				// 		$deuda_art = "SELECT cod_articulo FROM tbl_articulo WHERE nombre_art LIKE '%DEUDA PENDIENTE%' ";
				// 		$res_deuda_art = mysqli_query($con, $deuda_art) or die (mysqli_error());
				// 		while ($row_deuda_art = mysqli_fetch_array($res_deuda_art)) {
				// 			$cod_deuda_art = $row_deuda_art['cod_articulo'];
				// 		}
				// 		mysqli_free_result($res_deuda_art);
				// 		$int_saldo = abs($int_saldo);
				// 		if($int_saldo > 0){
				// 			$query_pend = "INSERT INTO tbl_detalle_movimiento (cod_estudiante_nmov, cod_articulo_nmov,
				// 			precio_nmov, dscto_nmov, subtotal_nmov, cod_usuario_nmov)
				// 			VALUES ($cod_estudiante, $cod_deuda_art, abs($int_saldo), 0, abs($int_saldo), $cod_usuario)";
				// 			$res_query_pend = mysqli_query($con, $query_pend) or die (mysqli_error());
				// 		}
				// 	}
				// }

				echo json_encode($data);
				break;

			// GENERAR TABLA DEL DETALLE DE LA VENTA
    	case 'generar_tabla':
    		$cod_usuario = $_REQUEST['cod_usu'];
    		$data = array();

    		// Generar la tabla del detalle de la venta
    		$data['tabla'] = "";
    		$cod_articulo = 0;
    		$nombre_art = "";
    		$descripcion = "";
    		$cantidad = 0;
    		$precio = 0; $total_precio = 0;
    		$dscto = 0; $total_dscto = 0;
    		$sub = 0; $total_sub = 0;
    		$cod_nro_movimiento = 0;
    		$cod_tipoarticulo_art = 0;
    		$sql_detalle = mysqli_query($con, "SELECT cod_nro_movimiento, descripcion_nmov, cantidad_nmov, precio_nmov, dscto_nmov, subtotal_nmov, cod_articulo_nmov, tipo_nmov 
    			FROM tbl_detalle_movimiento WHERE cod_usuario_nmov = $cod_usuario");
    		while ($row_d = mysqli_fetch_array($sql_detalle)) {
    			$cod_articulo = $row_d['cod_articulo_nmov'];
    			$descripcion = $row_d['descripcion_nmov'];

    			if($row_d['tipo_nmov'] == 1 || $row_d['tipo_nmov'] == "1"){
    				// ARTICULOS
	    			// OBTNER NOMBRE DEL ARTICULO
	    			$sql_nombre = mysqli_query($con, "SELECT nombre_art, cod_tipoarticulo_art FROM tbl_articulo WHERE cod_articulo = $cod_articulo");
	    			while ($row_nom = mysqli_fetch_array($sql_nombre)) {
	    				$nombre_art = $row_nom['nombre_art'];
	    				$cod_tipoarticulo_art = $row_nom['cod_tipoarticulo_art'];
	    			}

	    			$cod_nro_movimiento = $row_d['cod_nro_movimiento'];
	    			$cantidad = $row_d['cantidad_nmov'];
	    			$precio = $row_d['precio_nmov'];
	    			$dscto = $row_d['dscto_nmov'];
	    			$sub = $row_d['subtotal_nmov'];

	    			$total_precio = $total_precio + $row_d['precio_nmov'];
	    			$total_dscto = $total_dscto + $row_d['dscto_nmov'];
	    			$total_sub = $total_sub + $row_d['subtotal_nmov'];
	    			$data['tabla'] = $data['tabla']."<tr>
	    				<td>".$nombre_art.$descripcion."</td>";
	    			if($cod_tipoarticulo_art == 3 && $precio >= 0 && $precio <= 1){
	    				$data['tabla'] = $data['tabla']."<td colspan='3'>
	    						<input type='hidden' id='cod_nro_mov' name='cod_nro_mov' value='".$cod_nro_movimiento."'>
									<div class='input-group'>
										<input class='form-control' id='acuenta' name='acuenta' type='text' placeholder='0.00' aria-label='0.00' aria-describedby='basic-addon2'>
										<div class='input-group-append'>
											<a class='btn btn-success' id='precio_editable'><i class='ion-checkmark-round' title='Guardar'></i></a>
										</div>
									</div>
	    					</td>
	    					<td class='text-center'> 
		    					<i class='ion-trash-a text-danger' title='Eliminar' onclick='delete_articulo(".$cod_nro_movimiento.")' ></i>
		    				</td>
		    			</tr>";
		    			$data['acuenta_activo'] = 1;
	    			}else{
		    			$data['tabla'] = $data['tabla']."<td>".$precio."</td>
		    				<td>".$cantidad."</td>
		    				<td>".$dscto."</td>
		    				<td>".$sub."</td>
		    				<td class='text-center'>
		    					<i class='ion-loop text-primary' title='Actualizar' data-toggle='modal' data-target='#modal_articulo' data-whatever='".$cod_nro_movimiento."' 
		    					onclick='update_articulo(`".$nombre_art."`, ".$cod_articulo.", ".$cantidad.", ".$precio.", ".$dscto.", ".$sub.")' ></i> 
		    					<i class='ion-trash-a text-danger' title='Eliminar' onclick='delete_articulo(".$cod_nro_movimiento.")' ></i>
		    				</td>
		    			</tr>";
		    			$data['acuenta_activo'] = 0;
	    			}
	    		}else{
	    			// OFERTAS DE MATERIAS
	    			$nombre_mat = ""; $sigla_mat = "";
	    			$sql_oferta = mysqli_query($con, "SELECT nombre_mat, sigla_mat FROM tbl_oferta_materia, tbl_materia WHERE cod_materia_of = cod_materia AND cod_oferta_materia = $cod_articulo");
	    			while ($row_of = mysqli_fetch_array($sql_oferta)) {
	    				$nombre_mat = $row_of['nombre_mat'];
	    				$sigla_mat = $row_of['sigla_mat'];
	    			}

	    			$data['tabla'] = $data['tabla']."<tr>
	    				<td>MAT: ".$nombre_mat." - ".$sigla_mat.$descripcion."</td>
	    				<td>0</td>
		    			<td>1</td>
		    			<td>0</td>
		    			<td>0</td>
		    			<td class='text-center'></td>
		    		</tr>";
	    		}
    		}

    		$data['total'] = number_format(($total_sub), 2, '.', '');
    		echo json_encode($data);
    		break;

    	// case 'materia_turno':
    	// 	$cod_mes = $_REQUEST['mes'];
    	// 	$cod_gestion = $_REQUEST['ges'];
    	// 	$cod_estudiante = $_REQUEST['cod_est'];
    	// 	$cod_turno = $_REQUEST['tur'];

    	// 	$data = "<option value=''>...</option>";

    	// 	$cod_materia = 0; $sigla_mat = "";
    	// 	$cod_articulo = 0; $nombre_art = "";
    	// 	$fecha_ini = "";
    	// 	$sql_oferta = mysqli_query($con, "SELECT cod_articulo, nombre_art, cod_materia_of, sigla_mat, fecha_inicio_of 
    	// 		FROM tbl_articulo, tbl_oferta_materia, tbl_materia 
    	// 		WHERE cod_oferta_materia_art = cod_oferta_materia AND estado_of = 1 AND cod_mes_of = $cod_mes AND cod_gestion_of = $cod_gestion 
    	// 		AND cod_materia_of = cod_materia AND cod_turno_of = $cod_turno 
    	// 		AND cod_materia_of IN (SELECT cod_materia FROM tbl_materia, tbl_estudiante WHERE cod_carrera_est = cod_carrera_mat 
    	// 			AND cod_estudiante = $cod_estudiante)");
    	// 	while ($row_of = mysqli_fetch_array($sql_oferta)) {
    	// 		$cod_materia = $row_of['cod_materia_of'];
    	// 		$sigla_mat = $row_of['sigla_mat'];
    	// 		$cod_articulo = $row_of['cod_articulo'];
    	// 		$nombre_art = $row_of['nombre_art'];
    	// 		$fecha_ini = $row_of['fecha_inicio_of'];

    	// 		// Verificar si el estudiante tiene una materia en el mismo periodo
    	// 		$sql_historico_1 = mysqli_query($con, "SELECT cod_historico FROM tbl_historico, tbl_oferta_materia 
    	// 			WHERE cod_oferta_materia = cod_oferta_materia_his AND estado_his = 1 AND cod_mes_of = $cod_mes AND fecha_inicio_of = '$fecha_ini' 
    	// 			AND cod_gestion_of = $cod_gestion AND cod_turno_of = $cod_turno AND cod_estudiante_his = $cod_estudiante");

    	// 		// Verificar si el estudiante ya tiene la materia
    	// 		$sql_historico_2 = mysqli_query($con, "SELECT cod_historico FROM tbl_historico, tbl_oferta_materia 
    	// 			WHERE cod_oferta_materia = cod_oferta_materia_his AND cod_materia_of = $cod_materia AND estado_his = 1 AND cod_estudiante_his = $cod_estudiante");

    	// 		if(mysqli_num_rows($sql_historico_1) == 0 && mysqli_num_rows($sql_historico_2) == 0){
    	// 			$data = $data.'<option value="'.$cod_articulo.'">'.$sigla_mat.' - '.$nombre_art.'</option>';
    	// 		}
    	// 	}
    	// 	echo $data;
    	// 	break;

    	// PRECIO CANTIDAD
    	case 'precio_articulo':
    		// $cod_articulo = $_REQUEST['cod_art'];
    		// $data = array();

    		// // Obtener el precio del articulo
    		// $sql_precio_art = mysqli_query($con, "SELECT cod_articulo, precio_art FROM tbl_articulo WHERE cod_articulo = $cod_articulo");
    		// while ($row_pre = mysqli_fetch_array($sql_precio_art)) {
    		// 	$data['precio'] = $row_pre['precio_art'];

    		// 	$cod_articulo = $row_pre['cod_articulo'];
    		// 	$cantidad = 0;
				// 	// CANTIDAD ARTICULO
				// 	$sql_inventario = mysqli_query($con, "SELECT cod_articulo_inv, SUM(cantidad_inv) AS cantidad FROM tbl_inventario 
				// 		WHERE cod_tipoinventario_inv = 1 AND cod_articulo_inv = $cod_articulo AND estado_inv = 1 GROUP BY cod_articulo_inv");
				// 	if(mysqli_num_rows($sql_inventario) > 0){
				// 		while ($row_in = mysqli_fetch_array($sql_inventario)) {
				// 			$cantidad = $row_in['cantidad'];
				// 		}
				// 	}
				// 	$sql_inventario = mysqli_query($con, "SELECT cod_articulo_inv, SUM(cantidad_inv) AS cantidad FROM tbl_inventario 
				// 		WHERE cod_tipoinventario_inv IN (2, 3) AND cod_articulo_inv = $cod_articulo AND estado_inv = 1 GROUP BY cod_articulo_inv");
				// 	if(mysqli_num_rows($sql_inventario) > 0){
				// 		while ($row_in = mysqli_fetch_array($sql_inventario)) {
				// 			$cantidad = $cantidad - $row_in['cantidad'];
				// 		}
				// 	}
				// 	$data['cantidad'] = $cantidad;
    		// }

    		// echo json_encode($data);

    		$data = array();
    		$cant = $_REQUEST['cant'];
    		$dscto = $_REQUEST['dscto'];

    		$cod_mov = $_REQUEST['cod_mov'];
    		$subt = 0;

    		// Obtener el precio del articulo
    		$sql_precio_art = mysqli_query($con, "SELECT precio_nmov FROM tbl_detalle_movimiento where cod_nro_movimiento = $cod_mov");
    		while ($row_pre = mysqli_fetch_array($sql_precio_art)) {
					$subt = $row_pre['precio_nmov'] * $cant;
    		}

    		if($dscto <= $subt){
    			$subt = $subt - $dscto;
    		}else{
    			$dscto = $subt;
    			$subt = 0;
    		}

    		$data['dscto'] = number_format($dscto, 2, '.', '');
    		$data['subt'] = number_format($subt, 2, '.', '');
    		echo json_encode($data);
    		break;

    	// UPDATE PRECIO ARTICULO
    	case 'update_articulo':
    		$cantidad = $_REQUEST['cant'];
    		$cod_movimiento = $_REQUEST['cod_mov'];
    		$precio = $_REQUEST['precio'];
    		$dscto = $_REQUEST['dscto'];
    		$subtotal = $_REQUEST['subtotal'];

    		// UPDATE TABLA tbl_detalle_movimiento
    		$update_detalle = mysqli_query($con, "UPDATE tbl_detalle_movimiento SET cantidad_nmov = $cantidad, precio_nmov = $precio, dscto_nmov = $dscto, subtotal_nmov = $subtotal 
    			WHERE cod_nro_movimiento = $cod_movimiento");
    		break;

    	// DETALLE ARTICULO
    	case 'detalle_articulo':
    		$cod_articulo = $_REQUEST['cod_art'];
    		$cod_usuario = $_REQUEST['cod_usu'];
    		$cod_estudiante = $_REQUEST['cod_est'];

    		// BUSCAR EL ARTICULO
    		$nombre_art = ""; $precio_art = 0;
    		$descripcion = "";
    		$sql_articulo = mysqli_query($con, "SELECT nombre_art, precio_art, cod_tipoarticulo_art FROM tbl_articulo WHERE cod_articulo = $cod_articulo");
    		while ($row_art = mysqli_fetch_array($sql_articulo)) {
    			$nombre_art = $row_art['nombre_art'];
    			$precio_art = $row_art['precio_art'];

    			if($row_art['cod_tipoarticulo_art'] == 3 && $row_art['precio_art'] == 1)
						$precio_art = 0;
					else
						$precio_art = $row_art['precio_art'];
					
					$descripcion = "";
					if($nombre_art == "CUOTA DEL PLAN"){
						// DESCRIPCION CUOTA
						$sql_cuota = mysqli_query($con, "SELECT cod_cuenta_estudiante FROM tbl_cuenta_estudiante WHERE cod_estudiante_cuenta = $cod_estudiante AND estado_cuenta = 1 
							AND cod_articulo_cuenta IN (SELECT cod_articulo FROM tbl_articulo WHERE nombre_art = 'CUOTA DEL PLAN')");
						$cantidad_cuota = mysqli_num_rows($sql_cuota) + 1;
						$descripcion = " - Nro ".$cantidad_cuota;

						// OBTENER PRECIO DEL PLAN
						$sql_plan = mysqli_query($con, "SELECT precio_plan FROM tbl_estudiante, tbl_plan WHERE cod_plan_est = cod_plan AND cod_estudiante = $cod_estudiante");
						while ($row_p = mysqli_fetch_array($sql_plan)) {
							$precio_art = $row_p['precio_plan'];
						}
					}

					if(strpos($nombre_art, "DERECHO REGISTRO") !== false){
						// OBTENER PRECIO TOTAL
						$precio_total = 0;
						$cod_sucursal = 0;
						$sql_plan = mysqli_query($con, "SELECT precio_total_plan, cod_sucursal_est FROM tbl_estudiante, tbl_plan 
							WHERE cod_plan_est = cod_plan AND cod_estudiante = $cod_estudiante");
						while ($row_p = mysqli_fetch_array($sql_plan)) {
							$precio_total = $row_p['precio_total_plan'];
							$cod_sucursal = $row_p['cod_sucursal_est'];
						}
						// OBTENER EL ARTICULO "PRECIO TOTAL AÑO" DE LA SUCURSAL
						$cod_articulo_t = 0;
						$descripcion_t = " - ".date('Y')." (CARGADO)";
						// $descripcion_t = " - 2022 (CARGADO)";
						$sql_art = mysqli_query($con, "SELECT cod_articulo FROM tbl_articulo WHERE nombre_art = 'PRECIO TOTAL AÑO' AND cod_sucursal_art = $cod_sucursal");
						while ($row_p = mysqli_fetch_array($sql_art)) {
							$cod_articulo_t = $row_p['cod_articulo'];

							// VERIFICAR SI YA ESTA CARGADO
							$sql_ver_cuenta = mysqli_query($con, "SELECT cod_cuenta_estudiante FROM tbl_cuenta_estudiante WHERE cod_estudiante_cuenta = $cod_estudiante AND estado_cuenta = 1 
								AND descripcion_cuenta = '$descripcion_t'");
							if(mysqli_num_rows($sql_ver_cuenta) == 0){
								// INSERT EN LA TABLA tbl_detalle_movimiento
				    		$insert_detalle = mysqli_query($con, "INSERT INTO tbl_detalle_movimiento(cod_estudiante_nmov, cod_articulo_nmov, descripcion_nmov, precio_nmov, dscto_nmov, subtotal_nmov, cod_usuario_nmov, tipo_nmov) 
				    			VALUES ($cod_estudiante, $cod_articulo_t, '$descripcion_t', $precio_total, 0, 0, $cod_usuario, 1)");
				    	}

						}
					}

    		}

    		// INSERT EN LA TABLA tbl_detalle_movimiento
    		$insert_detalle = mysqli_query($con, "INSERT INTO tbl_detalle_movimiento(cod_estudiante_nmov, cod_articulo_nmov, descripcion_nmov, precio_nmov, dscto_nmov, subtotal_nmov, cod_usuario_nmov, tipo_nmov) 
    			VALUES ($cod_estudiante, $cod_articulo, '$descripcion', $precio_art, 0, $precio_art, $cod_usuario, 1)");
    		break;

    	case 'precio_editable':
    		$acuenta = $_REQUEST['acuenta'];
    		$cod_nro_mov = $_REQUEST['cod_mov'];

    		$res_prec_detalle = mysqli_query($con, "UPDATE tbl_detalle_movimiento SET precio_nmov = $acuenta, subtotal_nmov = $acuenta
		        WHERE cod_nro_movimiento = $cod_nro_mov");
    		break;

    	case 'delete_articulo':
    		$cod_nro_mov = $_REQUEST['cod_mov'];

    		$update_detalle = mysqli_query($con, "DELETE FROM tbl_detalle_movimiento WHERE cod_nro_movimiento = $cod_nro_mov");
    		break;

    	case 'verificar_materia':
    		$data = array();
    		$cod_usuario = $_REQUEST['cod_usu'];
    		$cod_estudiante = $_REQUEST['cod_est'];
    		$periodo = $_REQUEST['periodo'];
    		$grupo = $_REQUEST['grupo'];
    		$modalidad = $_REQUEST['modalidad'];
    		$contador_mat = 0;

    		// VACIAR EL DETALLE DE LA TABLA tbl_detalle_movimiento
				$delete_det = mysqli_query($con, "DELETE FROM tbl_detalle_movimiento WHERE cod_usuario_nmov = $cod_usuario AND tipo_nmov = 2");

    		$descripcion = "";
    		$sql_grupo = mysqli_query($con, "SELECT nombre_tur, nombre_gru FROM tbl_grupo, tbl_turno 
    			WHERE cod_turno_gru = cod_turno AND estado_gru = 1 AND cod_grupo = $grupo ORDER BY cod_turno, nombre_gru");
    		while ($row_t = mysqli_fetch_array($sql_grupo)) {
    			$descripcion = " / ".$row_t['nombre_tur']." - ".$row_t['nombre_gru'];
    		}

    		// OBTENER LA CARRERA DEL ESTUDIANTE
    		$cod_carrera = 0;
    		$sql_estudiante = mysqli_query($con, "SELECT cod_carrera_est FROM tbl_estudiante WHERE cod_estudiante = $cod_estudiante");
    		while ($row_e = mysqli_fetch_array($sql_estudiante)) {
    			$cod_carrera = $row_e['cod_carrera_est'];
    		}

    		// OBTENER LAS MATERIAS CON LOS DATOS SELECCIONADOS
    		$cod_oferta = 0; $cupo = 0; $cod_periodo = 0; $cod_materia = 0;
    		$sql_oferta = mysqli_query($con, "SELECT cod_oferta_materia, cod_materia, cupo_max_of, cod_periodo_of FROM tbl_oferta_materia, tbl_materia WHERE cod_materia_of = cod_materia 
    			AND cod_carrera_mat = $cod_carrera AND cod_periodo_of = $periodo AND cod_grupo_of = $grupo AND cod_tipomodalidad_of = $modalidad 
    			AND estado_of = 1 ORDER BY cod_materia");
    		if (mysqli_num_rows($sql_oferta) > 0) {
    			while ($row_o = mysqli_fetch_array($sql_oferta)) {
    				$cod_oferta = $row_o['cod_oferta_materia'];
    				$cod_periodo = $row_o['cod_periodo_of'];
    				$cupo = $row_o['cupo_max_of'];
    				$cod_materia = $row_o['cod_materia'];

    				// 1er VERIFICAR SI NO TIENE LA MATERIA REGISTRADA
						$sql_his1 = mysqli_query($con, "SELECT cod_historico FROM tbl_historico, tbl_oferta_materia WHERE cod_oferta_materia_his = cod_oferta_materia AND estado_his = 1 
							AND cod_materia_of = $cod_materia AND cod_estudiante_his = $cod_estudiante");
						if(mysqli_num_rows($sql_his1) == 0){
							// 2do VERIFICAR SI LA MATERIA TIENE PRE-REQUISITO Y SI YA LO APROBO
							$pre_res = 1;
							// $cod_prerequisito = 0;
							// $sql_pre = mysqli_query($con, "SELECT cod_prerequisito_mat FROM tbl_materia WHERE cod_materia = $cod_materia AND cod_prerequisito_mat > 0");
							// if(mysqli_num_rows($sql_pre) > 0){
							// 	while ($row_pr = mysqli_fetch_array($sql_pre)) {
							// 		$cod_prerequisito = $row_pr['cod_prerequisito_mat'];
							// 	}

							// 	$sql_his1 = mysqli_query($con, "SELECT cod_historico FROM tbl_historico, tbl_oferta_materia WHERE cod_oferta_materia_his = cod_oferta_materia 
							// 		AND estado_his = 1 AND cod_materia_of = $cod_prerequisito AND nota_final_his > 60 AND cod_estudiante_his = $cod_estudiante");
							// 	if(mysqli_num_rows($sql_his1) == 0){
							// 		$pre_res = 0;
							// 	}
							// }

							if($pre_res == 1){
								$cantidad_of = 0;
								// 3er VALIDAR EL CUPO MAXIMO DE LA OFERTA DE LA MATERIA
								$sql_his_cupo = mysqli_query($con, "SELECT cod_historico FROM tbl_historico WHERE cod_oferta_materia_his = $cod_oferta AND estado_his = 1");
								$cantidad_of = mysqli_num_rows($sql_his_cupo);

								if($cantidad_of < $cupo){
									// INSERT EN LA TABLA tbl_detalle_movimiento
			    				$insert_detalle = mysqli_query($con, "INSERT INTO tbl_detalle_movimiento(cod_estudiante_nmov, cod_articulo_nmov, descripcion_nmov, precio_nmov, dscto_nmov, subtotal_nmov, 
			    					cod_usuario_nmov, tipo_nmov) 
			    					VALUES ($cod_estudiante, $cod_oferta, '$descripcion', 0, 0, 0, $cod_usuario, 2)");
									if(mysqli_affected_rows($con) > 0){
										$contador_mat = $contador_mat + 1;
										$data['resultado'] = 1;
									}
								}
							}
						}
    			}

    			$data['mensaje'] = $contador_mat." MATERIAS HABILITADAS PARA REGISTRAR.";
    			if($contador_mat == 0){
    				$data['mensaje'] = "NO TIENE MATERIAS HABILITADAS.";
    			}
    		}else{
    			$data['resultado'] = 0;
    			$data['mensaje'] = "NO SE ENCONTRARON MATERIAS OFERTADAS.";
    		}

    		echo json_encode($data);
    		break;

    	case 'contar_materias':
    		$cod_estudiante = $_REQUEST['cod_est'];
    		$periodo = $_REQUEST['periodo'];
    		if($periodo != "" && $periodo != 0){
    			$item = 1;
	    		$sql_materia = mysqli_query($con, "SELECT cod_historico, nombre_mat, nota_final_his FROM tbl_historico, tbl_oferta_materia, tbl_materia 
	    			WHERE cod_oferta_materia_his = cod_oferta_materia AND cod_materia_of = cod_materia AND cod_periodo_of = $periodo AND cod_estudiante_his = $cod_estudiante AND estado_his = 1");
	    		?>
					<table class="table table-bordered table-sm text-xsmall">
						<tbody>
							<?php
							$estado = "";
							if(mysqli_num_rows($sql_materia) > 0){
								?>
								<tr class="table-primary" align="center">
									<td colspan="3">MATERIAS REGISTRADAS EN EL PERIODO : <b>(<?php echo mysqli_num_rows($sql_materia); ?>)</b></td>
								</tr>
								<?php
								while ($row_h = mysqli_fetch_array($sql_materia)) {
									if($row_h['nota_final_his'] > 50)
										$estado = "<div class='text-success'>".$row_h['nota_final_his']."</div>";
									elseif($row_h['nota_final_his'] == "")
										$estado = "<div class='text-primary'>INSCRITA</div>";
									elseif($row_h['nota_final_his'] < 51)
										$estado = "<div class='text-danger'>".$row_h['nota_final_his']."</div>";
									?>
									<tr>
										<td><?php echo $item++; ?></td>
										<td><?php echo $row_h['nombre_mat']; ?></td>
										<td align="center"><?php echo $estado; ?></td>
									</tr>
									<?php
								}
							}else{
								?>
								<tr class="table-danger" align="center">
									<td>NO TIENE MATERIAS REGISTRADAS</b></td>
								</tr>
								<?php
							}

							// VER EL GRUPO DEL ESTUDIANTE registrado anteriormente
							$sql_grupo = mysqli_query($con, "SELECT MAX(cod_periodo_of) AS cod_periodo, nombre_tur, nombre_gru, nombre_tipmod 
								FROM tbl_historico, tbl_oferta_materia, tbl_turno, tbl_grupo, tbl_tipo_modalidad 
								WHERE cod_oferta_materia_his = cod_oferta_materia AND cod_turno_of = cod_turno AND cod_grupo_of = cod_grupo AND cod_tipomodalidad_of = cod_tipomodalidad 
								AND cod_estudiante_his = $cod_estudiante GROUP BY nombre_gru ORDER BY cod_periodo DESC LIMIT 0,1");
							if(mysqli_num_rows($sql_grupo) > 0){
								while ($row_g = mysqli_fetch_array($sql_grupo)) {
									?>
									<tr class="table-primary" align="center">
										<td colspan="3" class="text-bold">GRUPO: <?php echo $row_g['nombre_tur']." / ".$row_g['nombre_gru']." / ".$row_g['nombre_tipmod']; ?></td>
									</tr>
									<?php
								}
							}else{
								?>
								<tr class="table-danger" align="center">
									<td class="text-bold">TURNO Y GRUPO NO REGISTRADO</b></td>
								</tr>
								<?php
							}
							?>
						</tbody>
					</table>
	    		<?php
	    	}
    		break;

		}
	}
?>