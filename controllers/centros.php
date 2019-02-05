<?php 

if(!$_CLIENTS->find($_SUBSECTION)) Redirect::javascript('home');
$_CLIENTS->addvisit();
$logoclient = json_decode($_CLIENTS->data()->logo);

$_STORESCLIENT = new Stores();
$_STORESCLIENT->get($_CLIENTS->data()->id);

$_FEATURES = new Features();
$_FEATURES->get($_CLIENTS->data()->id);

$_PROMOS->status = '1:1';
$_PROMOS->find($_CLIENTS->data()->id);

////////////////////// SEO ////////////////////////////////////////
$_TITLE = $_CLIENTS->data()->name.' - '.TITLE;

$_FIRSTADDRESS = $_STORESCLIENT->data()[0]->address.' &bullet; '.(!empty($_STORESCLIENT->data()[0]->additional) ? $_STORESCLIENT->data()[0]->additional.' &bullet; ' : '').$_STORESCLIENT->data()[0]->city.' &bullet; '.$_PROVINCES[$_STORESCLIENT->data()[0]->idprovince];
$_ADDRESS = $_STORESCLIENT->data()[0]->address.', '.$_STORESCLIENT->data()[0]->city;

$_DESCRIPTION = $_FIRSTADDRESS.' - '.$_CLIENTS->data()->subtitle;
$arrtags = explode(',',$_CLIENTS->data()->glossary);
$arrtagsnames = '';
if(count($arrtags)):
	foreach($arrtags as $kt=>$vt):
		if($_GLOSSARY->find($vt)):
			$arrtagsnames .= $_GLOSSARY->data()->name;
			if($kt != count($arrtags)-1) $arrtagsnames .= ', ';
		endif;
	endforeach;
endif;
$_KEYWORDS = $arrtagsnames;
$_IMGFACEBOOK = 'img/clients/'.$logoclient->photoname.'.'.$logoclient->extension;

//var_dump (number_format($_CLIENTS->data()->fee,2,'.',''));