<h4>Hola <?=$obj->clientname?></h4>

<p>¿<?= $obj->username.' ('.$obj->usermail.')' ?>  ya tomó el servicio de la promo <a href="<?= ROOT.'promo/'.$obj->permalink.'/'.$obj->idpromo.'-'.Permalink($obj->promotitle) ?>"><?=$obj->promotitle?></a>? </p>

<p><b>Nro de Comprobante: <?= $obj->collection_id ?></b>.</p>

<p>Si es así, por favor ingresá al sitio de EstiloSPA y actualiza el estado del servicio como <b>Brindado</b>, (o <b>Cancelado</b> en caso de haberse cancelado el servicio). Con tu aporte podemos mejorar y ofrecer un mejor servicio día a día.</p>

<p>&nbsp;</p>

<p><a href='<?=ROOT.'panel/venta/'.$obj->id?>' style='background-color:#e7127c;border-color:#e7127c;color:#fff;padding:6px 12px;text-align:center;'>Establecer Estado</a></p>

<hr>

<p>
	Gracias.<br />
	El equipo de EstiloSPA.com
</p>