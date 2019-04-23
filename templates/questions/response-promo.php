<p>Hola <?=$obj->user_name?>, respondieron tu pregunta para la siguiente promo: <a href="<?=$obj->promo_link?>"><?=$obj->promo_title?></a></p>

<div style="background-color:rgb(240,240,240);padding:16px;color:rgb(90,90,90)">
	<h5>Pregunta:</h5>
	<p><?=$obj->question?></p>

	<p>&nbsp;</p>
	<div style="padding-left:16px">
		<h5>Respuesta:</h5>
		<p><?=$obj->response?></p>
		<small>Enviada el <?=$obj->response_date?> hs.</small>
	</div>

</div>

<p><a href="<?=$obj->promo_link.'#form_question'?>" style="background-color:#68bbce;padding:4px 10px;color:white;text-decoration:none;" >Hacer otra pregunta</a></p>
<p>&nbsp;</p>


<small>NO respondas este email. Para poder hacer otra pregunta hacé click en el botón 'Hacer otra pregunta' que te llevará al sitio de EstiloSPA y desde allí podrás formularla.</small>