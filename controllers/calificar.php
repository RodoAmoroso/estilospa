<?php 

if(!$_USER->logged()) Redirect::to('login#'.ROOTPATH.'calificar/'.$_SUBSECTION);
$idsale = intval($_SUBSECTION);

$_SALES = new Sales();
$_SALES->iduser = $_USER->data()->id;
$_SALES->find($idsale);

if(is_null($_SALES->data()->id)) Redirect::to('mis-compras');
if(!$_SALES->checkqualify($idsale)) Redirect::to('mis-compras');

if(!is_null($_SALES->data()->gallery)){
	$img = json_decode($_SALES->data()->gallery);
	$imagepromo = 'url('.ROOTPATH.'/img/promos/'.$img[0]->photoname.'-t.'.$img[0]->extension.')';
}else{
	$imagepromo = 'none';
}