
<!-- MAIN MENU -->
<nav>
	<div class="container">
	
		<i class="fa fa-bars fa-lg cl-white" ></i>

		<ul id="main_menu" class="main-menu" >
			<?php 
			$_CLIENTTYPES->keywords = '';
			if($_CLIENTTYPES->get()): 
				foreach($_CLIENTTYPES->data() as $type): 
			?>
			<li><a href="<?= ROOTPATH.'busqueda/'.Permalink($type->name).'/' ?>"><?= $type->name ?></a></li>
			<?php endforeach; endif; ?>
			<li><a href="<?= ROOTPATH.'blog' ?>">Blog</a></li>
			<li><a href="<?= ROOTPATH.'busqueda/' ?>">Promos</a></li>
			<li class="highlight"><a href="<?= ROOTPATH.'venta-online/' ?>">Venta Online</a></li>
			<li><a id="btn_search_bar" class="clickable" ><i class="fa fa-search"></i></a></li>
		</ul>

	</div>
</nav>


<!-- SEARCH -->
<section id="search_bar" class="search-bar" >
	<!-- <div class="overprint-absolute" style="background-image:url(<?= ROOTPATH.'assets/bg-2.jpg' ?>)" ></div>
	<div class="overprint-absolute bg-gradient op-80"></div> -->

	<div class="container">		
		<p class="sz-10">Buscá tu servicio o tratamiento entre cientos de centros en todo el país</p>

		<form id="form_main_search" class="row" method="GET" action="<?= ROOTPATH.'busqueda/' ?>">
			<div class="col-xs-12 col-sm-4">	
				<div class="input-group">			
					<span class="input-group-addon bg-aqua-3 cl-white"><i class="fa fa-leaf"></i></span>
					<input name="main" data-mode="main" id="fd_search_input" type="text" class="form-control" placeholder="Servicio..." autocomplete="off" >
				</div>				
			</div>
			<div class="col-xs-12 col-sm-4">	
				<div class="input-group">
					<span class="input-group-addon bg-aqua-3 cl-white"><i class="fa fa-map-marker"></i></span>
					<input name="location" data-mode="locations" id="fd_search_location" type="text" class="form-control" placeholder="Zona..." autocomplete="off">
				</div>
			</div>
			<div class="col-xs-12 col-sm-4">				
				<button class="btn btn-default btn-block"><i class="fa fa-search fa-fw"></i> <span class="hidden-xs" >BUSCAR</span></button>
			</div>
		</form>

	</div>
</section>