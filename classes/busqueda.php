<?php

$_BLOG = new Blog();

$_ARRSEARCH = explode('_-_',$_SUBSECTION);
$_SEARCH = new Search();
///echo $_IDSECTION; //page nr.
$searchtext = '';
$search_main = str_replace('-',' ',$_ARRSEARCH[0]); //clienttype-glossaryname-promotype
$search_locations = isset($_ARRSEARCH[1]) ? str_replace('-',' ',$_ARRSEARCH[1]) : '';
if(!empty($_ARRSEARCH[0])){
	$searchtext = ucwords(str_replace('_',', ',$search_main));
}
if(!empty($_ARRSEARCH[1])){
	if(!empty($_ARRSEARCH[0])): $searchtext .= ' en '; else: $searchtext = 'Centros y Promos en '; endif;
	$searchtext .= ucwords(str_replace('_',', ',$search_locations));
}
$arrwordsmain = explode('_',$search_main);
$arrwordslocations = explode('_',$search_locations);
////////////// CLIENTTYPES //////////////
$_CLIENTTYPES->keywords = $arrwordsmain;
$_CLIENTTYPES->searchmixed = 1;
$arrtypes = array();
if(!empty($search_main)){
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
if(!empty($search_main)){
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
if($_STORES->search()){
	foreach($_STORES->data() as $idclient):
		$arridclients[] = $idclient->idclient;
	endforeach;
}
$totalresults = 12;
///print_r($_STORES->data());
////////////// PROMOS ///////////////////
////$_PROMOS->keywords = $search_main;
////$_PROMOS->status = '1:1';
////$_PROMOS->searchmixed = 1;
////$_PROMOS->sort = '';
////$_PROMOS->arrtypes = $arrtypes;
////$_PROMOS->arrpromotypes = $arrpromotypes;
////$_PROMOS->arridclients = $arridclients;
////$_PROMOS->get();
///////////// CLIENTS ///////////////////
$_CLIENTS->keywords = $search_main;
$_CLIENTS->sort = 'promocount';
$_CLIENTS->searchmixed = 1;
$_CLIENTS->searchpromos = true;
//$_CLIENTS->limit = '0,'.$totalresults;
$_CLIENTS->arrtypes = $arrtypes;
$_CLIENTS->arrglossary = $arrglossary;
$_CLIENTS->arridclients = $arridclients;
$_CLIENTS->get();
$_QCLIENTS = $_CLIENTS->data();
////////////// BLOG //////////////////////
//$_BLOG->limit = '0,'.$totalresults;
$_BLOG->keywords = $search_main;
$_BLOG->arrglossary = $arrglossary;
$_BLOG->get();