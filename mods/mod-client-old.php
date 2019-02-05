<a href="<?= ROOTPATH.'centros/'.$client->permalink ?>" class="mod-tile">

	<div class="thumb-contain bg-white" style="background-image:url(<?= ROOTPATH.'img/clients/'.$logo->photoname.'.'.$logo->extension ?>)">
		<img src="<?= ROOTPATH ?>assets/blank-rectangle.gif" class="wd-100 hidden-xs hidden-sm" alt="">
		<img src="<?= ROOTPATH ?>assets/blank-wide.gif" class="wd-100 visible-xs visible-sm " alt="">
	</div>

	<div class="caption">
		<div class="caption-inner">
			<h3><?= $client->name ?></h3>
			<p><i class="fa fa-map-marker"></i> <?= count($_STORES->data())>1 ? 'Varias Sucursales' : (is_array($_STORES->data()) ? $_STORES->data()[0]->city.', '.$_PROVINCES[$_STORES->data()[0]->idprovince] : $_STORES->data()->city.', '.$_PROVINCES[$_STORES->data()->idprovince]) ?></p>
			<div class="hidden-zone"></div>
		</div>
	</div>

	<div class="icons">
		<div class="col">
			<?= Stars($_CLIENTS->rating($client->id)); ?>
		</div>		
		<!-- <div class="col"><i class="fa fa-eye"></i> <?= $client->views ?></div> -->
		<div class="col">
			<?php 
			if($_USER->logged()):
				$_FAVS->iduser = $_USER->data()->id;
				$_FAVS->idclient = $client->id;
				if($_FAVS->find()):
			?>
					<i class="fa fa-heart"></i>
				<?php else: ?>
					<i class="fa fa-heart-o"></i>
				<?php endif; ?>
			<?php else: ?>
			<i class="fa fa-heart-o"></i>
			<?php endif; ?>
		</div>
		
	</div>

</a>