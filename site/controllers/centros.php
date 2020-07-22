<?php

$_arrjs[] = ['folder'=>'lib/','script'=>'slider'];
$_arrjs[] = ['folder'=>'site/','script'=>'questions'];
$_arrjs[] = ['folder'=>'site/','script'=>'reservations'];


$_arrjs[] = ['folder'=>'lib/','script'=>'leaflet'];
$_arrcss[] = ['folder'=>'lib/','style'=>'leaflet'];



if(!$Clients->find($_subsection)) Redirect::javascript('home');
$clientdata = $Clients->data();
$Clients->addvisit($clientdata->id);
if($User->logged()) $Stats->add_client_view($User->data()->id,$clientdata->id);

$logoclient = json_decode($clientdata->logo);

$StoresClient = new Stores();
$StoresClient->get($clientdata->id);

$Features = new Features();
$Features->get($clientdata->id);

$Promos->status = '1:1';
$Promos->find($clientdata->id);

////////////////////// SEO ////////////////////////////////////////
$_TITLE = $clientdata->name.' - '.TITLE;

$FirstAddress = $StoresClient->data()[0]->address.' &bullet; '.(!empty($StoresClient->data()[0]->additional) ? $StoresClient->data()[0]->additional.' &bullet; ' : '').$StoresClient->data()[0]->city.' &bullet; '.$Provinces[$StoresClient->data()[0]->idprovince];

//$_ADDRESS = $StoresClient->data()[0]->address.', '.$StoresClient->data()[0]->city;

$_DESCRIPTION = $FirstAddress.' - '.$clientdata->subtitle;
$arrtags = explode(',',$clientdata->glossary);
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

//var_dump (number_format($clientdata->fee,2,'.',''));

$_arrjs[] = ['folder'=>'lib/','script'=>'owl.carousel.min'];
$_arrcss[] = ['folder'=>'lib/','style'=>'owl.carousel.min'];
$_arrcss[] = ['folder'=>'lib/','style'=>'owl.theme.default.min'];


$_today = new DateTime();
$_arrdays = array();
for($i=1; $i<=4; $i++){
	$_arrdays[] = array(
		'day'=>$_today->format('d'),
		'dayname'=>$_today->format('D'),
		'name'=>Dates::translateDays($_today->format('l'))
	);
	$_today->modify('+1 day');
}