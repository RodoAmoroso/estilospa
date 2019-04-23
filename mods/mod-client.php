<div class="mod-client">
	<div class="wrapper">
		<div class="thumb thumb-contain" style="background-image:url(<?= ROOT.'img/clients/'.$logo->photoname.'.'.$logo->extension ?>)">
			<a href="<?= $clientlink ?>" ><img src="<?= ROOT.'assets/blank-square.gif' ?>" alt="" class="wd-100"></a>
			<div class="fav">
				<?= Fav(0,$client->id); ?>
			</div>
		</div>
		<div class="data">
			<a href="<?= $clientlink ?>" class="title"><?= $client->name ?></a>
			<div class="subtitle"><?= $client->subtitle ?></div>
		</div>
		<div class="location"><i class="fa fa-fw fa-map-marker"></i> <span><?= count($Stores->data()) > 1 ? 'Varias Sucursales' : $Stores->data()[0]->city.', '.$Provinces[$Stores->data()[0]->idprovince] ?></span></div>
	</div>
</div>