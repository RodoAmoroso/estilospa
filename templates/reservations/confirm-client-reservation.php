<h3>¡Hola <?=$obj->client->name?></h3>

<p>
	Te informamos que la solicitud de reserva de turno para la promo: <a href="<?=ROOT.'promo/'.$obj->promo->permalink.'/'.$obj->promo->id.'-'.Permalink($obj->promo->title) ?>"><?=$obj->promo->title?></a> para el día  <?= date('d/m/Y H:i',strtotime($obj->book_date)) ?> hs., ha sido confirmada por el usuario.
</p>


<div style="background-color:rgb(246,246,246);padding:16px;margin:16px 0;line-height: 1.5rem;">
	<div>Nombre de Usuario: <?=$obj->user->name.' '.$obj->user->lastname?></div>
	<div>Email: <?= $obj->user->mail ?></div>
	<div>Día y Hora: <?= date('d/m/Y H:i:s',strtotime($obj->book_date)) ?></div>
</div>


<p>&nbsp;</p>

<p><a href="<?=PANEL.'reservas/'?>">Agenda de Reservas</a></p>

<p>&nbsp;</p>
<p>&nbsp;</p>

<p>
	Gracias.<br />
	El equipo de EstiloSPA.com
</p>