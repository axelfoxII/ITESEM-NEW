			<!-- top navbar-->
			<header class="header-container">
				<nav>
					<ul class="d-lg-none">
						<li><a class="sidebar-toggler menu-link menu-link-close" href="#"><span><em></em></span></a></li>
					</ul>
					<ul class="d-none d-sm-block">
						<li><a class="covermode-toggler menu-link menu-link-close" href="#"><span><em></em></span></a></li>
					</ul>
					<h2 class="header-title">ITESEM - SIS</h2>
					<ul class="float-right">
						<li class="dropdown">
							<a class="dropdown-toggle has-badge" href="inicio.php" data-toggle="dropdown"><img class="header-user-image" src="img/user/user.png" alt="header-user-image"></a>
							<div class="dropdown-menu dropdown-menu-right dropdown-scale">
								<a class="dropdown-item" href="#"><em class="ion-ios-email-outline icon-lg text-primary"></em>Mensajes</a>
								<a class="dropdown-item" href="perfil.php"><em class="ion-ios-person-outline icon-lg text-primary"></em>Perfil</a>
								<div class="dropdown-divider" role="presentation"></div>
								<a class="dropdown-item" href="salir.php"><em class="ion-log-out icon-lg text-primary"></em>Cerrar Sesión</a>
							</div>
						</li>
						<li><a id="header-settings" href="#"><em class="ion-more"></em></a></li>
					</ul>
				</nav>
			</header>
			<!-- sidebar-->
			<aside class="sidebar-container">
				<div class="brand-header">
					<div class="float-left pt-4 text-muted sidebar-close"><em class="ion-arrow-left-c icon-lg"></em></div>
						<a class="brand-header-logo" href="inicio.php"><img src="img/logo_itesem.png", alt="logo"></a>
				</div>
				<div class="sidebar-content">
					<div class="sidebar-toolbar">
						<div class="sidebar-toolbar-background"></div>
						<div class="sidebar-toolbar-content text-center"><a href="#"><img class="rounded-circle thumb64" src="img/user/user.png" alt="Profile"></a>
							<div class="mt-3">
								<?php
								$nombre = "";
								$sql_usuario = mysqli_query($con, "SELECT nombre_per, apellido_per FROM tbl_persona, tbl_usuario 
									WHERE cod_persona_us = cod_persona AND cod_usuario = $cod_usuario");
								while ($row_u = mysqli_fetch_array($sql_usuario)) {
									$nombre = $row_u['nombre_per']." ".$row_u['apellido_per'];
								}
								?>
								<div class="lead"><?php echo $nombre; ?></div>
							</div>
						</div>
					</div>
					<nav class="sidebar-nav">
						<ul>
							<li>
								<div class="sidebar-nav-heading">MENÚ</div>
							</li>
							<?php
							// OBTENER EL MENU, SEGUN SUS PRIVILEGIOS DEL USUARIO
							// MENU
							$sql_menu = mysqli_query($con, "SELECT cod_menu, nombre_menu, icono_menu 
								FROM tbl_privilegio, tbl_submenu, tbl_menu, tbl_usuario 
								WHERE cod_submenu_priv = cod_submenu AND cod_menu_subm = cod_menu AND estado_priv = 1 AND cod_perfil_priv = cod_perfil_us 
								AND cod_usuario = $cod_usuario GROUP BY cod_menu ORDER BY nombre_menu");
							while ($row_m = mysqli_fetch_array($sql_menu)) {
								$cod_menu = $row_m['cod_menu'];
								?>
								<li><a href="#"><span class="float-right nav-caret"><em class="ion-ios-arrow-right"></em></span><span class="float-right nav-label"></span><span class="nav-icon"><em class="<?php echo $row_m['icono_menu']; ?>"></em></span><span><?php echo $row_m['nombre_menu']; ?></span></a>
									<ul class="sidebar-subnav reg_rep">
										<?php
										// TIPO_SUBMENU
										$sql_tipo_submenu = mysqli_query($con, "SELECT cod_tiposubmenu, nombre_tiposubm 
											FROM tbl_privilegio, tbl_submenu, tbl_tipo_submenu, tbl_usuario 
											WHERE cod_submenu_priv = cod_submenu AND cod_tiposubmenu_subm = cod_tiposubmenu AND estado_priv = 1 
											AND cod_perfil_priv = cod_perfil_us AND cod_usuario = $cod_usuario AND cod_menu_subm = $cod_menu 
											GROUP BY cod_tiposubmenu ORDER BY cod_tiposubmenu");
										while ($row_ts = mysqli_fetch_array($sql_tipo_submenu)) {
											$cod_tiposubmenu = $row_ts['cod_tiposubmenu'];
											$det_icono = "";
											if($cod_tiposubmenu == 1)
												$det_icono = "ion-checkmark-circled text-success";
											elseif($cod_tiposubmenu == 2)
												$det_icono = "ion-document-text text-danger";
											?>
											<li><a href="#"><span class="float-right nav-caret"><em class="ion-ios-arrow-right"></em></span><span class="float-right nav-label"></span><span class="nav-icon"><em class="<?php echo $det_icono; ?>"></em></span><span><?php echo $row_ts['nombre_tiposubm']; ?></span></a>
												<ul class="sidebar-subnav submenu">
													<?php
													$sql_submenu = mysqli_query($con, "SELECT cod_submenu, nombre_subm, enlace_subm 
														FROM tbl_privilegio, tbl_submenu, tbl_usuario 
														WHERE cod_submenu_priv = cod_submenu AND estado_priv = 1 AND cod_perfil_priv = cod_perfil_us 
														AND cod_usuario = $cod_usuario AND cod_menu_subm = $cod_menu AND cod_tiposubmenu_subm = $cod_tiposubmenu 
														GROUP BY cod_submenu ORDER BY nombre_subm");
													while ($row_s = mysqli_fetch_array($sql_submenu)) {
														?>
														<li><a href="<?php echo $row_s['enlace_subm']; ?>"><span class="float-right nav-label"></span><span><?php echo $row_s['nombre_subm']; ?></span></a></li>
														<?php
													}
													?>
												</ul>
											</li>
											<?php
										}
										?>
									</ul>
								</li>
								<?php
							}
							?>
						</ul>
					</nav>
				</div>
			</aside>
			<div class="sidebar-layout-obfuscator"></div>