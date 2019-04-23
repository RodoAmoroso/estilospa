
<section class="gral-section qualify">
	<div class="container">
		
		<h1>Favoritos</h1>
		<hr>


		<h4>Promos</h4>
		<div id="promos" class="well">

			<?php 
			if($Favs->getpromos($User->data()->id)):
				foreach($Favs->data() as $fav):					
					$img = json_decode($fav->gallery);
			?>
			<div data-id="<?= $fav->id ?>" class="mod-sales">
				<div class="sale-header">
					<div class="thumb-container">						
						<div class="thumb thumb-cover" style="background-image:url(<?= ROOT.'img/promos/'.$img[0]->photoname.'-t.'.$img[0]->extension ?>)" ></div>
					</div>
					<div class="caption">
						<h1 data-tag="ordernumber" class="sz-16 fw-400"><a href="<?= ROOT.'promo/'.$fav->permalink.'/'.$fav->idpromo.'-'.Permalink($fav->title) ?>"><?= $fav->title ?></a></h1>

						<h2 data-tag="price" class="sz-14"></h2>
						<!-- PRICING -->
						<?php if($fav->sale): ?>
							<?php if($fav->discount): ?>
							<div class="sz-11"><span class="strikethrough">Precio: $  <?= number_format($fav->price,0,',','.') ?></span> - <span class="sz-11"><?= $fav->discount ?>% Off</span></div>
							<?php endif; ?>
							<div class="sz-14"><strong>$ <?= number_format($fav->price-($fav->price*$fav->discount/100),0,',','.') ?></strong></div>
							<!-- AMOUNT -->
							<div  class="sz-9"><?= $fav->amount ? $fav->amount.' disponibles' : 'Lo sentimos, ya no hay más disponibles' ?></div>
						<?php endif; ?>
						<div><?= Stars($Promos->rating($fav->idpromo),'sz-7'); ?></div>
					</div>
					<div data-id="<?= $fav->id ?>" class="close delete">
						<i class="fa fa-times"></i>
					</div>
				</div>
			</div>
			<?php endforeach; else: ?>
			<p>No tienes ninguna promo en tus favoritos</p>
			<?php endif; ?>

		</div>

		<hr>


		<h4>Centros</h4>
		<div id="clients" class="well">

			<?php 
			if($Favs->getclients($User->data()->id)):
				foreach($Favs->data() as $fav):					
					$img = json_decode($fav->logo);
			?>
			<div data-id="<?= $fav->id ?>" class="mod-sales">			
				<div class="sale-header">
					<div class="thumb-container">						
						<div class="thumb thumb-cover" style="background-image:url(<?= ROOT.'img/clients/'.$img->photoname.'.'.$img->extension ?>)" ></div>
					</div>
					<div class="caption">
						<h1 class="sz-16 fw-400"><a href="<?= ROOT.'centros/'.$fav->permalink ?>"><?= $fav->name ?> </a></h1>
						<h2 class="sz-14"><?= $fav->subtitle ?></h2>
						<?php if($Stores->get($fav->idclient)): ?>
						<small> <i class="fa fa-map-marker"></i> 
						<?php foreach($Stores->data() as $ks=>$vs): ?>
						<?= $vs->city.($ks==count($Stores->data())-1 ? '' : ' &bullet; ') ?>
						<?php endforeach; ?>
						</small>
						<?php endif; ?>						
						<div><?= Stars($Clients->rating($fav->idclient),'sz-7'); ?></div>
					</div>
					<div data-id="<?= $fav->id ?>" class="close delete">
						<i class="fa fa-times"></i>
					</div>
				</div>
			</div>

			<?php endforeach; else: ?>
			<p>No tienes ningún centro en tus favoritos</p>
			<?php endif; ?>

		</div>

	</div>

</section>