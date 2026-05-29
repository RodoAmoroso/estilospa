<nav class="main-nav">
	<div class="container d-flex justify-content-between align-items-center"> 

		<ul class="site-menu">
			<?php if($main_categories): foreach($main_categories as $main_category): ?>
			<li class="menu-item">
				<a href="#" data-toggle="submenu" data-target="<?= $main_category->reference ?>" class="item-anchor">
					<?= $main_category->name ?>
				</a>
			</li>			
			<?php endforeach; endif; ?>

			<li class="menu-item">
				<a href="<?= View::url('giftcards') ?>" class="item-anchor">
					<span>Gift</span><span class="script">Cards</span>
				</a>
			</li>
		</ul>
		
		<?php if(!$User->logged()): ?>
		<ul class="site-menu">
			<li class="menu-item">
				<a href="<?= View::url('login') ?>" data-toggle="login" class="item-anchor">Ingresar</a>
			</li>
			<li class="menu-item">
				<a href="<?= View::url('registro') ?>" data-toggle="register" class="item-anchor">Registrate</a>
			</li>
			
		</ul>
		<?php else: ?>
		
		<ul class="site-menu">

			<li class="menu-item">
			
				<a data-bs-toggle="dropdown" href="#">
					<span>Hola <?= $_userdata->name ?>!</span> <i class="fa fa-caret-down fa-fw"></i>					
				</a>

				<ul class="dropdown-menu" >

					<?php if($_userdata->idtype == 1): ?>
					<li>
						<a href="<?= View::url('admin') ?>" class="dropdown-item">
							<i class="fal fa-cogs fa-fw"></i> <span>Panel de Control</span> 
						</a>
					</li>	
					<?php endif; ?>

					<?php if($_userdata->idtype == 3): ?>
					<li>
						<a href="<?= View::url('panel') ?>" class="dropdown-item">
							<i class="fal fa-cogs fa-fw"></i> <span>Panel de Control</span> 
						</a>
					</li>	
					<?php endif; ?>

					<li><hr></li>			

					<li>
						<a href="<?= View::url('usuario','mi-perfil') ?>" class="dropdown-item">
							<i class="fal fa-user fa-fw"></i>
							<span>Mi Perfil</span>
						</a>
					</li>
					<li>
						<a href="<?= View::url('usuario','mis-compras') ?>" class="dropdown-item">
							<i class="fal fa-shopping-bag fa-fw"></i>
							<span>Mis Compras</span>
						</a>
					</li>
					<li>
						<a href="<?= View::url('usuario','mis-experiencias') ?>" class="dropdown-item">
							<i class="fal fa-spa fa-fw"></i>
							<span>Mis Experiencias</span>
						</a>
					</li>

					<li>
						<a href="<?= View::url('usuario','mis-giftcards') ?>" class="dropdown-item">
							<i class="fal fa-gift fa-fw"></i>
							<span>Mis GiftCards</span>
						</a>
					</li>


					<li>
						<a href="<?= View::url('usuario','mis-favoritos') ?>" class="dropdown-item">
							<i class="fal fa-heart fa-fw"></i>
							<span>Mis Favoritos</span>
						</a>
					</li>

					<li><hr></li>			
					<li>
						<a href="#" data-toggle="logout" class="dropdown-item">
							<i class="fal fa-sign-out fa-fw"></i> <span>Salir</span>
						</a>
					</li>
				</ul>

			</li>

		</div>				
		
		<?php endif; ?>

	</div>
</nav>


<!-- PARA MI -->
<div data-submenu="para-mi" class="main-submenu">
	
	<div class="container">

		<div class="d-flex justify-content-between align-items-baseline">
			<div class="submenu-title">
				Para <span class="ff-birthstone text-aqua-2">Mi</span>			
			</div>
			<a href="#" class="small text-aqua-2">
				ver más
				<i class="fa fa-caret-right fa-fw"></i>
			</a>

		</div>

		<hr>

		<div class="row">
			<div class="col-lg-2">
				<ul class="submenu-nav" >
					<li>
						<a href="#">Faciales <i class="fa fa-caret-right fa-fw"></i></a>
					</li>
					<li>
						<a href="#">Corporales</a>
					</li>
					<li>
						<a href="#">Masajes</a>
					</li>
					<li>
						<a href="#">Días de SPA</a>
					</li>
				</ul>
			</div>
			<div class="col-lg-10">	

				<div class="experiences">

					<div class="experience">
						<div class="image"></div>
						<div class="content">
							<div class="title">Noche de Alojamiento + Circuito Spa para 3 personas</div>
							<div class="price">$ 0000</div>
						</div>
					</div>

					<div class="experience">
						<div class="image"></div>
						<div class="content">
							<div class="title">2x1 en Masajes entre Amigas</div>
							<div class="price">$ 0000</div>
						</div>
					</div>

				</div>				

			</div>
		</div>

	</div>

	<div data-toggle="close-submenu" class="close-submenu">
		<i class="fal fa-times"></i>
	</div>
