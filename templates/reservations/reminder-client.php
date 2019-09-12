
<h3>¡Hola <?=$obj->client_name?></h3>

<p>Te recordamos que hoy <?= date('d/m/Y',strtotime($obj->book_date)) ?> a las <?= date('H:i',strtotime($obj->book_date)) ?> hs. tenés una reserva de <?=$obj->user_name?> (<?=$obj->user_email?>) <?= !is_null($obj->title) ? 'de la siguiente experiencia' : '' ?>:</p>

<?php if(!is_null($obj->title)): ?>
<h4><a href="<?=ROOT.'promos/'.$obj->permalink.'/'.$obj->promoid.'-'.Permalink($obj->title)?>"><?=$obj->title?></a></h4>
<?php $img = json_decode($obj->gallery) ?>
<img src="<?=View::img('promos',$img[0]->photoname.'-t.'.$img[0]->extension)?>" alt="" style="max-width: 200px">
<?php endif; ?>

<p>&nbsp;</p>

<p>
	Gracias.<br />
	El equipo de EstiloSPA.com
</p>