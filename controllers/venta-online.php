<?php 

$query = isset($_uri[1]) && !empty($_uri[1]) ? $_uri[1] : '-';
$location = isset($_uri[2]) && !empty($_uri[2]) ? $_uri[2] : '-';
$page = isset($_uri[3]) && !empty($_uri[3]) ? $_uri[3] : 1;
$_SEARCH = new Search();


$search_main = trim(str_replace('-',' ',$query)); 
$search_locations = trim(str_replace('-',' ',$location));

$_stats->add_search_word($search_main);
$_stats->add_search_location($search_locations);


$arrwordsmain = (array) $search_main;
$arrwordslocations = (array) $search_locations;

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
$page_results = 50;
////////////// PROMOS ///////////////////
$_PROMOS->keywords = $search_main == ' ' ? '' : $search_main;
$_PROMOS->status = '1:1';
$_PROMOS->searchmixed = 1;
$_PROMOS->issale = true;
$_PROMOS->sort = 'position';
$_PROMOS->visible = true;
//$_PROMOS->arrpromotypes = $arrpromotypes;
$_PROMOS->arrglossary = $arrglossary;
$_PROMOS->arridclients = $arridclients;
$_PROMOS->get();
$total_results = $_PROMOS->data() ? count($_PROMOS->data()) : 0;

$_PROMOS->limit = (($page*$page_results)-$page_results).','.$page_results;
$_PROMOS->get();
///print_r($arridclients);
//show_array($_PROMOS->search);

$MPConfig = new MPConfig();
$_STORES = new Stores();