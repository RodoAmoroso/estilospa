<div class="wrapper">
	<div class="mod-body">

		<div class="image-wrapper" >
			<a href="<?=$promolink?>" class="overprint-absolute" style="background-image:url(<?=ROOT.'img/promos/'.$imgpromo[0]->photoname.'-t.'.$imgpromo[0]->extension ?>)" ></a>

			<div class="fav">
				<?= Fav($promo->id,$promo->idclient); ?>
			</div>

			<?php if($promo->discount): ?>
			<div class="sale-tag"><span><?=$promo->discount?>% off</span></div>
			<?php elseif(!empty($promo->label)): ?>
			<div class="sale-tag"><span><?=$promo->label?></span></div>
			<?php endif; ?>

		</div>

		<div class="content">

			<a href="<?=$promolink?>" class="title">
				<?= text_shortener($promo->title,50) ?>
			</a>

			<div class="subtitle"><?= text_shortener($promo->subtitle,75) ?></div>

			<a href="<?=ROOT.'busqueda/-/'.Permalink($Stores->data()[0]->city) ?>" class="location"><i class="fa fa-map-marker-alt"></i> <?= count($Stores->data())>1 ? 'Varias Sucursales' : (is_array($Stores->data()) ? $Stores->data()[0]->city.', '.$Provinces[$Stores->data()[0]->idprovince] : $Stores->data()->city.', '.$Provinces[$Stores->data()->idprovince]) ?></a>

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
				<div class="main-button" >
					<i class="fa fa-shopping-bag fa-fw icon"></i>
					<a href="<?=$promolink?>#comprar">Comprar</a>
					<i class="fa fa-caret-down arrow" data-bs-toggle="collapse" data-bs-target="#btn_promo_<?=$kp?>"></i>
				</div>
				<?php else: ?>
				<div class="main-button" >
					<i class="fa fa-envelope fa-fw icon"></i>
					<a href="<?=$promolink?>#consultar">Consultar</a>
					<i class="fa fa-caret-down arrow" data-bs-toggle="collapse" data-bs-target="#btn_promo_<?=$kp?>"></i>
				</div>
				<?php endif; ?>

				<ul id="btn_promo_<?=$kp?>" class="collapse">
					<li>
						<i class="fa fa-envelope fa-fw"></i>
						<a href="<?=$promolink?>#consultar">Consultar</a>
					</li>

					<?php if($promo->show_reservation): ?>
					<li>
						<i class="fa fa-calendar fa-fw"></i>
						<a href="<?=$promolink?>#turno">Reservar</a>
					</li>
					<?php endif; ?>


				</ul>
			</div>
		</div>
	</div>
</div>