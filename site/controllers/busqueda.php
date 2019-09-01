<?php 

//$_ARRSEARCH = explode('_-_',$_subsection);

$query = isset($_uri[1]) && !empty($_uri[1]) ? $_uri[1] : '-';
$location = isset($_uri[2]) && !empty($_uri[2]) ? $_uri[2] : '-';
$page = isset($_uri[3]) && !empty($_uri[3]) ? $_uri[3] : 1;


//$searchtext = '';
$search_main = trim(str_replace('-',' ',$query)); //clienttype-glossaryname-promotype
//$search_main = empty($search_main) ? ' ' : $search_main;
$search_locations = trim(str_replace('-',' ',$location));

$Stats->add_search_word($search_main);
$Stats->add_search_location($search_locations);


/*if(!empty($query)){
	$searchtext = ucwords(str_replace('_',', ',$search_main));
}
if(!empty($location)){
	if(!empty($query)): $searchtext .= ' en '; else: $searchtext = 'Centros y Promos en '; endif;
	$searchtext .= ucwords(str_replace('_',', ',$search_locations));
}*/
$arrwordsmain = (array) $search_main;
$arrwordslocations = (array) $search_locations;

////////////// CLIENTTYPES //////////////
/*$ClientTypes->keywords = $arrwordsmain;
$ClientTypes->searchmixed = 1;
$arrtypes = array();
if(!empty($search_main)){
	if($ClientTypes->get()){
		foreach($ClientTypes->data() as $type):
			$arrtypes[] = $type->id;
		endforeach;
	}
}*/
////////////// GLOSSARY ///////////////
$Glossary->keywords = $arrwordsmain;
$Glossary->searchmixed = 1;
$arrglossary = array();
if(!empty($search_main)){
	if($Glossary->get()){
		foreach($Glossary->data() as $glossary):
			$arrglossary[] = $glossary->id;
		endforeach;
	}
}
////////////// PROMO TYPES //////////
/*$_PROMOTYPES = new PromoTypes();
$_PROMOTYPES->keywords = $arrwordsmain;
$_PROMOTYPES->searchmixed = 1;
$arrpromotypes = array();
if($_PROMOTYPES->get()){
	foreach($_PROMOTYPES->data() as $promotype):
		$arrpromotypes[] = $promotype->id;
	endforeach;
}*/
////////////// LOCATIONS ////////////////
$Stores->keywords = $arrwordslocations;
$Stores->searchmixed = 0;
$arridclients = array();
if(!empty($search_locations)){	
	if($Stores->search()){
		foreach($Stores->data() as $store):
			$arridclients[] = $store->idclient;
		endforeach;
	}
}
//show_array(var_dump($arridclients));
//show_array(var_dump($arrwordslocations));
///echo '<br />';
$page_results = 25;
//echo $_page;
////////////// PROMOS ///////////////////
$Promos->keywords = empty($search_main) ? '' : $search_main;
$Promos->status = '1:1';
$Promos->searchmixed = 1;
$Promos->sort = 'position';
$Promos->visible = true;
///$Promos->arrtypes = $arrtypes;
///$Promos->arrpromotypes = $arrpromotypes;
$Promos->arrglossary = $arrglossary;
$Promos->arridclients = $arridclients;
$Promos->get();
$total_results = $Promos->data() ? count($Promos->data()) : 0;

$Promos->limit = (($page*$page_results)-$page_results).','.$page_results;
$Promos->get();

if(!$total_results){
	Redirect::to('categoria/'.$query.'/'.$location);
}
///show_array($Promos->limit);

///////////// CLIENTS ///////////////////
/*$Clients->keywords = $search_main == ' ' ? '' : $search_main;
$Clients->sort = 'promocount';
$Clients->searchmixed = 0;
$Clients->visible = 1;
$Clients->limit = '0,'.$totalresults;
$Clients->arrtypes = $arrtypes;
$Clients->arrglossary = $arrglossary;
$Clients->arridclients = $arridclients;
$Clients->get();
$_QCLIENTS = array();
if(!empty($query) || !empty($location)) $_QCLIENTS = $Clients->data();

$Blog = new Blog();
$Blog->limit = '0,'.$totalresults;
$Blog->keywords = $search_main;
$Blog->arrglossary = $arrglossary;
if(!empty($query) || !empty($location)) $Blog->get();*/

$MPConfig = new MPConfig();
$Stores = new Stores();


///show_array(var_dump($location));