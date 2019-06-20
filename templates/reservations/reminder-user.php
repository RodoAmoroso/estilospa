
<h3>¡Hola <?=$obj->user_name?></h3>
<p>Te recordamos que hoy <?= date('d/m/Y',strtotime($obj->book_date)) ?> a las <?= date('H:i',strtotime($obj->book_date)) ?> hs. tenés una reserva para disfrutar de la siguiente experiencia en <a href="<?=ROOT.'centros/'.$obj->permalink?>"><?=$obj->client_name?></a>:</p>

<h4><a href="<?=ROOT.'promos/'.$obj->permalink.'/'.$obj->promoid.'-'.Permalink($obj->title)?>"><?=$obj->title?></a></h4>

<?php $img = json_decode($obj->gallery) ?>
<img src="<?=View::img('promos',$img[0]->photoname.'-t.'.$img[0]->extension)?>" alt="" style="max-width: 200px">
<p><b>¿Qué incluye?: </b><?=$obj->includes?></p>


<p>&nbsp;</p>

<p>
	Gracias.<br />
	El equipo de EstiloSPA.com
</p>