
<!-- TOP MENU -->
<section class="top-menu">

	<div class="container">
		<ul class="left-menu">
			<li><a href="<?= ROOTPATH.'publica-tu-centro' ?>">PUBLICA TU CENTRO <i class="fa fa-angle-double-right"></i></a></li>
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
			
			<a href="<?= ROOTPATH ?>" class="logo"><img src="<?= ROOTPATH ?>assets/logo.jpg" alt=""></a>	

			<?php if(!$_USER->logged()): ?>		
			
			<div class="user">
				<ul class="menu-login">
					<li><a href="<?= ROOTPATH ?>login">INGRESAR</a></li>
					<li class="separator hidden-xs">|</li>
					<li><a href="<?= ROOTPATH ?>registro">REGISTRATE</a></li>
				</ul>
			</div>	
			<?php 
			else: 
			$img = empty($_USER->data()->image) ? '' : json_decode($_USER->data()->image);
			$_AVATAR = empty($img) ? 'user-default.png' : $img->photoname.'-o.'.$img->extension;
			?>

			<div class="user user-logged text-center">
				<div class="avatar thumb-cover" style="background-image:url(<?= ROOTPATH.'img/users/'.$_AVATAR ?>)"></div>
				<div class="user-title">	
					<a data-toggle="slide" href="#menu_user">Hola <?= $_USER->data()->name ?>! <i class="fa fa-caret-down"></i></a>					
				</div>
				<div id="menu_user" class="menu-user-container" >
					<div class="arrow"></div>
					<ul class="menu-user" >
						<li><a href="<?= ROOTPATH ?>perfil"><i class="fa fa-user"></i> <span>Perfil</span></a></li>
						<li><a href="<?= ROOTPATH ?>mis-compras"><i class="fa fa-shopping-basket"></i> <span>Mis Compras</span></a></li>
						<li><a href="<?= ROOTPATH ?>mis-favoritos"><i class="fa fa-heart"></i> <span>Favoritos</span></a></li>
						<?php if($_USER->data()->idtype == 1): ?>
						<li><a href="<?= ROOTPATH ?>admin"><i class="fa fa-wrench"></i> <span>Panel de Control</span></a></li>
						<?php endif; ?>

						<?php if($_USER->data()->idtype == 3): ?>
						<li><a href="<?= ROOTPATH ?>cuenta"><i class="fa fa-wrench"></i> <span>Panel de Control</span></a></li>
						<?php endif; ?>

						<li><a href="javascript:Logout();"><i class="fa fa-times"></i> <span>Cerrar Sesión</span></a></li>
					</ul>
				</div>				
			</div>
			<?php endif; ?>

		</div>

	</div>	
</header>


