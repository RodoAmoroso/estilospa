<h3>¡Hola <?=$obj->clientname?>! Nueva venta en EstiloSPA.com!!!</a></h3>

<h4>Datos del comprador:</h4>

<ul>
	<li>Nombre completo: <?=$obj->username?></li>
	<li>E-mail: <a href="mailto:<?=$obj->useremail?>"><?=$obj->useremail?></a></li>
	<li>Teléfono: <?=(empty($obj->phone) ? 'no indicó ninguno' : $obj->userphone) ?></li>
</ul>


<?php if(!is_null($obj->gift)): ?>
<h4>El usuario ha regalado el servicio a:</h4>
<ul>
	<li>Nombre: <?=$obj->gift->touser?></li>
	<li>E-mail: <a href="mailto:<?=$obj->gift->mail?>"><?=$obj->gift->mail?></a></li>
</ul>
<hr>
<?php endif; ?>

<h4>Datos de la promo: <?=$obj->title?></h4>
<img src="<?=$obj->image?>" alt="" style="max-width: 200px">
<p><b>Incluye: </b><?=$obj->includes?></p>
<div style="background-color:rgb(240,240,240);padding:8px 16px">
	<p><b>Nro de Operación: <?=$obj->collection_id?></b></p>
	<small>Valor: <?=$obj->quantity?> x $ <?=number_format($obj->price,2,',','.')?></small>
	<p><b>Total: $ <?=number_format($obj->price*$obj->quantity,2,',','.')?></b></p>
</div>


<hr>
<p>
	Gracias.<br />
	El equipo de EstiloSPA.com
</p>