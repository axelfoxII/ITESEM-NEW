<?php
	//$bd_host = "itesem.edu.bo:2083";
	$bd_host = "localhost";
	// $bd_usuario = "itesemqmd_itesem";
	// $bd_password = "BD_itesem2023";
	// $bd_base = "itesemqmd_itesem";
	$bd_usuario = "root";
	$bd_password = "";
	$bd_base = "instituto_bd";
	$con = mysqli_connect($bd_host, $bd_usuario, $bd_password,$bd_base);
	$acentos = $con->query("SET NAMES 'utf8'");
/* 	
 admin
70289641alejandro */
?>

