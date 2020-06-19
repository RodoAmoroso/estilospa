
<!-- TOP MENU -->
<section class="top-menu">

	<div class="container">
		<ul class="left-menu">
			<li><a href="<?= ROOT.'publica-tu-centro' ?>">PUBLICÁ TU CENTRO <i class="fa fa-angle-double-right"></i></a></li>
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
<header class="header">
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
			$img = empty($_userdata->image) ? '' : json_decode($_userdata->image);
			$_AVATAR = empty($img) ? 'user-default.png' : $img->photoname.'-o.'.$img->extension;
			?>

			<div class="user user-logged text-center">
				<div data-toggle="slide" href="#menu_user" class="avatar thumb-cover" style="background-image:url(<?= View::img('users',$_AVATAR) ?>)"></div>
				<div class="user-title">
					<a data-toggle="slide" href="#menu_user"><span>Hola <?= $_userdata->name ?>!</span> <i class="fa fa-caret-down"></i></a>
				</div>
				<div id="menu_user" class="menu-user-container" >
					<div class="arrow"></div>
					<ul class="menu-user" >

					<?php if($_userdata->idtype == 1): ?>
						<li>
							<a href="<?= View::url('admin') ?>">
								<i class="fa fa-wrench"></i>
								<span>Panel de Control</span>
							</a>
						</li>
						<li>
							<a href="<?= View::url('admin','centros') ?>">
								<i class="fa fa-building-o"></i>
								<span>Centros</span>
							</a>
						</li>
						<li>
							<a href="<?= View::url('admin','promos') ?>">
								<i class="fa fa-leaf"></i>
								<span>Experiencias</span>
							</a>
						</li>
						<li>
							<a href="<?= View::url('admin','preguntas') ?>">
								<i class="fa fa-comments"></i>
								<span>Preguntas</span>
							</a>
						</li>
						<li>
							<a href="<?= View::url('admin','reservas') ?>">
								<i class="fa fa-calendar"></i>
								<span>Reservas</span>
							</a>
						</li>
						<li>
							<a href="<?= View::url('admin','ventas') ?>">
								<i class="fa fa-shopping-bag"></i>
								<span>Ventas</span>
							</a>
						</li>
						<li>
							<a href="<?= View::url('admin','usuarios') ?>">
								<i class="fa fa-users"></i>
								<span>Usuarios</span>
							</a>
						</li>

						<li class="highlight">
							<a href="<?= View::url('admin','hotsale') ?>">
								<i class="fa fa-tag"></i>
								<span>HOT SALE!!</span>
							</a>
						</li>
						<li class="separator"></li>
					<?php endif; ?>



					<?php if($_userdata->idtype == 3): ?>

						<?php $reservations_unconfirmed = $Reservations->get_unconfirmed($_userdata->idclient); ?>
						<li>
							<a href="<?= View::url('panel') ?>">
								<i class="fa fa-wrench"></i>
								<span>Panel de Control</span></a>
						</li>
						<li>
							<a href="<?= View::url('panel','mi-centro') ?>">
								<i class="fa fa-building-o"></i>
								<span>Mi Centro</span>
							</a>
						</li>
						<li>
							<a href="<?= View::url('panel','promos') ?>">
								<i class="fa fa-leaf"></i>
								<span>Experiencias</span>
							</a>
						</li>
						<li>
							<a href="<?= View::url('panel','reservas') ?>">
								<i class="fa fa-calendar"></i>
								<span>Reservas</span>
								<?php if($reservations_unconfirmed): ?>
								<span class="notify-icon"><?=$reservations_unconfirmed?></span>
								<?php endif; ?>
							</a>
						</li>
						<li>
							<a href="<?= View::url('panel','preguntas') ?>">
								<i class="fa fa-comments"></i>
								<span>Preguntas</span>
							</a>
						</li>
						<li>
							<a href="<?= View::url('panel','mi-cuenta') ?>">
								<i class="fa fa-shopping-bag"></i>
								<span>Ventas</span>
							</a>
						</li>
						<li class="separator"></li>
					<?php endif; ?>


						<li><a href="<?= View::url('perfil') ?>"><i class="fa fa-user"></i> <span>Perfil</span></a></li>
						<li><a href="<?= View::url('mis-compras') ?>"><i class="fa fa-shopping-basket"></i> <span>Mis Compras</span></a></li>
						<li><a href="<?= View::url('mis-favoritos') ?>"><i class="fa fa-heart"></i> <span>Favoritos</span></a></li>
						<?php if($_userdata->idtype == 2): ?>
						<li><a href="<?= View::url('mi-agenda') ?>"><i class="fa fa-calendar"></i> <span>Agenda</span></a></li>
						<?php endif; ?>

						<li class="separator"></li>

						<li><a href="javascript:logout();"><i class="fa fa-times"></i> <span>Cerrar Sesión</span></a></li>
					</ul>
				</div>
			</div>
			<?php endif; ?>

		</div>

	</div>
</header>


