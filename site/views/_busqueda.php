<section class="search-page">
	<div class="container-fluid">
		
		<div class="search-container">

			<div class="left-column hidden-xs hidden-sm">

				<h3>Resultados de la Búsqueda:</h3>

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
				<hr>

				<!-- PROVINCES --> 
				<h4 class="title-bar" data-toggle="collapse" href="#list_zones" >Zonas/Provincias <i class="fa fa-caret-down"></i></h4>
				<ul id="list_zones" class="list collapse in">
					<?php 
					//$Stores->keywords = $searchtext;
					$Stores->searchmixed = 1;
					$Stores->group = 'province';
					if($Stores->search()):
						foreach($Stores->data() as $store):
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
					$Stores->group = 'city';
					if($Stores->search()):
						foreach($Stores->data() as $store):
					?>
					<li data-word="location" ><a href="#"><span><?= $store->city ?></span> <span class="badge hidden">1</span></a></li>
					<?php endforeach; endif; ?>
				</ul> --> 

				<?php 
					$GlossaryGroups->keywords = $search_main;
					if($GlossaryGroups->get()):
				?>

				<!-- GLOSSARY --> 
				<h4 class="title-bar" data-toggle="collapse" href="#list_glossary">Etiquetas <i class="fa fa-caret-down"></i></h4>
				<ul id="list_glossary" class="list collapse in">
					<?php foreach($GlossaryGroups->data() as $glossary): ?>
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

				<div class="promos-highlight">
					<?php 
					if($Promos->data()): foreach($Promos->data() as $kp=>$promo):
					$promolink = ROOT.'promo/'.$promo->permalink.'/'.$promo->id.'-'.Permalink($promo->title);
					$clientlink = ROOT.'centros/'.$promo->permalink;
					$mp = $MPConfig->find($promo->idclient);
					//$image = json_decode($promo->gallery);
					$imgpromo = json_decode($promo->gallery);
					$Stores->get($promo->idclient);
					echo '<div class="mod-promo mod-promo-5">';
					include 'mods/mod-promo.php';
					echo '</div>';
					endforeach; endif; ?>

					
				</div>

				<div class="client-container">
					<?php if($_QCLIENTS): foreach($_QCLIENTS as $client): 
					$logo = json_decode($client->logo);
					$clientlink = ROOT.'centros/'.$client->permalink;
					$Stores->get($client->id);
					//include 'mods/mod-client.php';
					?>
					<!--<a href="<?= $clientlink ?>" class="mod-client">
						<div class="wrapper">
							<div class="thumb thumb-cover" style="background-image:url(<?= ROOT.'img/clients/'.$logo->photoname.'.'.$logo->extension ?>)">
								<img src="<?= ROOT.'assets/blank-square.gif' ?>" alt="" class="wd-100">
							</div>
							<div class="data">
								<div class="title"><?= $client->name ?></div>
								<div class="subtitle"><?= $client->subtitle ?></div>
							</div>
							<div class="location"><i class="fa fa-fw fa-map-marker"></i> <span><?= count($Stores->data()) > 1 ? 'Varias Sucursales' : $Stores->data()[0]->city.', '.$Provinces[$Stores->data()[0]->idprovince] ?></span></div>
						</div>
					</a>-->
					<?php endforeach; endif; ?>
				</div>


				<!-- BLOG -->
				<?php if($Blog->data()): ?>
				<!-- <div class="block-white">-->
					<div class="row">
					<?php						
						foreach($Blog->data() as $blog):
							echo '<div class="col-xs-12 col-sm-12 col-md-6 col-lg-3">';
							$img = json_decode($blog->gallery);
							//include 'mods/mod-blog.php';
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
