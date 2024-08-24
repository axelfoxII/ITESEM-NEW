<?php
include ('../conf/funciones.php');
include("../conf/mysql.php");
date_default_timezone_set('America/La_Paz');
if (verificar_usuario()){
	//si el usuario es verificado, se elimina los valores,se destruye la sesion y volvemos al formulario de ingreso
	session_unset();
	session_destroy();
	header('Location:../inicio.html');
} else {
	//si el usuario no es verificado vuelve al formulario de ingreso
	header('Location:../inicio.html');
}
?>
