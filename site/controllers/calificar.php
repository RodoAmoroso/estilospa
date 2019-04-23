<?php 

if(!$User->logged()) Redirect::to('login#'.ROOT.'calificar/'.$_subsection);
$idsale = intval($_subsection);

$Sales = new Sales();
$Sales->iduser = $User->data()->id;
$Sales->find($idsale);

if(is_null($Sales->data()->id)) Redirect::to('mis-compras');
if(!$Sales->checkqualify($idsale)) Redirect::to('mis-compras');

if(!is_null($Sales->data()->gallery)){
	$img = json_decode($Sales->data()->gallery);
	$imagepromo = 'url('.ROOT.'/img/promos/'.$img[0]->photoname.'-t.'.$img[0]->extension.')';
}else{
	$imagepromo = 'none';
}