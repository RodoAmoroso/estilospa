<h3>¡Hola <?=$obj->username?>! Gracias por tu compra en EstiloSPA.com!!!</a></h3>

<p>A continuación te detallamos tu compra:</p>

<h4><a href="<?=$obj->promolink?>"><?=$obj->title?></a></h4>

<img src="<?=$obj->image?>" alt="" style="max-width: 200px">

<p><b>Incluye: </b><?=$obj->includes?></p>

<div style="background-color:rgb(240,240,240);padding:8px 16px">
	<p><b>Nro de Operación: <?=$obj->collection_id?></b></p>
	<small>Valor: <?=$obj->quantity?> x $ <?=number_format($obj->price,2,',','.')?></small>
	<p><b>Total: $ <?=number_format($obj->price*$obj->quantity,2,',','.')?></b></p>
</div>

<?php if(!is_null($obj->gift)): ?>
<h4>Le regalaste este servicio a:</h4>
<ul>
	<li>Nombre: <?=$obj->gift->touser?></li>
	<li>E-mail: <a href="mailto:<?=$obj->gift->mail?>"><?=$obj->gift->mail?></a></li>
</ul>
<hr>
<?php endif; ?>



<h4>Datos del Centro:</h4>
<p>
	<a href="<?= ROOT.'centros/'.$obj->permalink ?>"><?=$obj->clientname?></a><br />
	<small>Email: <a href="mailto:<?=$obj->clientemail?>"><?=$obj->clientemail?></a></small>
</p>

<h5>Dirección(es):</h5>
<?=$obj->stores?>
<br />

<p>Recordá comunicarte con el centro para poder confirmar tu compra y reservar el día y horario del turno.</p>
<hr>

<p>
	Gracias.<br />
	El equipo de EstiloSPA.com
</p>