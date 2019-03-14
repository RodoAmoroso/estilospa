<?php 

$query = isset($_uri[1]) && !empty($_uri[1]) ? $_uri[1] : '-';
$location = isset($_uri[2]) && !empty($_uri[2]) ? $_uri[2] : '-';
$page = isset($_uri[3]) && !empty($_uri[3]) ? $_uri[3] : 1;

$search_main = trim(str_replace('-',' ',$query)); 
$search_locations = trim(str_replace('-',' ',$location));

$_stats->add_search_type($search_main);
$_stats->add_search_location($search_locations);

$arrwordsmain = (array) $search_main;
$arrwordslocations = (array) $search_locations;

//////////// CLIENTTYPES //////////
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
//show_array($arrtypes);

//////////// GLOSSARY //////////
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
//show_array($arrglossary);

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
//show_array($arridclients);


$page_results = 24;

$_CLIENTS->keywords = empty($search_main) ? '' : $search_main;
$_CLIENTS->sort = 'promocount';
$_CLIENTS->searchmixed = 1;
$_CLIENTS->visible = 1;
$_CLIENTS->arrtypes = $arrtypes;
$_CLIENTS->arrglossary = $arrglossary;
$_CLIENTS->arridclients = $arridclients;

$_CLIENTS->get();
$total_results = $_CLIENTS->data() ? count($_CLIENTS->data()) : 0;


$_CLIENTS->limit = (($page*$page_results)-$page_results).','.$page_results;
$_CLIENTS->get();

///show_array($_CLIENTS->search);

$_QCLIENTS = array();
if(!empty($query) || !empty($location)) $_QCLIENTS = $_CLIENTS->data();

//show_array($_CLIENTS->search);