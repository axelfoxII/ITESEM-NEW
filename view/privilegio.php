<?php
include ('../conf/funciones.php');
include("../conf/mysql.php");
$cod_usuario = 0;
if (verificar_usuario()){
	$cod_usuario = $_SESSION['cod_usuario'];
	$nombre_pagina = "perfil_sistema.php";
	// Verificar el privilegio de la pagina
	$sql_pagina = mysqli_query($con, "SELECT cod_privilegio FROM tbl_submenu, tbl_privilegio, tbl_usuario 
		WHERE cod_submenu = cod_submenu_priv AND cod_perfil_priv = cod_perfil_us AND estado_priv = 1 
		AND cod_usuario = $cod_usuario AND enlace_subm = '$nombre_pagina'");
	if(mysqli_num_rows($sql_pagina) > 0){
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
		<meta name="description" content="Bootstrap Admin Template">
		<meta name="keywords" content="app, responsive, jquery, bootstrap, dashboard, admin">
		<link rel="shortcut icon" href="img/logo.png">
		<title>ITESEM - SIS</title>
		<!-- Vendor styles-->
		<!-- Animate.CSS-->
		<link rel="stylesheet" href="vendor/animate.css/animate.css">
		<!-- Bootstrap-->
		<link rel="stylesheet" href="vendor/bootstrap/dist/css/bootstrap.min.css">
		<!-- Ionicons-->
		<link rel="stylesheet" href="vendor/ionicons/css/ionicons.css">
		<!-- Material Colors-->
		<link rel="stylesheet" href="vendor/material-colors/dist/colors.css">
		<!-- Application styles-->
		<link rel="stylesheet" href="css/app.css">
		<!-- CBX -->
    <!-- <link rel="stylesheet" type="text/css" href="vendor/cbx/jquery.checkboxtree.css"/> -->
    <style type="text/css">
    	.categories-list
  margin: 40px 0px
  padding: 0px 10px
  width: 300px
  .category
    font-size: 14px
    display: flex
    flex-direction: column
    position: relative
    padding: 3px 0px 3px 22px
    border-bottom: 1px solid #f5f5f5
    font-weight: 300
    display: flex
    label
      width: 100%
    a
      color: #95939a
      position: absolute
      right: 0px
      z-index: 1000
    input[type="checkbox"]
      margin: 0px 10px 0px 0px
      position: absolute
      left: 0px
      top: 7px
    .subcategories
      margin-left: 0px
      display: none
      padding: 5px
      flex-direction: column
      .category
        padding-left: 22px
        flex-direction: column
        &:last-child
          border-bottom: none

    </style>
	</head>
	<body class="theme-default">
		<div class="layout-container">
			<?php include('menu.php'); ?>
			<!-- Main section-->
			<main class="main-container">
				<!-- Page content-->
				<section class="section-container">
					<div class="container-fluid">
						<div class="cardbox">
							<div class="cardbox-body">
								<?php
								if(isset($_GET['cod']) && isset($_GET['cod']) > 0){
									$cod_perfil = $_GET['cod'];
									$nombre_perfil = "";
									$sql_perfil = mysqli_query($con, "SELECT nombre_perfil FROM tbl_perfil WHERE cod_perfil = $cod_perfil");
									while ($row_p = mysqli_fetch_array($sql_perfil)) {
										$nombre_perfil = $row_p['nombre_perfil'];
									}
									?>
									<center><h2>Perfil: <?php echo $nombre_perfil; ?></h2></center>
									<h4><a href="perfil_sistema.php"><i class="ion-arrow-left-c"></i>Volver Atras</a></h4>
									<form action="privilegio_guardar.php" method="POST">
										<input type="hidden" name="cod_perfil" value="<?php echo $cod_perfil; ?>">
										<ul class="categories-list">
											<?php
											// MENU
											$cod_menu = 0;
											$sql_menu = mysqli_query($con, "SELECT cod_menu, nombre_menu FROM tbl_menu ORDER BY nombre_menu");
											while ($row_me = mysqli_fetch_array($sql_menu)) {
												$cod_menu = $row_me['cod_menu'];
												$det_check = "";
												// Verificar si tiene el privilegio
												$sql_privilegio = mysqli_query($con, "SELECT cod_menu_subm FROM tbl_privilegio, tbl_submenu 
													WHERE cod_submenu_priv = cod_submenu AND cod_perfil_priv = $cod_perfil AND estado_priv = 1 AND cod_menu_subm = $cod_menu 
													GROUP BY cod_menu_subm");
												if(mysqli_num_rows($sql_privilegio) > 0)
													$det_check = 'checked="checked"';
												?>
												<li class="category"><input type="checkbox" name="menu[]" <?php echo $det_check; ?> value="<?php echo $cod_menu; ?>"> <label> <?php echo $row_me['nombre_menu']; ?></label>
													<ul class="subcategories">
														<?php
														// SEBMENU
														$cod_submenu = 0;
														$sql_submenu = mysqli_query($con, "SELECT cod_submenu, nombre_subm FROM tbl_submenu WHERE cod_menu_subm = $cod_menu 
															ORDER BY nombre_subm");
														while ($row_su = mysqli_fetch_array($sql_submenu)) {
															$cod_submenu = $row_su['cod_submenu'];
															$det_check = "";
															// Verificar si tiene el privilegio
															$sql_privilegio_sub = mysqli_query($con, "SELECT cod_privilegio FROM tbl_privilegio 
																WHERE cod_perfil_priv = $cod_perfil AND cod_submenu_priv = $cod_submenu AND estado_priv = 1");
															if(mysqli_num_rows($sql_privilegio_sub) > 0)
																$det_check = 'checked="checked"';
															?>
															<li class="category"><input type="checkbox" name="submenu[]" <?php echo $det_check; ?> value="<?php echo $row_su['cod_submenu']; ?>"> <label><?php echo $row_su['nombre_subm']; ?></label></li>
															<?php
														}
														?>
													</ul>
												</li>
												<?php
											}
											?>
										</ul>
										<fieldset>
											<div class="form-group row">
												<div class="col-sm-12 text-right">
													<button class="btn btn-primary" type="submit">Guardar el Registro</button>
												</div>
											</div>
										</fieldset>
									</form>
								<?php } ?>
							</div>
						</div>
					</div>
				</section>
			</main>
		</div>
		<!-- End Search template-->
		<?php include('ajuste.php'); ?>
		<!-- Modernizr-->
		<script src="vendor/modernizr/modernizr.custom.js"></script>
		<!-- jQuery-->
		<script src="vendor/jquery/dist/jquery.js"></script>
		<!-- Bootstrap-->
		<script src="vendor/popper.js/dist/umd/popper.min.js"></script>
		<script src="vendor/bootstrap/dist/js/bootstrap.js"></script>
		<!-- Material Colors-->
		<script src="vendor/material-colors/dist/colors.js"></script>
		<!-- Screenfull-->
		<script src="vendor/screenfull/dist/screenfull.js"></script>
		<!-- jQuery Localize-->
		<script src="vendor/jquery-localize/dist/jquery.localize.js"></script>
		<!-- Sparkline-->
		<script src="vendor/jquery-sparkline/jquery.sparkline.js"></script>
		<!-- jQuery Knob charts-->
		<script src="vendor/jquery-knob/js/jquery.knob.js"></script>
		<!-- App script-->
		<script src="js/app.js"></script>
		<!-- CBX -->
		<!-- <script type="text/javascript" src="vendor/cbx/jquery.checkboxtree.js"></script> -->
		<script type="text/javascript">
			$.fn.cascadeCheckboxes = function() {
				$.fn.checkboxParent = function() {
					//to determine if checkbox has parent checkbox element
					var checkboxParent = $(this)
					.parent("li")
					.parent("ul")
					.parent("li")
					.find('> input[type="checkbox"]');
					return checkboxParent;
				};

				$.fn.checkboxChildren = function() {
					//to determine if checkbox has child checkbox element
					var checkboxChildren = $(this)
					.parent("li")
					.find(' > .subcategories > li > input[type="checkbox"]');
					return checkboxChildren;
				};

				$.fn.cascadeUp = function() {
					var checkboxParent = $(this).checkboxParent();
					if ($(this).prop("checked")) {
						if (checkboxParent.length) {
							//check if all children of the parent are selected - if yes, select the parent
        			//these will be the siblings of the element which we clicked on
        			var children = $(checkboxParent).checkboxChildren();
        			console.log(children);
        			var booleanChildren = $.map(children, function(child, i) {
        				return $(child).prop("checked");
        			});
        			//check if all children are checked
        			var allChecked = booleanChildren.filter(function(x) {return !x})
        			//if there are no false elements, parent is selected
        			if (!allChecked.length) {
        				$(checkboxParent).prop("checked", true);
        				$(checkboxParent).cascadeUp();
        			}
        		}
        	} else {
        		if (checkboxParent.length) {
        			//if parent is checked, becomes unchecked
        			$(checkboxParent).prop("checked", false);
        			$(checkboxParent).cascadeUp();
        		}
        	}
        };
        $.fn.cascadeDown = function() {
        	var checkboxChildren = $(this).checkboxChildren();
        	if (checkboxChildren.length) {
        		checkboxChildren.prop("checked", $(this).prop("checked"));
        		checkboxChildren.each(function(index) {
        			$(this).cascadeDown();
        		});
        	}
        }

        $(this).cascadeUp();
        $(this).cascadeDown();
      };

      $("input[type=checkbox]:not(:disabled)").on("change", function() {
      	$(this).cascadeCheckboxes();
      });

      $(".category a").on("click", function(e) {
      	e.preventDefault();
      	$(this)
      	.parent()
      	.find("> .subcategories")
      	.slideToggle(function() {
      		if ($(this).is(":visible")) $(this).css("display", "flex");
      	});
      });
		</script>
	</body>
</html>
<?php
	}else{
		header('Location:inicio.php');
	}
}else {
	header('Location:../inicio.html');
}
?>