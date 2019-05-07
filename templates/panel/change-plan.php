<h2>Hola</h2>
<p>El usuario <?=$obj->name?> ha solicitado cambiar el plan actual de su centro <a href="<?=ROOT.'centros/'.$obj->client_permalink?>"><?=$obj->client_name?></a></p>	
<h4>Datos del usuario:</h4>
<ul>
	<li>Nombre: <?=$obj->name.' '.$obj->lastname?></li>
	<li>Email: <?=$obj->mail?></li>
	<li>Plan actual: <?=$obj->planname?></li>
</ul>