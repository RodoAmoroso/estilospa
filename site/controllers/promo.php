<?php 

$_arrjs[] = ['folder'=>'lib/','script'=>'slider'];
$_arrjs[] = ['folder'=>'site/','script'=>'questions'];
$_arrjs[] = ['folder'=>'site/','script'=>'reservations'];


if(!$Clients->find($_subsection)) Redirect::javascript('home');
if($_idsection == '') Redirect::javascript('home');
$arrsection = explode('-',$_idsection);

if(!$Promos->find(intval($arrsection[0]))) Redirect::javascript('home');
if(!$Promos->data()->statusstart || !$Promos->data()->statusfinish) Redirect::javascript('home');

$Promos->addvisit();
if($User->logged()) $Stats->add_promo_view($User->data()->id,$Promos->data()->id);


$jsonlogo = json_decode($Clients->data()->logo);
if(file_exists(PATH.'/img/clients/'.$jsonlogo->photoname.'.'.$jsonlogo->extension)): 
	$logo = ROOT.'img/clients/'.$jsonlogo->photoname.'.'.$jsonlogo->extension;
else:
	$logo = ROOT.'img/client-default.jpg';
endif;
$gallery = json_decode($Promos->data()->gallery);

////////////////////// SEO ////////////////////////////////////////
$_TITLE = $Promos->data()->title.' - '.TITLE;
$_DESCRIPTION = $Promos->data()->description;
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
if($Promos->data()->gallery != ''){
	$_IMGFACEBOOK = 'img/promos/'.$gallery[0]->photoname.'-o.'.$gallery[0]->extension;
}

$MPConfig = new MPConfig();
$mp = $MPConfig->find($Promos->data()->idclient);
$showsalebuttons = false;
if($Promos->data()->amount && $Promos->data()->sale && $mp){
	$showsalebuttons = true;
}

$Vouchers = new Vouchers();

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
