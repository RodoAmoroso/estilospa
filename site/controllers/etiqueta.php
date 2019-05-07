<?php 


$arrsection = explode('-',$_subsection);
$idglossary = $arrsection[0];
if(!$Glossary->find($idglossary)) Redirect::javascript('404');

////////////////////// SEO ////////////////////////////////////////
$_TITLE = $Glossary->data()->name.' - '.TITLE;
$Glossary->addvisit($idglossary);
$_DESCRIPTION = substr(strip_tags($Glossary->data()->description),0,500);
$imgjson = json_decode($Glossary->data()->image);
$imgheader = '';
if(!empty($imgjson)){
	$imgheader = 'img/glossary/'.$imgjson->photoname.'.'.$imgjson->extension;
	$_IMGFACEBOOK = $imgheader;
}


$Promos->status = '1:1';
$Promos->sort = 'rand';
$Promos->visible = true;
$Promos->keywords = $Glossary->data()->name;
$Promos->arrglossary = [$idglossary];
$Promos->limit = '0,12';

$_arrjs[] = ['folder'=>'site/','script'=>'questions'];

$_arrjs[] = ['folder'=>'lib/','script'=>'owl.carousel.min'];
$_arrcss[] = ['folder'=>'lib/','style'=>'owl.carousel.min'];
$_arrcss[] = ['folder'=>'lib/','style'=>'owl.theme.default.min'];