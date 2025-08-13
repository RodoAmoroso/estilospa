
<!-- TOP MENU -->
<section class="top-menu">

	<div class="container">
		<ul class="left-menu">
			<li>
				<a href="<?= ROOT.'publica-tu-centro' ?>">
					<span>PUBLICÁ TU CENTRO</span> <i class="fa fa-angle-double-right"></i>
				</a>
			</li>
		</ul>
		<ul class="right-menu">
			<li>
				<a href="http://www.facebook.com/estilospa" target="_blank">
					<i class="fab fa-facebook-f fa-fw"></i>
				</a>
			</li>
			<li>
				<a href="https://www.instagram.com/estilospa/" target="_blank">
					<i class="fab fa-instagram fa-fw"></i>
				</a>
			</li>
			<li>
				<a href="http://www.youtube.com/EstiloSpa" target="_blank">
					<i class="fab fa-youtube fa-fw"></i>
				</a>
			</li>
		</ul>
	</div>
</section>

<!-- HEADER -->
<header class="header">
	<div class="container">
		<div class="header-inner">

			<a href="<?= ROOT ?>" class="logo">
				<img src="<?= View::assets('logo.jpg') ?>" alt="">
			</a>

			<form action="busqueda" method="GET" class="search-bar-wrapper">
				<div class="input-group input-group-sm">
					<input type="text" name="q" placeholder="Buscar..." class="form-control" required>
					<button class="btn">
						<i class="fa fa-search text-aqua-2"></i>
					</button>					
				</div>
			</form>


			<div>
				<a href="#" class="btn btn-primary btn-sm px-2">
					<i class="fal fa-gift fa-fw"></i>
					Abrí Tu Regalo
				</a>				
			</div>
			

		</div>

	</div>
</header>