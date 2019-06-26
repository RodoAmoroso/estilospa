<h3>¡Hola <?=$obj->gift->touser.', '.$obj->gift->fromuser ?> te ha regalado la siguiente experiencia EstiloSPA!!!</h3>
<div style="background-color:rgb(240,240,240);padding:26px;margin:16px 0;font-style:italic"><?= $obj->gift->message ?></div>

<p>A continuación te detallamos en qué consiste:</p>

<h4><a href="<?=$obj->promolink?>"><?=$obj->title?></a></h4>
<img src="<?=$obj->image?>" alt="" style="max-width: 200px">
<p><b>Incluye: </b><?=$obj->includes?></p>


<h4>Canjeable en:</h4>
<p>
	<a href="<?= ROOT.'centros/'.$obj->permalink ?>"><?=$obj->clientname?></a><br />
	<small>Email: <a href="mailto:<?=$obj->clientemail?>"><?=$obj->clientemail?></a></small>
</p>

<h5>Dirección(es):</h5>
<?=$obj->stores?>
<br />

<p>Recordá comunicarte con el centro para poder confirmar tu compra y reservar el día y el horario del turno.</p>
<hr>

<p>
	Gracias.<br />
	El equipo de EstiloSPA.com
</p>