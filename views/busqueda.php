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
					<?php foreach($arrwordslocations as $lc): if(strlen($lc)>2): ?>
					<li data-word="location"><a href="#" ><span><?= $lc ?></span> <i class="fa fa-times"></i> </a></li>
					<?php endif; endforeach; ?>
				</ul>
				<hr>

				<!-- PROVINCES --> 
				<h4 class="title-bar" data-toggle="collapse" href="#list_zones" >Zonas/Provincias <i class="fa fa-caret-down"></i></h4>
				<ul id="list_zones" class="list collapse in">
					<?php 
					//$_STORES->keywords = $searchtext;
					$_STORES->searchmixed = 1;
					$_STORES->group = 'province';
					if($_STORES->search()):
						foreach($_STORES->data() as $i=>$store):
					?>
					<li data-word="location" >
						<a data-toggle="collapse" href="#zones_<?= $i ?>" ><span><?= $store->name ?></span> <i class="fa fa-caret-down"></i></a>
						<ul id="zones_<?= $i ?>" class="collapse">
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

			</div>

			<div class="right-column">

				<div class="promos-highlight">
					<?php 
					if($_PROMOS->data()): foreach($_PROMOS->data() as $kp=>$promo):
					$promolink = ROOTPATH.'promo/'.$promo->permalink.'/'.$promo->id.'-'.Permalink($promo->title);
					$clientlink = ROOTPATH.'centros/'.$promo->permalink;
					$mp = $MPConfig->find($promo->idclient);
					//$image = json_decode($promo->gallery);
					$imgpromo = json_decode($promo->gallery);
					$_STORES->get($promo->idclient);
					echo '<div class="mod-promo mod-promo-5">';
					include 'mods/mod-promo.php';
					echo '</div>';
					endforeach; endif; ?>					
				</div>

				<?php if($total_results): ?>
				<p>&nbsp;</p>
				<ul class="pagination">
					
					<li class="page-item <?=$page==1 ? 'disabled' : ''?>"><a href="<?= ROOT.'busqueda/'.$query.'/'.$location.'/'.($page-1) ?>" class="page-link" ><i class="fa fa-angle-double-left"></i></a></li>
					
					<?php for($i=1; $i<=PageMaker($page_results,$total_results); $i++): ?>
					<li class="page-item <?= $page == $i ? 'active' : '' ?>"><a href="<?=ROOT.'busqueda/'.$query.'/'.$location.'/'.$i ?>" class="page-link" ><?= $i ?></a></li>
					<?php endfor; ?>

					<li class="page-item <?=$page==PageMaker($page_results,$total_results) ? 'disabled' : ''?>"><a href="<?= ROOT.'busqueda/'.$query.'/'.$location.'/'.($page+1) ?>" class="page-link" ><i class="fa fa-angle-double-right"></i></a></li>

				</ul>

				<?php endif; ?>

				
			</div>

		</div>

	</div>
</section>
