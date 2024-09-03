<?php
/*ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);*/

include ('conf/funciones.php');
//usuario y clave pasados por el formulario
$usuario = $_POST['usuario'];
$clave = $_POST['contrasena'];
//usa la funcion conexiones() que se ubica dentro de funciones.php
if (conexiones($usuario, $clave)){
	//si es valido accedemos a ingreso.php
	// header('Location:view/inicio.php');
	echo "1";
} else {
	//si no es valido volvemos al formulario inicial
	// header('Location: inicio.html');
	echo "0";
}
?>
