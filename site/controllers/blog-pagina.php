<?php 

$_arrjs[] = ['folder'=>'lib/','script'=>'slider'];


$Blog = new Blog();
$arrsection = explode('-',$_subsection);
$idblog = intval($arrsection[0]);
if(!$Blog->find($idblog)) Redirect::javascript('404');
$Blog->addvisit();
////////////////////// SEO ////////////////////////////////////////
$_TITLE = $Blog->data()->title.' - '.TITLE;
$_DESCRIPTION = $Blog->data()->shortdescription;
$arrtags = explode(',',$Blog->data()->glossary);
$arrtagsnames = '';
if(count($arrtags)):
	foreach($arrtags as $kt=>$vt):
		if($Glossary->find($vt)):
			$arrtagsnames .= $Glossary->data()->name;
			if($kt != count($arrtags)-1) $arrtagsnames .= ', ';
		endif;
	endforeach;
endif;
$_KEYWORDS = $arrtagsnames;
$bloggallery = json_decode($Blog->data()->gallery);
if(!isset($bloggallery[0]->video)) $_IMGFACEBOOK = 'img/blog/'.$bloggallery[0]->photoname.'-o.'.$bloggallery[0]->extension;



$_arrjs[] = ['folder'=>'lib/','script'=>'owl.carousel.min'];
$_arrcss[] = ['folder'=>'lib/','style'=>'owl.carousel.min'];
$_arrcss[] = ['folder'=>'lib/','style'=>'owl.theme.default.min'];