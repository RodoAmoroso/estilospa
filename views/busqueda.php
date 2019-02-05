<section class="search-page">
	<div class="container-fluid">
		
		<div class="block-white search-header">
			<div class="row">
				<div class="col-xs-12 col-sm-6">
					<h3>Resultados de la Búsqueda:</h3>
					<p>
						<?= $searchtext; ?>
					</p>
				</div>

				<div class="col-xs-12 col-sm-6 dp-none">
					<div class="form-inline text-right">
						<button class="btn btn-default active"><i class="fa fa-th"></i></button> 
						<button class="btn btn-default"><i class="fa fa-th-list"></i></button> | 
						<button class="btn btn-default hidden-sm hidden-md hidden-lg"><i class="fa fa-sliders"></i></button>
					</div>
				</div>

			</div>
		</div>

		<div class="search-container">

			<div class="left-column hidden-xs hidden-sm">
				<ul class="button-menu">
					<!-- WORDS --> 
					<?php foreach($arrwordsmain as $main): if(strlen($main)>2): ?>
					<?php //if(!empty($search_main)): ?>
					<li data-word="main"><a href="#" ><span><?= $main ?></span> <i class="fa fa-times"></i> </a></li>
					<?php endif; endforeach; ?>
					<!-- LOCATIONS -->
					<?php //if(!empty($search_locations)): ?>
					<?php foreach($arrwordslocations as $location): if(strlen($location)>2): ?>
					<li data-word="location"><a href="#" ><span><?= $location ?></span> <i class="fa fa-times"></i> </a></li>
					<?php endif; endforeach; ?>
				</ul>

				<!-- PROVINCES --> 
				<h4 class="title-bar" data-toggle="collapse" href="#list_zones" >Zonas/Provincias <i class="fa fa-caret-down"></i></h4>
				<ul id="list_zones" class="list collapse in">
					<?php 
					//$_STORES->keywords = $searchtext;
					$_STORES->searchmixed = 1;
					$_STORES->group = 'province';
					if($_STORES->search()):
						foreach($_STORES->data() as $store):
					?>
					<li data-word="location" >
						<a data-toggle="collapse" href="#zones_<?= $store->id ?>" ><span><?= $store->name ?></span> <i class="fa fa-caret-down"></i></a>
						<ul id="zones_<?= $store->id ?>" class="collapse">
							<?php 
							$_stores = new Stores();
							$_stores->group = 'city';
							$_stores->idprovince = $store->idprovince;
							$_stores->search();
							if($_stores->data()):
								foreach($_stores->data() as $st):
							?>
							<li data-word="location" ><a href="#"><span><?= $st->city ?></span> <span class="badge"><?= $st->countclients ?></span></a></li>
							<?php endforeach; endif; ?>
						</ul>
					</li>
					<?php endforeach; endif; ?>
				</ul>

				<!-- CITIES
				<h4 class="title-bar" data-toggle="collapse" href="#list_cities">Localidades/Barrios <i class="fa fa-caret-down"></i></h4>
				<ul id="list_cities" class="list collapse in">
					<?php
					$_STORES->group = 'city';
					if($_STORES->search()):
						foreach($_STORES->data() as $store):
					?>
					<li data-word="location" ><a href="#"><span><?= $store->city ?></span> <span class="badge hidden">1</span></a></li>
					<?php endforeach; endif; ?>
				</ul> --> 

				<?php 
					$_GLOSSARYGROUPS->keywords = $search_main;
					if($_GLOSSARYGROUPS->get()):
				?>

				<!-- GLOSSARY --> 
				<h4 class="title-bar" data-toggle="collapse" href="#list_glossary">Etiquetas <i class="fa fa-caret-down"></i></h4>
				<ul id="list_glossary" class="list collapse in">
					<?php foreach($_GLOSSARYGROUPS->data() as $glossary): ?>
					<li data-word="main" >
						<a data-toggle="collapse" href="#labels_<?= $glossary->id ?>" ><span><?= $glossary->name ?></span> <i class="fa fa-caret-down"></i></a>
						<ul id="labels_<?= $glossary->id ?>" class="collapse">
							<?php 
							$_glossary = new Glossary();
							$_glossary->idgroup = $glossary->id;
							$_glossary->get();
							if($_glossary->data()):
								foreach($_glossary->data() as $glos):
							?>
							<li data-word="main" ><a href="#"><span><?= $glos->name ?></span> <span class="badge"><?= $glos->countclients ?></span></a></li>
							<?php endforeach; endif; ?>

						</ul>
					</li>
					<?php endforeach; ?>
				</ul>
				<?php endif; ?>

				<!-- PROMO TYPE 
				<h4 class="title-bar" data-toggle="collapse" href="#list_types">Tipo de Promo <i class="fa fa-caret-down"></i></h4>
				<ul id="list_types" class="list collapse in">
					<?php 
					$_PROMOTYPES->keywords = '';
					if($_PROMOTYPES->get()):
						foreach($_PROMOTYPES->data() as $promotype):
					?>
					<li data-word="main" ><a href="#"><span><?= $promotype->name ?></span> <span class="badge hidden">1</span></a></li>
					<?php endforeach; endif; ?>
				</ul>--> 
			</div>

			<div class="right-column">

				<div class="promo-container">
					<?php 
					if($_PROMOS->data()): foreach($_PROMOS->data() as $i=>$promo):
					$promolink = ROOTPATH.'promo/'.$promo->permalink.'/'.$promo->id.'-'.Permalink($promo->title);
					$clientlink = ROOTPATH.'centros/'.$promo->permalink;
					$mp = $MPConfig->find($promo->idclient);
					$image = json_decode($promo->gallery);
					$_STORES->get($promo->idclient);
					?>
					<div class="mod-promo">
						<div class="wrapper">
							<div class="thumb thumb-cover" style="background-image: url(<?= ROOTPATH.'img/promos/'.$image[0]->photoname.'-t.'.$image[0]->extension ?>);" ></div>
							<div class="data">
								<div class="data-body">
									<a href="<?= $promolink ?>" class="title"><?= $promo->title ?></a>
									<div class="subtitle"><?= $promo->subtitle ?></div>
								</div>
								<div class="data-footer">
									<a href="<?= $clientlink ?>"><i class="fa fa-fw fa-map-marker"></i> <span><?= $promo->name ?></span> &bullet; <span><?= count($_STORES->data()) > 1 ? 'Varias Sucursales' : $_STORES->data()[0]->city.', '.$_PROVINCES[$_STORES->data()[0]->idprovince] ?></span></a>
								</div>
							</div>
							<div class="actions">
								<?php if($promo->price): ?>
									<?php if($promo->discount): ?>
									<div class="price price-discount"><span class="strikethrough">$ <?= number_format($promo->price,0,',','.') ?></span> - <span><?= $promo->discount ?>% Off</span></div>
									<?php endif; ?>
									<div class="price"><span>$ <?= number_format($promo->price-($promo->price*$promo->discount/100),2,',','.') ?></span></div>
								<?php endif; ?>
								<div class="buttons promo-buttons">										
									<?php if($promo->price && $mp): ?>
									<div class="main-button" ><i class="fa fa-shopping-bag fa-fw icon"></i> <a href="<?= $promolink.'#comprar' ?>">Comprar</a> <i class="fa fa-caret-down arrow" data-toggle="collapse" data-target="#btn_promo_<?= $promo->id.'_'.$i ?>"></i></div>
									<?php else: ?>
									<div class="main-button" ><i class="fa fa-envelope fa-fw icon"></i> <a href="<?= $promolink.'#consultar' ?>">Consultar</a> <i class="fa fa-caret-down arrow" data-toggle="collapse" data-target="#btn_promo_<?= $promo->id.'_'.$i ?>"></i></div>
									<?php endif; ?>

									<ul id="btn_promo_<?= $promo->id.'_'.$i ?>" class="collapse">
										<li><i class="fa fa-envelope fa-fw"></i> <a href="<?= $promolink.'#consultar' ?>"> Consultar</a></li>
										<?php if($promo->price && $mp): ?>
										<li><i class="fa fa-gift fa-fw"></i> <a href="<?= $promolink.'#regalar' ?>">Regalar</a></li>
										<?php endif; ?>
										<li><i class="fa fa-calendar fa-fw"></i> <a href="<?= $promolink.'#turno' ?>">Solicitar Turno</a></li>
									</ul>
								</div>
							</div>
						</div>
					</div>
					<?php endforeach; endif; ?>
				</div>

				<div class="client-container">
					<?php if($_QCLIENTS): foreach($_QCLIENTS as $client): 
					$logo = json_decode($client->logo);
					$clientlink = ROOTPATH.'centros/'.$client->permalink;
					$_STORES->get($client->id);
					?>
					<a href="<?= $clientlink ?>" class="mod-client">
						<div class="wrapper">
							<div class="thumb thumb-cover" style="background-image:url(<?= ROOTPATH.'img/clients/'.$logo->photoname.'.'.$logo->extension ?>)">
								<img src="<?= ROOTPATH.'assets/blank-square.gif' ?>" alt="" class="wd-100">
							</div>
							<div class="data">
								<div class="title"><?= $client->name ?></div>
								<div class="subtitle"><?= $client->subtitle ?></div>
							</div>
							<div class="location"><i class="fa fa-fw fa-map-marker"></i> <span><?= count($_STORES->data()) > 1 ? 'Varias Sucursales' : $_STORES->data()[0]->city.', '.$_PROVINCES[$_STORES->data()[0]->idprovince] ?></span></div>
						</div>
					</a>
					<?php endforeach; endif; ?>
				</div>


				<!-- BLOG -->
				<?php if($_BLOG->data()): ?>
				<!-- <div class="block-white">-->
					<div class="row">
					<?php						
						foreach($_BLOG->data() as $blog):
							echo '<div class="col-xs-12 col-sm-12 col-md-6 col-lg-2">';
							$img = json_decode($blog->gallery);
							include 'mods/mod-blog.php';
							echo '</div>';
						endforeach;
					?>
					</div>
				<!--</div>-->
				<?php endif; ?>
			</div>


		</div>

	</div>
</section>
