<a href="<?= $clientlink ?>" class="mod-client">
	<div class="wrapper">
		<div class="thumb thumb-contain" style="background-image:url(<?= ROOTPATH.'img/clients/'.$logo->photoname.'.'.$logo->extension ?>)">
			<img src="<?= ROOTPATH.'assets/blank-square.gif' ?>" alt="" class="wd-100">
		</div>
		<div class="data">
			<div class="title"><?= $client->name ?></div>
			<div class="subtitle"><?= $client->subtitle ?></div>
		</div>
		<div class="location"><i class="fa fa-fw fa-map-marker"></i> <span><?= count($_STORES->data()) > 1 ? 'Varias Sucursales' : $_STORES->data()[0]->city.', '.$_PROVINCES[$_STORES->data()[0]->idprovince] ?></span></div>
	</div>
</a>