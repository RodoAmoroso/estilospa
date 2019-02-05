<a href="<?= ROOTPATH.'promo/'.$_CLIENTS->data()->permalink.'/'.$promo->id.'-'.Permalink($promo->title) ?>" class="mod-tile">
	
	<div class="thumb-cover bg-<?= $colorsequence[$nm] ?>" >
		<img src="<?= ROOTPATH ?>assets/blank-rectangle.gif" class="wd-100 hidden-xs hidden-sm" alt="">
		<img src="<?= ROOTPATH ?>assets/blank-wide.gif" class="wd-100 visible-xs visible-sm " alt="">
		<div class="overprint-absolute op-15 grayscale" style="background-image:url(<?= ROOTPATH.'img/promos/'.$imgpromo[0]->photoname.'-t.'.$imgpromo[0]->extension ?>)"></div>
		<div class="overprint-absolute dp-flexbox">
			<div class="flex-item pad-16 text-center cl-black">
				<h4 class="fw-400"><?= $promo->title ?></h4>
				<p><?= $promo->subtitle ?></p>
			</div>
		</div>
	</div>

	<div class="caption">
		<i class="fa fa-map-marker"></i> 
		<?= count($_STORES->data())>1 ? 'Varias Sucursales' : (is_array($_STORES->data()) ? $_STORES->data()[0]->city.', '.$_PROVINCES[$_STORES->data()[0]->idprovince] : $_STORES->data()->city.', '.$_PROVINCES[$_STORES->data()->idprovince]) ?>
	</div>

	<div class="icons">
		<div class="col">
			<?= Stars($_PROMOS->rating($promo->id)); ?>
		</div>
		<?php if($promo->sale): ?>
		<div class="col">$ <?= number_format($promo->price-($promo->price*$promo->discount/100),0,',','.') ?> <i class="fa fa-shopping-bag fa-fw"></i> </div>
		<?php endif; ?>
	</div>

	<?php if($promo->sale): ?>
	<div class="sale-tag"><span>Compra Online</span></div>
	<?php endif; ?>

	
</a>