</div>

<!-- PARA COMPARTIR -->
<div data-submenu="para-compartir" class="main-submenu">
	<div class="container">

		<div class="d-flex justify-content-between align-items-baseline">
			<div class="submenu-title">
				Para <span class="ff-birthstone text-aqua-2">Compartir</span>
			</div>
			<a href="#" class="small text-aqua-2">
				ver más
				<i class="fa fa-caret-right fa-fw"></i>
			</a>
		</div>

		<hr>

		<div class="row">
			<div class="col-lg-2">
				<ul class="submenu-nav" >
					<li>
						<a href="#">Faciales <i class="fa fa-caret-right fa-fw"></i></a>
					</li>
					<li>
						<a href="#">Corporales</a>
					</li>
					<li>
						<a href="#">Masajes</a>
					</li>
					<li>
						<a href="#">Días de SPA</a>
					</li>
				</ul>
			</div>
			<div class="col-lg-10">	

				<div class="experiences">

					<div class="experience">
						<div class="image"></div>
						<div class="content">
							<div class="title">Noche de Alojamiento + Circuito Spa para 3 personas</div>
							<div class="price">$ 0000</div>
						</div>
					</div>

					<div class="experience">
						<div class="image"></div>
						<div class="content">
							<div class="title">2x1 en Masajes entre Amigas</div>
							<div class="price">$ 0000</div>
						</div>
					</div>

				</div>

			</div>
		</div>

	</div>

	<div data-toggle="close-submenu" class="close-submenu">
		<i class="fal fa-times"></i>
	</div>
</div>

<!-- PARA REGALAR -->
<div data-submenu="para-regalar" class="main-submenu">
	<div class="container">

		<div class="d-flex justify-content-between align-items-baseline">
			<div class="submenu-title">
				Para <span class="ff-birthstone text-aqua-2">Regalar</span>
			</div>
			<a href="#" class="small text-aqua-2">
				ver más
				<i class="fa fa-caret-right fa-fw"></i>
			</a>
		</div>
		<hr>

		<div class="row">
			<div class="col-lg-2">
				<ul class="submenu-nav" >
					<li>
						<a href="#">Faciales <i class="fa fa-caret-right fa-fw"></i></a>
					</li>
					<li>
						<a href="#">Corporales</a>
					</li>
					<li>
						<a href="#">Masajes</a>
					</li>
					<li>
						<a href="#">Días de SPA</a>
					</li>
				</ul>
			</div>
			<div class="col-lg-10">	

				<div class="experiences">

					<div class="experience">
						<div class="image"></div>
						<div class="content">
							<div class="title">Noche de Alojamiento + Circuito Spa para 3 personas</div>
							<div class="price">$ 0000</div>
						</div>
					</div>

					<div class="experience">
						<div class="image"></div>
						<div class="content">
							<div class="title">2x1 en Masajes entre Amigas</div>
							<div class="price">$ 0000</div>
						</div>
					</div>

				</div>

			</div>
		</div>

	</div>

	<div data-toggle="close-submenu" class="close-submenu">
		<i class="fal fa-times"></i>
	</div>
</div>

<?php if($User->logged() && $giftcard_user_balance): ?>					
<div class="gift-balance-notification d-none" >

	<div class="icon">
		<i class="fa fa-gift"></i>
	</div>

	<a href="<?= View::url('usuario','mis-giftcards') ?>" class="content">
		<div class="title">Tenés a tu favor</div>
		<div class="price">$ <?= $giftcard_user_balance->total_formatted ?></div>
	
		<div class="expiration">Disponible hasta el <?= $giftcard_user_balance->expiration ?></div>
	</a>
	
	<div class="close-balance-notification" >
		<i class="fal fa-times"></i>
	</div>
</div>
<?php endif; ?>
