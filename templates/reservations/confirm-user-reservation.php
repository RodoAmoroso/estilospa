<h3>¡Hola <?=$obj->user->name?></h3>

<p>
	Te informamos que la solicitud de reserva de turno para la promo: <a href="<?=ROOT.'promo/'.$obj->promo->permalink.'/'.$obj->promo->id.'-'.Permalink($obj->promo->title) ?>"><?=$obj->promo->title?></a> para el día  <?= date('d/m/Y H:i',strtotime($obj->book_date)) ?> hs., ha sido confirmada por el centro.
</p>


<?php if($obj->promo->sale): ?>
<p>Podés pagar online la promo mediante Mercado Pago, hasta en 12 cuotas, con todos los bancos, haciendo <a href="<?=ROOT.'promo/'.$obj->promo->permalink.'/'.$obj->promo->id.'-'.Permalink($obj->promo->title).'#comprar'?>">click aquí</a></p>
<?php endif; ?>


<p>&nbsp;</p>
<p>&nbsp;</p>

<p>
	Gracias.<br />
	El equipo de EstiloSPA.com
</p>