
<?php if($obj->promo): ?>
<h3>¡Hola <?=$obj->client->name?>, tenés una nueva solicitud de reserva de turno para la experiencia: <a href="<?=$obj->promo->promolink ?>"><?=$obj->promo->title?></a></h3>

<?php else: ?>
<h3>¡Hola <?=$obj->client->name?>, tenés una nueva solicitud de reserva</a></h3>
<?php endif; ?>

<div style="background-color:rgb(246,246,246);padding:16px;margin:16px 0;line-height: 1.5rem;">
	<div>Nombre de Usuario: <?=$obj->user->fullname?></div>
	<div>Email: <?= $obj->user->mail ?></div>
	<div>Día y Hora: <?= date('d/m/Y H:i',strtotime($obj->book_date)) ?></div>
</div>

<p>&nbsp;</p>


<p>Para confirmar o cancelarlo, hacé click en el siguiente enlace: <a href="<?=PANEL.'reserva/'.$obj->id?>"><?=PANEL.'reserva/'.$obj->id?></a></p>


<p>&nbsp;</p>
<p>&nbsp;</p>

<p>
	Gracias.<br />
	El equipo de EstiloSPA.com
</p>