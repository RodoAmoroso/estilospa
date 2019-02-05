<?php 

$_BLOG = new Blog();
$arrsection = explode('-',$_SUBSECTION);
$idblog = intval($arrsection[0]);
if(!$_BLOG->find($idblog)) Redirect::javascript('404');
$_BLOG->addvisit();
////////////////////// SEO ////////////////////////////////////////
$_TITLE = $_BLOG->data()->title.' - '.TITLE;
$_DESCRIPTION = $_BLOG->data()->shortdescription;
$arrtags = explode(',',$_BLOG->data()->glossary);
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
$bloggallery = json_decode($_BLOG->data()->gallery);
if(!isset($bloggallery[0]->video)) $_IMGFACEBOOK = 'img/blog/'.$bloggallery[0]->photoname.'-o.'.$bloggallery[0]->extension;