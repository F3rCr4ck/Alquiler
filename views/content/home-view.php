<div class="full-box page-header">
		
		<h3 class="text-center">
			<i class="fab fa-dashcube fa-fw"></i> <b>BIENVENIDO!! ELIJA UNA OPCION</b> 
		</h3>
		<p class="text-center"> 
		<b>Recuerde que siempre puede explorar el menú completo haciendo click en la parte superior izquierda</b>
		</p>
		
</div>
		
			<!-- Content -->
			<div class="full-box tile-container">
				<a href="<?php echo SERVERURL;?>client-list/" class="tile">
					<div class="tile-tittle">Clientes</div>
					<div class="tile-icon">
						<!--<i class="fas fa-users fa-fw"></i>-->
						<img src="<?php echo SERVERURL; ?>views/assets/img/cliente.png" class="img-fluid" width="80px" height="100px" alt="Avatar">
						<p>5 Registrados</p>
					</div>
				</a>

				<?php 
					require_once "./controllers/itemController.php";
					$ins_item = new itemController();

					$total_items = $ins_item->datos_item_controlador("Conteo",0);
				?>

				<a href="<?php echo SERVERURL;?>item-list/" class="tile">
					<div class="tile-tittle">Ver Inventario</div>
					<div class="tile-icon">
						<!-- <i class="fas fa-pallet fa-fw"></i>-->
						<img src="<?php echo SERVERURL; ?>views/assets/img/inventario.png" class="img-fluid" width="80px" height="100px" alt="Avatar">
						<p><?php echo $total_items->rowCount();?> Registrados</p>
					</div>
				</a>

				<?php 
					require_once "./controllers/prestamoController.php";
					$ins_prestamo = new prestamoController();

					$total_reservaciones = $ins_prestamo->datos_prestamo_controlador("Conteo_Reservacion",0);
					$total_prestamos = $ins_prestamo->datos_prestamo_controlador("Conteo_Prestamos",0);
					$total_finalizados = $ins_prestamo->datos_prestamo_controlador("Conteo_Finalizados",0);
					
				?>	

				<a href="<?php echo SERVERURL;?>reservation-reservation/" class="tile">
					<div class="tile-tittle">Reservaciones</div>
					<div class="tile-icon">
						<i class="far fa-calendar-alt fa-fw"></i>
						<p><?php echo $total_reservaciones->rowCount();?> Registradas</p>
					</div>
				</a>

				<a href="<?php echo SERVERURL;?>reservation-pending/" class="tile">
					<div class="tile-tittle">Ventas</div><!--Prestamos -->
					<div class="tile-icon">
						<!--<i class="fas fa-hand-holding-usd fa-fw"></i>-->
						<img src="<?php echo SERVERURL; ?>views/assets/img/venta.png" class="img-fluid" width="80px" height="100px" alt="Avatar">
						<p><?php echo $total_prestamos->rowCount();?> Registrados</p>
					</div>
				</a>

				<a href="<?php echo SERVERURL;?>reservation-list/" class="tile">
					<div class="tile-tittle">Finalizados</div>
					<div class="tile-icon">
						<i class="fas fa-clipboard-list fa-fw"></i>
						<p><?php echo $total_finalizados->rowCount();?> Registrados</p>
					</div>
				</a>
				<?php if($_SESSION['privilegio_spm'] == 1){ 
					require_once "./controllers/usuarioController.php";
					$ins_usuario= new usuarioController();
					$total_user= $ins_usuario->datos_usuario_controlador("Conteo",0);
				
				?>
				<a href="<?php echo SERVERURL;?>user-list/" class="tile">
					<div class="tile-tittle">Usuarios</div>
					<div class="tile-icon">
						<i class="fas fa-user-secret fa-fw"></i>
						<p> <?php echo $total_user->rowCount(); ?> Registrados</p>
					</div>
				</a>
				<?php }?>
				<a href="<?php echo SERVERURL;?>company/" class="tile">
					<div class="tile-tittle">Empresa</div>
					<div class="tile-icon">
						<i class="fas fa-store-alt fa-fw"></i>
						<p>1 Registrada</p>
					</div>
				</a>
		</div>

