<h3>Hola, <?=$obj->fullname?> está interesado en publicar su centro en EstiloSPA:</h3>

<p style="line-height:18pt">
	<b>Nombre y Apellido:</b> <?=$obj->fullname?><br />
	<b>E-mail:</b> <?=$obj->email?><br />
	<b>Web:</b> <?= (empty($obj->web) ? 'no indicó' : $obj->web) ?><br />
	<b>Teléfono:</b> <?=$obj->phone?><br />
	<b>Empresa:</b> <?=$obj->company?><br />
	<b>¿Cómo nos conoció?:</b> <?=(empty($obj->knowus) ? 'No respondió' : $obj->knowus) ?><br />
</p>

<p>
<?= (empty($obj->message) ? 'No escribió ningún mensaje adicional' : $obj->message) ?>
</p>