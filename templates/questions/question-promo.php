<p>Hola, hicieron una pregunta para la siguiente promo: <a href="<?=$obj->promo_link?>"><?=$obj->promo_title?></a></p>

<div style="background-color:rgb(240,240,240);padding:16px;color:rgb(90,90,90)">
	<p><?=$obj->question?></p>
	<small>Enviada por <?=$obj->user_name?> el <?=$obj->question_date?> hs.</small>
</div>

<p><a href="<?=View::url('responder-pregunta',$obj->questionid)?>" style="background-color:#68bbce;padding:4px 10px;color:white;text-decoration:none" >Responder</a></p>
<p>&nbsp;</p>


<small>NO respondas este email. Para poder responder a la pregunta hacé click en el botón Responder que te llevará al sitio de EstiloSPA y desde allí podrás responderla.</small>