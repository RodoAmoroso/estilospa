<a href="<?= $clientlink ?>" class="mod-client">
	<div class="wrapper">
		
		<div class="data">
			<div class="title"><?= $client->name ?></div>
			<div class="subtitle"><?= $client->subtitle ?></div>
		</div>
		<div class="location"><i class="fa fa-fw fa-map-marker"></i> <span><?= count($Stores->data()) > 1 ? 'Varias Sucursales' : $Stores->data()[0]->city.', '.$Provinces[$Stores->data()[0]->idprovince] ?></span></div>
	</div>
</a>