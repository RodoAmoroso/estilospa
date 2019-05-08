<?php 

$_arrjs[] = ['folder'=>'lib/','script'=>'slider'];
$_arrjs[] = ['folder'=>'site/','script'=>'questions'];
$_arrjs[] = ['script'=>'https://maps.googleapis.com/maps/api/js?key=AIzaSyC2m93XcFMuCAPZSjBUNsZO24UJOSPSF1M'];


if(!$Clients->find($_subsection)) Redirect::javascript('home');
$Clients->addvisit();
$logoclient = json_decode($Clients->data()->logo);

$StoresClient = new Stores();
$StoresClient->get($Clients->data()->id);

$Features = new Features();
$Features->get($Clients->data()->id);

$Promos->status = '1:1';
$Promos->find($Clients->data()->id);

////////////////////// SEO ////////////////////////////////////////
$_TITLE = $Clients->data()->name.' - '.TITLE;

$FirstAddress = $StoresClient->data()[0]->address.' &bullet; '.(!empty($StoresClient->data()[0]->additional) ? $StoresClient->data()[0]->additional.' &bullet; ' : '').$StoresClient->data()[0]->city.' &bullet; '.$Provinces[$StoresClient->data()[0]->idprovince];

//$_ADDRESS = $StoresClient->data()[0]->address.', '.$StoresClient->data()[0]->city;

$_DESCRIPTION = $FirstAddress.' - '.$Clients->data()->subtitle;
$arrtags = explode(',',$Clients->data()->glossary);
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
$_IMGFACEBOOK = 'img/clients/'.$logoclient->photoname.'.'.$logoclient->extension;

//var_dump (number_format($Clients->data()->fee,2,'.',''));

$_arrjs[] = ['folder'=>'lib/','script'=>'owl.carousel.min'];
$_arrcss[] = ['folder'=>'lib/','style'=>'owl.carousel.min'];
$_arrcss[] = ['folder'=>'lib/','style'=>'owl.theme.default.min'];



///echo var_dump(preg_match('((http|https)\:\/\/)',$Clients->data()->web));