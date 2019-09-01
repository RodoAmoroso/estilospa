<h3>¡Hola <?=$obj->gift->to_user->name?>!</h3>
<h4><?= $obj->gift->from_user->name ?> te ha regalado la siguiente experiencia EstiloSPA.com!!!</h4>

<div style="background-color:rgb(240,240,240);padding:26px;margin:16px 0;font-style:italic"><?= $obj->gift->message ?></div>

<p>A continuación te detallamos en qué consiste:</p>

<h4><a href="<?=$obj->promolink?>"><?=$obj->title?></a></h4>
<img src="<?=$obj->image?>" alt="" style="max-width: 200px">
<p><b>Incluye: </b><?=$obj->includes?></p>


<p>&nbsp;</p>


<a href="<?=ROOT.'voucher-regalos/'.$obj->hash?>" style="background-color:#e7127c; padding:4px 10px; color:white; text-decoration:none">Descargar Voucher</a>

<p>&nbsp;</p>

<p>
	Gracias.<br />
	El equipo de EstiloSPA.com
</p>