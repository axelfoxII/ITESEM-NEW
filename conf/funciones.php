<?php
//funcion para conectar a la base de datos y verificar la existencia del usuario
function conexiones($usuario, $clave) {
	include("mysql.php");
	date_default_timezone_set('America/La_Paz');

	//sentencia sql para consultar el nombre del usuario
	$sql = "SELECT cod_usuario, contrasena_us, control_us FROM tbl_usuario WHERE (nombre_us = '$usuario' OR correo_us = '$usuario') and estado_us='1'";
	//ejecucion de la sentencia anterior
	$ejecutar_sql=mysqli_query($con,$sql) or die (mysqli_error());
	//si existe inicia una sesion y guarda el nombre del usuario
	if (mysqli_num_rows($ejecutar_sql)!=0){
		$password_db = "0";
		$cod_usuario = "0"; $control_us = "0";
		while ($row_usuario = mysqli_fetch_array($ejecutar_sql)) {
			$password_db = $row_usuario['contrasena_us'];
			$cod_usuario = $row_usuario['cod_usuario'];
			$control_us = $row_usuario['control_us'];
		}
		if (crypt($clave, $password_db) == $password_db){
			//inicio de sesion
			session_start();
			//configurar un elemento usuario dentro del arreglo global $_SESSION
			$_SESSION['cod_usuario']=$cod_usuario;
			$_SESSION['control_us'] = $control_us;
			return true;
		}else {
			return false;
		}

	} else {
		//retornar falso
		return false;
	}
}
//funcion para verificar que dentro del arreglo global $_SESSION existe el nombre del usuario
function verificar_usuario(){
	//continuar una sesion iniciada
	session_start();
	//comprobar la existencia del usuario
	if (isset($_SESSION["cod_usuario"]) && $_SESSION["cod_usuario"] > 0){
		return true;
	}
}
?>
