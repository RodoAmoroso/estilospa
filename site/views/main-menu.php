<nav class="main-nav">
	<div class="container d-flex justify-content-between align-items-center"> 

		<ul class="site-menu">
			<li class="menu-item">
				<a href="#" data-toggle="submenu" data-target="para-mi" class="item-anchor">Para <span class="script">Mi</span></a>
			</li>
			<li class="menu-item">
				<a href="#" data-toggle="submenu" data-target="para-compartir" class="item-anchor">Para <span class="script">Compartir</span></a>
			</li>
			<li class="menu-item">
				<a href="#" data-toggle="submenu" data-target="para-regalar" class="item-anchor">Para <span class="script">Regalar</span></a>
			</li>

			<li class="menu-item">
				<a href="<?= View::url('giftcards') ?>" class="item-anchor"><span>Gift</span><span class="script">Cards</span></a>
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
						<a href="<?= View::url('perfil') ?>" class="dropdown-item">
							<i class="fal fa-user fa-fw"></i>
							<span>Mi Perfil</span>
						</a>
					</li>
					<li>
						<a href="<?= View::url('mis-compras') ?>" class="dropdown-item">
							<i class="fal fa-shopping-bag fa-fw"></i>
							<span>Mis Compras</span>
						</a>
					</li>

					<li>
						<a href="<?= View::url('usuario','mis-giftcards') ?>" class="dropdown-item">
							<i class="fal fa-gift fa-fw"></i>
							<span>Mis GiftCards</span>
						</a>
					</li>


					<li>
						<a href="<?= View::url('mis-favoritos') ?>" class="dropdown-item">
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

<?php if($User->logged()): ?>					
<div class="gift-balance-notification" >

	<div class="icon">
		<i class="fa fa-gift"></i>
	</div>

	<div class="content">
		<div class="title">Tenés a tu favor</div>
		<div class="price">$ 150.000,00</div>
	
		<div class="expiration">Disponible hasta el 00/00/0000</div>
	</div>
	
	<div class="close-balance-notification" >
		<i class="fal fa-times"></i>
	</div>
</div>
<?php endif; ?>
