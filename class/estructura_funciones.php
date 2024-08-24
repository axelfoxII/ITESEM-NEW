<?php
	include ('../conf/funciones.php');
	include('../conf/mysql.php');
	if (verificar_usuario()){

		if(isset($_REQUEST['tipo'])){
			$tipo = $_REQUEST['tipo'];

			switch ($tipo) {
				case 'requisito':
					$nombre = $_REQUEST['nom'];
					$cod_nivel = $_REQUEST['cod_niv'];
					$cod_requisito = $_REQUEST['cod_req'];
					$data = 0;

					if($cod_requisito == 0){
						$insert_requisito = mysqli_query($con, "INSERT INTO tbl_requisito_inscripcion (nombre_reqins, cod_nivel_reqins) 
							VALUES ('$nombre', $cod_nivel)");
						if(mysqli_affected_rows($con) > 0)
							$data = 1;
					}else{
						$update_requisito = mysqli_query($con, "UPDATE tbl_requisito_inscripcion SET nombre_reqins = '$nombre', cod_nivel_reqins = $cod_nivel 
							WHERE cod_requisito_inscripcion = $cod_requisito");
						if(mysqli_affected_rows($con) > 0)
							$data = 1;
					}
					echo $data;
					break;

				case 'requisito_buscar':
					$data = array();
					$cod_requisito = $_POST['cod_req'];

					$sql_requisito = mysqli_query($con, "SELECT nombre_reqins, cod_nivel_reqins FROM tbl_requisito_inscripcion WHERE cod_requisito_inscripcion = $cod_requisito");
					while ($row = mysqli_fetch_array($sql_requisito)) {
						$data['nombre_reqins'] =  $row['nombre_reqins'];
						$data['cod_nivel_reqins'] =  $row['cod_nivel_reqins'];
					}
					echo json_encode($data);
					break;

				case 'modalidad':
					$nombre = $_REQUEST['nom'];
					$cod_modalidad = $_REQUEST['cod_mod'];
					$data = 0;

					if($cod_modalidad == 0){
						$insert_requisito = mysqli_query($con, "INSERT INTO tbl_tipo_modalidad (nombre_tipmod) VALUES ('$nombre')");
						if(mysqli_affected_rows($con) > 0)
							$data = 1;
					}else{
						$update_requisito = mysqli_query($con, "UPDATE tbl_tipo_modalidad SET nombre_tipmod = '$nombre' WHERE cod_tipomodalidad = $cod_modalidad");
						if(mysqli_affected_rows($con) > 0)
							$data = 1;
					}
					echo $data;
					break;

				case 'modalidad_buscar':
					$data = array();
					$cod_modalidad = $_POST['cod_mod'];

					$sql_modalidad = mysqli_query($con, "SELECT nombre_tipmod FROM tbl_tipo_modalidad WHERE cod_tipomodalidad = $cod_modalidad");
					while ($row = mysqli_fetch_array($sql_modalidad)) {
						$data['nombre_modalidad'] =  $row['nombre_tipmod'];
					}
					echo json_encode($data);
					break;
			}
		}
	}else {
		header('Location:../index.php');
	}
?>