<?php
	include ('../conf/funciones.php');
	include('../conf/mysql.php');
	if (verificar_usuario()){
	
		$codigo = "";
		$datos = "";
		if(isset($_GET['cod'])){
			$codigo = $_GET['cod'];
			$sql = mysqli_query($con, "SELECT cod_materia, nombre_mat, sigla_mat FROM tbl_materia WHERE estado_mat = 1 AND cod_carrera_mat = $codigo");
			while ($row = mysqli_fetch_array($sql)) {
				$datos = $datos.'<option value="'.$row['cod_materia'].'">'.$row['sigla_mat'].' - '.$row['nombre_mat'].'</option>';
			}
		}
		echo "<option value=''>...</option>".$datos;
	}else {
		header('Location:../index.php');
	}
?>