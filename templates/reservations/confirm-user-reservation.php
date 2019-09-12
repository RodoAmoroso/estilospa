<h3>¡Hola <?=$obj->user->name?></h3>


<?php if($obj->promo): ?>
<p>Te informamos que la solicitud de reserva de turno para la promo: <a href="<?=ROOT.'promo/'.$obj->promo->permalink.'/'.$obj->promo->id.'-'.Permalink($obj->promo->title) ?>"><?=$obj->promo->title?></a> para el día  <?= date('d/m/Y H:i',strtotime($obj->book_date)) ?> hs., ha sido confirmada por el centro.</p>
<?php else: ?>
<p>Te informamos que la solicitud de reserva de turno para el día  <?= date('d/m/Y H:i',strtotime($obj->book_date)) ?> hs., ha sido confirmada por <a href="<?=ROOT.'centros/'.$obj->client->permalink?>"><?=$obj->client->name?></a>.</p>
<?php endif; ?>


<?php if($obj->promo && $obj->promo->sale && !$obj->sale): ?>
<p>Podés pagar online la promo mediante Mercado Pago, hasta en 12 cuotas, con todos los bancos:</p>
<p><a href="<?=$obj->promo->promolink.'/'.$obj->id.'#comprar'?>" style="background-color:#e7127c;padding:8px 16px;color:white;text-decoration:none;" >Pagar Ahora</a></p>
<p>&nbsp;</p>
<?php endif; ?>


<p>&nbsp;</p>

<p>
	Gracias.<br />
	El equipo de EstiloSPA.com
</p>