<?php
	include ('../conf/funciones.php');
	include('../conf/mysql.php');
	if (verificar_usuario()){

		$carnet = "";
		if(isset($_POST['carn'])){
			$carnet = $_POST['carn'];
			$sql_persona = mysqli_query($con, "SELECT cod_persona, nombre_per, apellido_per, carnet_per, complemento_carnet_per 
			FROM tbl_persona WHERE carnet_per LIKE '$carnet%' AND estado_per = 1 ORDER BY nombre_per, apellido_per");
		}else{
			$nombre = "";
			if(!empty($_POST['nom']))
				$nombre = $_POST['nom'];

			$apellido = "";
			if(!empty($_POST['ape']))
				$apellido = $_POST['ape'];

			$sql_persona = mysqli_query($con, "SELECT cod_persona, nombre_per, apellido_per, carnet_per, complemento_carnet_per 
				FROM tbl_persona WHERE nombre_per LIKE '%$nombre%' AND apellido_per LIKE '%$apellido%' 
				AND estado_per = 1 ORDER BY nombre_per, apellido_per");
		}

		if (mysqli_num_rows($sql_persona)) {
			$item = 1;
			while ($row_p = mysqli_fetch_array($sql_persona)) {
				$complemento = "";
				if($row_p['complemento_carnet_per'] != "" && $row_p['complemento_carnet_per'] != NULL)
					$complemento = "-".$row_p['complemento_carnet_per'];

				?>
				<a href="usuario_nuevo.php?cod=<?php echo $row_p['cod_persona']; ?>"><h6><?php echo $row_p['carnet_per'].$complemento." - ".$row_p['nombre_per']." ".$row_p['apellido_per']; ?></h6></a>
				<?php
			}
		}
	}else {
		header('Location:../index.php');
	}
?>