<a href="<?= ROOT.'centros/'.$client->permalink ?>" class="mod-tile">

	<div class="thumb-contain bg-white" style="background-image:url(<?= ROOT.'img/clients/'.$logo->photoname.'.'.$logo->extension ?>)">
		<img src="<?= ROOT ?>assets/blank-rectangle.gif" class="wd-100 hidden-xs hidden-sm" alt="">
		<img src="<?= ROOT ?>assets/blank-wide.gif" class="wd-100 visible-xs visible-sm " alt="">
	</div>

	<div class="caption">
		<div class="caption-inner">
			<h3><?= $client->name ?></h3>
			<p><i class="fa fa-map-marker"></i> <?= count($Stores->data())>1 ? 'Varias Sucursales' : (is_array($Stores->data()) ? $Stores->data()[0]->city.', '.$Provinces[$Stores->data()[0]->idprovince] : $Stores->data()->city.', '.$Provinces[$Stores->data()->idprovince]) ?></p>
			<div class="hidden-zone"></div>
		</div>
	</div>

	<div class="icons">
		<div class="col">
			<?= Stars($Clients->rating($client->id)); ?>
		</div>		
		<!-- <div class="col"><i class="fa fa-eye"></i> <?= $client->views ?></div> -->
		<div class="col">
			<?php 
			if($User->logged()):
				$Favs->iduser = $User->data()->id;
				$Favs->idclient = $client->id;
				if($Favs->find()):
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