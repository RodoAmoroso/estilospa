
<div class="wrapper">
	<div class="mod-body">

		<div class="content">
			<a href="<?=$promolink?>" class="title"><?=$promo->title?></a>
			<div class="subtitle dp-none"><?=$promo->subtitle?></div>
			<a href="<?=ROOT.'busqueda/-/'.Permalink($Stores->data()[0]->city) ?>" class="location"><i class="fa fa-map-marker fa-fw"></i> <?= count($Stores->data())>1 ? 'Varias Sucursales' : (is_array($Stores->data()) ? $Stores->data()[0]->city.', '.$Provinces[$Stores->data()[0]->idprovince] : $Stores->data()->city.', '.$Provinces[$Stores->data()->idprovince]) ?></a>

		</div>
	</div>
	<div class="mod-footer">
		<div class="actions">

			<div class="pricing">
			<?php if($promo->sale): ?>
				<div class="price <?= $promo->discount ? 'strikethrough' : ''?>">$ <?= number_format($promo->price,0,',','.') ?></div>

				<?php if($promo->price && $promo->discount): ?>
				<div class="price">$ <?= number_format($promo->price-($promo->price*$promo->discount/100),0,',','.') ?></div>
				<?php endif; ?>
			<?php endif; ?>
			</div>

			<div class="button promo-buttons">

				<?php if($promo->sale): ?>
				<div class="main-button" ><i class="fa fa-envelope fa-fw icon"></i> <a href="<?=$promolink?>#comprar">Comprar</a> <i class="fa fa-caret-down arrow" data-bs-toggle="collapse" data-bs-target="#btn_promo_<?=$kp?>"></i></div>
				<?php else: ?>
				<div class="main-button" ><i class="fa fa-envelope fa-fw icon"></i> <a href="<?=$promolink?>#consultar">Consultar</a> <i class="fa fa-caret-down arrow" data-bs-toggle="collapse" data-bs-target="#btn_promo_<?=$kp?>"></i></div>
				<?php endif; ?>

				<ul id="btn_promo_<?=$kp?>" class="collapse">
					<li><i class="fa fa-envelope fa-fw"></i> <a href="<?=$promolink?>#consultar"> Consultar</a></li>
					<?php if($promo->sale): ?>
					<li><i class="fa fa-gift fa-fw"></i> <a href="<?=$promolink?>#regalar">Regalar</a></li>
					<?php endif; ?>

					<?php if($promo->show_reservation): ?>
					<li><i class="fa fa-calendar fa-fw"></i> <a href="<?=$promolink?>#turno">Reservar</a></li>
					<?php endif; ?>

				</ul>
			</div>
		</div>
	</div>
</div>