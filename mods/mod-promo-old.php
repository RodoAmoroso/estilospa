<a href="<?= ROOT.'promo/'.$Clients->data()->permalink.'/'.$promo->id.'-'.Permalink($promo->title) ?>" class="mod-tile">
	
	<div class="thumb-cover bg-<?= $colorsequence[$nm] ?>" >
		<img src="<?= ROOT ?>assets/blank-rectangle.gif" class="wd-100 hidden-xs hidden-sm" alt="">
		<img src="<?= ROOT ?>assets/blank-wide.gif" class="wd-100 visible-xs visible-sm " alt="">
		<div class="overprint-absolute op-15 grayscale" style="background-image:url(<?= ROOT.'img/promos/'.$imgpromo[0]->photoname.'-t.'.$imgpromo[0]->extension ?>)"></div>
		<div class="overprint-absolute dp-flexbox">
			<div class="flex-item pad-16 text-center cl-black">
				<h4 class="fw-400"><?= $promo->title ?></h4>
				<p><?= $promo->subtitle ?></p>
			</div>
		</div>
	</div>

	<div class="caption">
		<i class="fa fa-map-marker"></i> 
		<?= count($Stores->data())>1 ? 'Varias Sucursales' : (is_array($Stores->data()) ? $Stores->data()[0]->city.', '.$Provinces[$Stores->data()[0]->idprovince] : $Stores->data()->city.', '.$Provinces[$Stores->data()->idprovince]) ?>
	</div>

	<div class="icons">
		<div class="col">
			<?= Stars($Promos->rating($promo->id)); ?>
		</div>
		<?php if($promo->sale): ?>
		<div class="col">$ <?= number_format($promo->price-($promo->price*$promo->discount/100),0,',','.') ?> <i class="fa fa-shopping-bag fa-fw"></i> </div>
		<?php endif; ?>
	</div>

	<?php if($promo->sale): ?>
	<div class="sale-tag"><span>Compra Online</span></div>
	<?php endif; ?>

	
</a>
