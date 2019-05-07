<h3>¡Hola <?=$obj->client->name?></h3>

<p>
	Te informamos que la solicitud de reserva de turno para la promo: <a href="<?=ROOT.'promo/'.$obj->promo->permalink.'/'.$obj->promo->id.'-'.Permalink($obj->promo->title) ?>"><?=$obj->promo->title?></a> para el día <?= date('d/m/Y H:i',strtotime($obj->book_date)) ?> hs., ha sido cancelada por el usuario.
</p>

<p>&nbsp;</p>
<p>&nbsp;</p>


<p>
	Gracias.<br />
	El equipo de EstiloSPA.com
</p>