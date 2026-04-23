<?php 

if(!$User->logged()) Redirect::to('login#'.ROOT.'usuario/calificar/'.$_idsection);
$idsale = intval($_idsection);

$Sales = new Sales();
$SalesComments = new SalesComments();

if(!$sale = $Sales->find($idsale)) Redirect::to('404');
if($sale->iduser != $_userdata->id ) Redirect::to('restricted');

if($comment = $SalesComments->find_by_sale_user($idsale,$_userdata->id)) Redirect::to('usuario/mis-compras');

/* if(!is_null($sale->gallery)){
	$img = json_decode($Sales->data()->gallery);
	$imagepromo = 'url('.ROOT.'/img/promos/'.$img[0]->photoname.'-t.'.$img[0]->extension.')';
}else{
	$imagepromo = 'none';
} */