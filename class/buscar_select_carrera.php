<?php
	include ('../conf/funciones.php');
	include('../conf/mysql.php');
	if (verificar_usuario()){
	
		$codigo = "";
		$datos = "";
		if(isset($_GET['cod'])){
			$codigo = $_GET['cod'];

			$sucursal = 0;
			if(isset($_GET['suc']))
				$sucursal = $_GET['suc'];

			if(isset($_GET['tipo']) && isset($_GET['tipo']) == 'rep'){
				if($codigo == "todos"){
					$datos = "<option value='todos'>* TODOS *</option>";
				}else{
					$sql = mysqli_query($con, "SELECT cod_carrera, nombre_car, resolucion_ministerial_car FROM tbl_carrera WHERE estado_car = 1 AND cod_nivel_car = $codigo 
					AND cod_carrera IN (SELECT cod_carrera_carsuc FROM tbl_carrera_sucursal WHERE estado_carsuc = 1 AND cod_sucursal_carsuc = $sucursal)
					ORDER BY nombre_car");
					if(mysqli_num_rows($sql) > 0){
						while ($row = mysqli_fetch_array($sql)) {
							$datos = $datos.'<option value="'.$row['cod_carrera'].'">'.$row['nombre_car'].' - '.$row['resolucion_ministerial_car'].'</option>';
						}
						$datos = $datos."<option value='todos'>* TODOS *</option>";
					}
				}
			}else{
				$sql = mysqli_query($con, "SELECT cod_carrera, nombre_car, resolucion_ministerial_car FROM tbl_carrera WHERE estado_car = 1 AND cod_nivel_car = $codigo 
					AND cod_carrera IN (SELECT cod_carrera_carsuc FROM tbl_carrera_sucursal WHERE estado_carsuc = 1 AND cod_sucursal_carsuc = $sucursal)
					ORDER BY nombre_car");
				while ($row = mysqli_fetch_array($sql)) {
					$datos = $datos.'<option value="'.$row['cod_carrera'].'">'.$row['nombre_car'].' - '.$row['resolucion_ministerial_car'].'</option>';
				}
			}
		}
		echo "<option value=''>...</option>".$datos;
	}else {
		header('Location:../index.php');
	}
?>