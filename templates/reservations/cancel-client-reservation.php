<h3>¡Hola <?=$obj->client->name?></h3>

<?php if($obj->promo): ?>
<p>Te informamos que la solicitud de reserva de turno para la experiencia: <a href="<?=ROOT.'promo/'.$obj->promo->permalink.'/'.$obj->promo->id.'-'.Permalink($obj->promo->title) ?>"><?=$obj->promo->title?></a> para el día <?= date('d/m/Y H:i',strtotime($obj->book_date)) ?> hs., ha sido cancelada por el usuario <?=$obj->user->fullname?> (<?=$obj->user->mail?>).</p>
<?php else: ?>
<p>Te informamos que la solicitud de reserva de turno para el día <?= date('d/m/Y H:i',strtotime($obj->book_date)) ?> hs., ha sido cancelada por el usuario <?=$obj->user->fullname?> (<?=$obj->user->mail?>). </p>
<?php endif; ?>

<p>&nbsp;</p>
<p>&nbsp;</p>


<p>
	Gracias.<br />
	El equipo de EstiloSPA.com
</p>