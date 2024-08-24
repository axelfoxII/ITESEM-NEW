<?php
	if (!empty($_POST['nombre']))
	{
		$nombre=$_POST['nombre'];		
	}	
	else
	{
		$nombre='Certificado_Oficial';		
	}	
	
    header('Pragma: public');
    header('Expires: Sat, 26 Jul 1997 05:00:00 GMT');  
    header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
    header('Cache-Control: no-store, no-cache, must-revalidate');
    header('Cache-Control: pre-check=0, post-check=0, max-age=0');
    header('Pragma: no-cache');
    header('Expires: 0');
    header('Content-Transfer-Encoding: none');
    header('Content-Type: application/vnd.ms-excel'); 
    header('Content-type: application/x-msexcel');
   
    header('Content-Disposition: attachment; filename="'.$nombre.'.xls"');
	
echo utf8_decode($_POST['datos_a_enviar']);
?>
