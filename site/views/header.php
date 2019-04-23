
<!-- TOP MENU -->
<section class="top-menu">

	<div class="container">
		<ul class="left-menu">
			<li><a href="<?= ROOT.'publica-tu-centro' ?>">PUBLICA TU CENTRO <i class="fa fa-angle-double-right"></i></a></li>
		</ul>
		<ul class="right-menu">
			<li><a href="http://www.facebook.com/estilospa" target="_blank"><i class="fa fa-facebook fa-fw"></i></a></li>
			<li><a href="http://www.twitter.com/estilospa" target="_blank"><i class="fa fa-twitter fa-fw"></i></a></li>
			<li><a href="https://www.instagram.com/estilospa/" target="_blank"><i class="fa fa-instagram fa-fw"></i></a></li>
			<li><a href="http://www.youtube.com/EstiloSpa" target="_blank"><i class="fa fa-youtube-play fa-fw"></i></a></li>
		</ul>
	</div>
</section>

<!-- HEADER -->
<header>
	<div class="container">
		<div class="header-inner">
			
			<a href="<?= ROOT ?>" class="logo"><img src="<?= View::assets('logo.jpg') ?>" alt=""></a>	

			<?php if(!$User->logged()): ?>		
			
			<div class="user">
				<ul class="menu-login">
					<li><a href="<?= ROOT ?>login">INGRESAR</a></li>
					<li class="separator hidden-xs">|</li>
					<li><a href="<?= ROOT ?>registro">REGISTRATE</a></li>
				</ul>
			</div>	
			<?php 
			else: 
			$img = empty($User->data()->image) ? '' : json_decode($User->data()->image);
			$_AVATAR = empty($img) ? 'user-default.png' : $img->photoname.'-o.'.$img->extension;
			?>

			<div class="user user-logged text-center">
				<div class="avatar thumb-cover" style="background-image:url(<?= View::img('users',$_AVATAR) ?>)"></div>
				<div class="user-title">	
					<a data-toggle="slide" href="#menu_user">Hola <?= $User->data()->name ?>! <i class="fa fa-caret-down"></i></a>					
				</div>
				<div id="menu_user" class="menu-user-container" >
					<div class="arrow"></div>
					<ul class="menu-user" >
						<li><a href="<?= View::url('perfil') ?>"><i class="fa fa-user"></i> <span>Perfil</span></a></li>
						<li><a href="<?= View::url('mis-compras') ?>"><i class="fa fa-shopping-basket"></i> <span>Mis Compras</span></a></li>
						<li><a href="<?= View::url('mis-favoritos') ?>"><i class="fa fa-heart"></i> <span>Favoritos</span></a></li>
						<?php if($User->data()->idtype == 1): ?>
						<li><a href="<?= View::url('admin') ?>"><i class="fa fa-wrench"></i> <span>Panel de Control</span></a></li>
						<?php endif; ?>

						<?php if($User->data()->idtype == 3): ?>
						<li><a href="<?= View::url('cuenta') ?>"><i class="fa fa-wrench"></i> <span>Panel de Control</span></a></li>
						<?php endif; ?>

						<li><a href="javascript:logout();"><i class="fa fa-times"></i> <span>Cerrar Sesión</span></a></li>
					</ul>
				</div>				
			</div>
			<?php endif; ?>

		</div>

	</div>	
</header>


