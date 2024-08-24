<?php
	include('../conf/mysql.php');
	// date_default_timezone_set('America/La_Paz');

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

		}
	}
?>