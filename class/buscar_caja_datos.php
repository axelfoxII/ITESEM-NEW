<?php
	include ('../conf/funciones.php');
	include('../conf/mysql.php');
	if (verificar_usuario()){

		$carnet = "";
		$sucursal = $_POST['suc'];
		if(isset($_POST['carn'])){
			$carnet = $_POST['carn'];
			$sql_estudiante = mysqli_query($con, "SELECT cod_estudiante,nombre_per,apellido_per,carnet_per,nombre_car 
			FROM tbl_persona,tbl_estudiante,tbl_carrera WHERE carnet_per LIKE '$carnet%' AND estado_est = 1 
			AND cod_persona_est = cod_persona AND cod_carrera_est = cod_carrera AND cod_sucursal_est = $sucursal 
			ORDER BY nombre_per,apellido_per");
		}else{
			$nombre = "";
			if(!empty($_POST['nom']))
				$nombre = $_POST['nom'];

			$apellido = "";
			if(!empty($_POST['ape']))
				$apellido = $_POST['ape'];

			$sql_estudiante = mysqli_query($con, "SELECT cod_estudiante,nombre_per,apellido_per, carnet_per,nombre_car 
			FROM tbl_persona,tbl_estudiante,tbl_carrera 
			WHERE nombre_per LIKE '%$nombre%' AND apellido_per LIKE '%$apellido%' AND estado_est = 1 
			AND cod_persona_est = cod_persona AND cod_carrera_est = cod_carrera AND cod_sucursal_est = $sucursal 
			ORDER BY nombre_per,apellido_per");
		}

		if (mysqli_num_rows($sql_estudiante)) {
			$item = 1;
			while ($row_e = mysqli_fetch_array($sql_estudiante)){ 
				?>
				<a onclick="obtner_datos(<?php echo $row_e['cod_estudiante']; ?>)" class="text-primary" style="cursor: pointer"><h6><?php echo $row_e['carnet_per']." - <b>".$row_e['nombre_per']." ".$row_e['apellido_per']."</b> - ".$row_e['nombre_car']; ?></h6></a>
				<?php
			}
		}
	}else {
		header('Location:../index.php');
	}
?>