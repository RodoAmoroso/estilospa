
<!-- MAIN MENU -->
<nav>
	<div class="container">
	
		<i class="fa fa-bars fa-lg cl-white" ></i>

		<ul id="main_menu" class="main-menu" >
			<li class="highlight"><a href="<?= ROOTPATH.'busqueda/' ?>">Promos</a></li>
			<?php 
			$_CLIENTTYPES->keywords = '';
			if($_CLIENTTYPES->get()): 
				foreach($_CLIENTTYPES->data() as $type): 
			?>
			<li><a href="<?= ROOTPATH.'busqueda/'.Permalink($type->name).'/' ?>"><?= $type->name ?></a></li>
			<?php endforeach; endif; ?>
			<li class="highlight"><a href="<?= ROOTPATH.'venta-online/' ?>">Venta Online</a></li>
			<li><a href="<?= ROOTPATH.'blog' ?>">Blog</a></li>
			<li><a id="btn_search_bar" class="clickable" ><i class="fa fa-search"></i></a></li>
		</ul>

	</div>
</nav>


<!-- SEARCH -->
<section id="search_bar" class="search-bar <?=$_SECTION=='categoria'?'search-bar-category':'' ?>" >
	<!-- <div class="overprint-absolute" style="background-image:url(<?= ROOTPATH.'assets/bg-2.jpg' ?>)" ></div>
	<div class="overprint-absolute bg-gradient op-80"></div> -->

	<div class="container">	
		<?php if($_SECTION=='categoria'): ?>	
		<p class="sz-10">Buscá entre cientos de centros en todo el país</p>
		<?php else: ?>
		<p class="sz-10">Buscá tu servicio o tratamiento entre cientos de centros en todo el país</p>
		<?php endif; ?>

		<form id="form_main_search" class="row" method="POST" >
			<div class="col-xs-12 col-sm-3">	
				<div class="input-group">			
					<span class="input-group-addon"><i class="fa fa-leaf"></i></span>
					<input name="main" data-mode="main" id="fd_search_input" type="text" class="form-control" placeholder="Servicio..." value="<?=empty($search_main) ? '' : $search_main?>" autocomplete="off" >
				</div>				
			</div>
			<div class="col-xs-12 col-sm-3">	
				<div class="input-group">
					<span class="input-group-addon"><i class="fa fa-map-marker"></i></span>
					<input name="location" data-mode="locations" id="fd_search_location" type="text" class="form-control" placeholder="Zona..." value="<?=empty($search_locations) ? '' : $search_locations?>"  autocomplete="off">
				</div>
			</div>
			<div class="col-xs-12 col-sm-3">
				<div class="input-group">
					<span class="input-group-addon"><i class="fa fa-shopping-bag"></i></span>
					<select name="type" id="" class="form-control">
						<option value="busqueda" <?= $_SECTION!='categoria' ? 'selected' : '' ?> >Promos</option>
						<option value="categoria" <?= $_SECTION=='categoria' ? 'selected' : '' ?> >Centros</option>
					</select>
				</div>
			</div>			
			<div class="col-xs-12 col-sm-3">				
				<button class="btn btn-default btn-block"><i class="fa fa-search fa-fw"></i> <span class="hidden-xs" >BUSCAR</span></button>
			</div>
		</form>

	</div>
</section>