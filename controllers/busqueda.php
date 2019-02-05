<?php 

$_ARRSEARCH = explode('_-_',$_SUBSECTION);

$query = isset($_REQUEST['q']) ? $_REQUEST['q'] : '';
$location = isset($_REQUEST['l']) ? $_REQUEST['l'] : '';

$_SEARCH = new Search();

$searchtext = '';
$search_main = str_replace('-',' ',$query); //clienttype-glossaryname-promotype
$search_main = empty($search_main) ? ' ' : $search_main;
$search_locations = isset($location) ? str_replace('-',' ',$location) : '';
if(!empty($query)){
	$searchtext = ucwords(str_replace('_',', ',$search_main));
}
if(!empty($location)){
	if(!empty($query)): $searchtext .= ' en '; else: $searchtext = 'Centros y Promos en '; endif;
	$searchtext .= ucwords(str_replace('_',', ',$search_locations));
}
$arrwordsmain = (array) $search_main;
$arrwordslocations = (array) $search_locations;
////////////// CLIENTTYPES //////////////
$_CLIENTTYPES->keywords = $arrwordsmain;
$_CLIENTTYPES->searchmixed = 1;
$arrtypes = array();
if($search_main!=' '){
	if($_CLIENTTYPES->get()){
		foreach($_CLIENTTYPES->data() as $type):
			$arrtypes[] = $type->id;
		endforeach;
	}
}
////////////// GLOSSARY ///////////////
$_GLOSSARY->keywords = $arrwordsmain;
$_GLOSSARY->searchmixed = 1;
$arrglossary = array();
if($search_main!=' '){
	if($_GLOSSARY->get()){
		foreach($_GLOSSARY->data() as $glossary):
			$arrglossary[] = $glossary->id;
		endforeach;
	}
}
////////////// PROMO TYPES //////////
$_PROMOTYPES = new PromoTypes();
$_PROMOTYPES->keywords = $arrwordsmain;
$_PROMOTYPES->searchmixed = 1;
$arrpromotypes = array();
if($_PROMOTYPES->get()){
	foreach($_PROMOTYPES->data() as $promotype):
		$arrpromotypes[] = $promotype->id;
	endforeach;
}
////////////// LOCATIONS ////////////////
$_STORES->keywords = $arrwordslocations;
$_STORES->searchmixed = 0;
$arridclients = array();
if(!empty($search_locations)){	
	if($_STORES->search()){
		foreach($_STORES->data() as $idclient):
			$arridclients[] = $idclient->idclient;
		endforeach;
	}
}
///print_r($arridclients);
///echo '<br />';
$totalresults = 60;
////////////// PROMOS ///////////////////
$_PROMOS->keywords = $search_main == ' ' ? '' : $search_main;
$_PROMOS->status = '1:1';
$_PROMOS->searchmixed = 1;
$_PROMOS->sort = 'position';
$_PROMOS->visible = true;
$_PROMOS->limit = '0,'.$totalresults;
$_PROMOS->arrtypes = $arrtypes;
$_PROMOS->arrpromotypes = $arrpromotypes;
$_PROMOS->arridclients = $arridclients;
$_PROMOS->get();
///print_r($arridclients);
///////////// CLIENTS ///////////////////
$_CLIENTS->keywords = $search_main == ' ' ? '' : $search_main;
$_CLIENTS->sort = 'promocount';
$_CLIENTS->searchmixed = 0;
$_CLIENTS->visible = 1;
///$_CLIENTS->searchpromos = true;
$_CLIENTS->limit = '0,'.$totalresults;
$_CLIENTS->arrtypes = $arrtypes;
$_CLIENTS->arrglossary = $arrglossary;
$_CLIENTS->arridclients = $arridclients;
$_CLIENTS->get();
$_QCLIENTS = array();
if(!empty($query) || !empty($location)) $_QCLIENTS = $_CLIENTS->data();
////////////// BLOG //////////////////////
$_BLOG = new Blog();
$_BLOG->limit = '0,'.$totalresults;
$_BLOG->keywords = $search_main;
$_BLOG->arrglossary = $arrglossary;
if(!empty($query) || !empty($location)) $_BLOG->get();

$MPConfig = new MPConfig();
$_STORES = new Stores();