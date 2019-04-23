<h3>Hola, <?=$obj->fullname?> se ha contactado a través de la página de EstiloSPA:</h3>

<p style="line-height:18pt">
	<b>Nombre:</b> <?=$obj->fullname?><br />
	<b>E-mail:</b> <?=$obj->email?><br />
	<b>Teléfono:</b> <?=$obj->phone?><br />
</p>

<p>
<?= (empty($obj->message) ? 'No escribió ningún mensaje adicional' : $obj->message) ?>
</p>