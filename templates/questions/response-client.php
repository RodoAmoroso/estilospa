<p>Hola <?=$obj->user_name?>, respondieron tu pregunta en EstiloSPA.com</p>

<div style="background-color:rgb(240,240,240);padding:16px;color:rgb(90,90,90)">
	<div style="padding:10px">
		<h5 style="margin:2px 0">Pregunta:</h5>
		<p><?=$obj->question?></p>
		<small style="color:rgb(140,140,140)">Enviada el <?=$obj->question_date?> hs.</small>
	</div>

	<hr style="border-top:1px solid white">
	<div style="padding:10px 10px 10px 20px">
		<h5 style="margin:2px 0">Respuesta de <a href="<?=$obj->client_link?>"><?=$obj->client->name?></a>:</h5>
		<p><?=$obj->response?></p>
		<small style="color:rgb(140,140,140)">Enviada el <?=$obj->response_date?> hs.</small>
	</div>

</div>

<p><a href="<?=$obj->client_link.'#form_question'?>" style="background-color:#68bbce;padding:8px 16px;color:white;text-decoration:none;" >Hacer otra pregunta</a></p>
<p>&nbsp;</p>


<small>NO respondas este email. Para poder hacer otra pregunta hacé click en el botón 'Hacer otra pregunta' que te llevará al sitio de EstiloSPA y desde allí podrás formularla.</small>