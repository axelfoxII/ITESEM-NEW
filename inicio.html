<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
	<meta name="description" content="Bootstrap Admin Template">
	<meta name="keywords" content="app, responsive, jquery, bootstrap, dashboard, admin">
	<link rel="shortcut icon" href="view/img/logo.png">
	<title>ITESEM - SIS</title>
	<!-- Animate.CSS-->
	<link rel="stylesheet" href="view/vendor/animate.css/animate.css">
	<!-- Bootstrap-->
	<link rel="stylesheet" href="view/vendor/bootstrap/dist/css/bootstrap.min.css">
	<!-- Ionicons-->
	<link rel="stylesheet" href="view/vendor/ionicons/css/ionicons.css">
	<!-- Material Colors-->
	<link rel="stylesheet" href="view/vendor/material-colors/dist/colors.css">
	<!-- Application styles-->
	<link rel="stylesheet" href="view/css/app.css">
	<script src="https://kit.fontawesome.com/c0897c978d.js" crossorigin="anonymous"></script>
	<link rel="stylesheet" href="view/vendor/sweetalert/dist/sweetalert.css">
	<!-- <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.12.4/dist/sweetalert2.min.css" rel="stylesheet"></head> -->
	<!-- <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> -->
<body>
	<div class="layout-container">
		<div class="page-container bg-blue-grey-900">
			<div class="d-flex align-items-center align-items-center-ie bg-gradient-primary">
				<div class="fw">
					<div class="container container-xs">
						<!-- <form class="cardbox cardbox-flat text-white text-color" method='POST' action='login.php' name="loginForm" novalidate=""> -->
							<div class="cardbox-heading">
								<div class="cardbox-title text-center">
									<img src="view/img/logo_itesem.png" style="width: 60%;" class="img-fluid" alt="ITESEM">
								</div>
							</div>
							<div class="cardbox-body">
								<div class="px-5">
									<div class="form-group">
										<input class="form-control form-control-inverse" type="text" name="usuario" id="usuario" required="" placeholder="Usuario o Correo">
									</div>
									<div class="form-group" style="position: relative;">
										<input id="password" class="form-control form-control-inverse" type="password" name="contrasena" required="" placeholder="Contraseña">
										<i id="togglePassword" class="fas fa-eye text-secondary" style="cursor: pointer; position: absolute; right: 10px; top: 50%; transform: translateY(-50%);"></i>
									</div>
									<div class="text-center my-4">
										<button class="btn btn-lg btn-gradient btn-oval btn-info btn-block" id="btn-guardar" type="submit">Iniciar Sesión</button>
									</div>
								</div>
							</div>
						<!-- </form> -->
					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- Modernizr-->
	<script src="view/vendor/modernizr/modernizr.custom.js"></script>
	<!-- jQuery-->
	<script src="view/vendor/jquery/dist/jquery.js"></script>
	<!-- Bootstrap-->
	<script src="view/vendor/popper.js/dist/umd/popper.min.js"></script>
	<script src="view/vendor/bootstrap/dist/js/bootstrap.js"></script>
	<!-- Material Colors-->
	<script src="view/vendor/material-colors/dist/colors.js"></script>
	<!-- jQuery Form Validation-->
	<script src="view/vendor/jquery-validation/dist/jquery.validate.js"></script>
	<script src="view/vendor/jquery-validation/dist/additional-methods.js"></script>
	<!-- App script-->
	<script src="view/js/app.js"></script>
	<!-- <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.12.4/dist/sweetalert2.all.min.js"></script> -->
	 <script src="view/vendor/sweetalert/dist/sweetalert-dev.js"></script>

	<script>
		const togglePassword = document.querySelector('#togglePassword');
		const password = document.querySelector('#password');

		togglePassword.addEventListener('click', function (e) {
			// Cambia el tipo del input entre password y text
			const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
			password.setAttribute('type', type);

			// Cambia el ícono entre ojo abierto y cerrado
			this.classList.toggle('fa-eye-slash');
		});

		$(document).ready(function(){
			$("#btn-guardar").on( "click", function() {
				realizarAccion();
			});

			$("#usuario, #password").on("keypress", function(e) {
        if (e.which === 13) { // Código 13 es la tecla Enter
            realizarAccion();
        }
    	});
		});
				function realizarAccion() {
					usuario = $("#usuario").val();
						contrasena = $("#password").val();
						$.ajax({
							type: "POST",
							url: "login.php",
							data: "usuario="+usuario+"&contrasena="+contrasena,
							dataType: "html",
							error: function(){
								alert("Error del sistema");
							},
							success: function(data){
								if (data == 1) {
									window.location.href = "view/inicio.php";
								}else{
									swal({
										type:'error',
										title:'El usuario o contraseña son incorrectos',
										text: 'Por favor vuelva a intentarlo',
									},
									function(){
											$("#usuario").val("");
											$("#password").val("");
											location.reload();
									});
								}
							}
						});
				}
		
	</script>
</body>
</html>
