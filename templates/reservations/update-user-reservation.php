<h3>¡Hola <?=$obj->user->name?></h3>

<?php if($obj->promo): ?>
<p>Te informamos que la solicitud de reserva de turno para la promo: <a href="<?=ROOT.'promo/'.$obj->promo->permalink.'/'.$obj->promo->id.'-'.Permalink($obj->promo->title) ?>"><?=$obj->promo->title?></a> no se ha podido confirmar. El centro sugiere este otro turno para el día <?= date('d/m/Y H:i',strtotime($obj->book_date)) ?> hs.</p>
<?php else: ?>
<p>Te informamos que la solicitud de reserva de turno no se ha podido confirmar. <a href="<?=$obj->client->permalink?>" target="_blank"><?=$obj->client->name?></a> sugiere este otro turno para el día <?= date('d/m/Y H:i',strtotime($obj->book_date)) ?> hs.</p>
<?php endif; ?>

<p>&nbsp;</p>

<a href="<?=ROOT.'reserva/confirmar/'.$obj->id?>" style="background-color:#68bbce; padding:4px 10px; color:white; text-decoration:none">Confirmar</a>

<a href="<?=ROOT.'reserva/cancelar/'.$obj->id?>" style="background-color:#e7127c; padding:4px 10px; color:white; text-decoration:none">Cancelar</a>

<p>&nbsp;</p>
<p>&nbsp;</p>

<p>
	Gracias.<br />
	El equipo de EstiloSPA.com
</p>