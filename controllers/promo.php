<?php 

if(!$_CLIENTS->find($_SUBSECTION)) Redirect::javascript('home');
if($_IDSECTION == '') Redirect::javascript('home');
$arrsection = explode('-',$_IDSECTION);

if(!$_PROMOS->find(intval($arrsection[0]))) Redirect::javascript('home');
if(!$_PROMOS->data()->statusstart || !$_PROMOS->data()->statusfinish) Redirect::javascript('home');

$_PROMOS->addvisit();


$jsonlogo = json_decode($_CLIENTS->data()->logo);
if(file_exists(PATH.'/img/clients/'.$jsonlogo->photoname.'.'.$jsonlogo->extension)): 
	$logo = ROOTPATH.'img/clients/'.$jsonlogo->photoname.'.'.$jsonlogo->extension;
else:
	$logo = ROOTPATH.'img/client-default.jpg';
endif;
$gallery = json_decode($_PROMOS->data()->gallery);

////////////////////// SEO ////////////////////////////////////////
$_TITLE = $_PROMOS->data()->title.' - '.TITLE;
$_DESCRIPTION = $_PROMOS->data()->description;
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
if($_PROMOS->data()->gallery != ''){
	$_IMGFACEBOOK = 'img/promos/'.$gallery[0]->photoname.'-o.'.$gallery[0]->extension;
}

$MPConfig = new MPConfig();
$mp = $MPConfig->find($_PROMOS->data()->idclient);
$showsalebuttons = false;
if($_PROMOS->data()->amount && $_PROMOS->data()->sale && $mp){
	$showsalebuttons = true;
}

$_VOUCHERS = new Vouchers();