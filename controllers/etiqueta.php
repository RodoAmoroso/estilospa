<?php 

$arrsection = explode('-',$_SUBSECTION);
$idglossary = $arrsection[0];
if(!$_GLOSSARY->find($idglossary)) Redirect::javascript('404');

////////////////////// SEO ////////////////////////////////////////
$_TITLE = $_GLOSSARY->data()->name.' - '.TITLE;
$_GLOSSARY->addvisit($idglossary);
$_DESCRIPTION = substr(strip_tags($_GLOSSARY->data()->description),0,500);
$imgjson = json_decode($_GLOSSARY->data()->image);
$imgheader = '';
if(!empty($imgjson)){
	$imgheader = 'img/glossary/'.$imgjson->photoname.'.'.$imgjson->extension;
	$_IMGFACEBOOK = $imgheader;
}


$_PROMOS->status = '1:1';
$_PROMOS->sort = 'rand';
$_PROMOS->visible = true;
$_PROMOS->keywords = $_GLOSSARY->data()->name;
$_PROMOS->arrglossary = [$idglossary];
$_PROMOS->limit = '0,12';