<?php
	include ('../conf/funciones.php');
	include('../conf/mysql.php');
	if (verificar_usuario()){

		$tipo = "";
		if(isset($_REQUEST['funcion']))
			$tipo = $_REQUEST['funcion'];

		switch ($tipo) {
			case 'verificar_ci':
				$carnet = $_POST['carnet'];
				$data = array();

				// VERIFICAR SI EL CARNET ESTA EN LA TABLA tbl_persona
				$sql_persona = mysqli_query($con, "SELECT cod_persona, nombre_per, apellido_per, cod_expedido_per, fecha_nacimiento_per, celular_per, celular2_per, correo_per, direccion_per, 
					cod_sexo_per, cod_pais_per, cod_departamento_per 
					FROM tbl_persona WHERE carnet_per = '$carnet' AND estado_per = 1");
				if(mysqli_num_rows($sql_persona) > 0){
					while ($row_p = mysqli_fetch_array($sql_persona)) {
						$data['cod_persona'] = $row_p['cod_persona'];
						$data['nombre'] = $row_p['nombre_per'];
						$data['apellido'] = $row_p['apellido_per'];
						$data['expedido'] = $row_p['cod_expedido_per'];
						$data['fecha_nac'] = date_format(date_create($row_p['fecha_nacimiento_per']), 'd-m-Y');
						$data['celular'] = $row_p['celular_per'];
						$data['celular2'] = $row_p['celular2_per'];
						$data['correo'] = $row_p['correo_per'];
						$data['direccion'] = $row_p['direccion_per'];
						$data['sexo'] = $row_p['cod_sexo_per'];
						$data['pais'] = $row_p['cod_pais_per'];
						$data['departamento'] = $row_p['cod_departamento_per'];
					}
				}else{
					$data['cod_persona'] = '0';
				}

				echo json_encode($data);
				break;

			case 'verificar_est':
				$cod_persona = $_POST['cod_persona'];
				$carrera = $_POST['carrera'];

				// VERIFICAR SI ESTUDIANTE YA ESTA REGISTRADO
				$sql_estudiante = mysqli_query($con, "SELECT cod_estudiante FROM tbl_estudiante WHERE cod_carrera_est = $carrera AND cod_persona_est = $cod_persona AND estado_est = 1");
				if(mysqli_num_rows($sql_estudiante) > 0){
					?>
					<div class="col-sm-2"></div>
					<div class="col-sm-8">
						<div class="alert alert-danger text-center" role="alert">
							EL ESTUDIANTE YA ESTA REGISTRADO EN LA CARRERA SELECCIONADA. <br><b>NO PUEDE REGISTRAR AL ESTUDIANTE.</b>
						</div>
					</div>
					<?php
				}else{
					echo "0";
				}
				break;
		}
	}else {
		header('Location:../index.php');
	}
?>