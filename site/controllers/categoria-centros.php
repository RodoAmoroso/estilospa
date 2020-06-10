<?php 

$query = isset($_uri[1]) && !empty($_uri[1]) ? $_uri[1] : '-';
$location = isset($_uri[2]) && !empty($_uri[2]) ? $_uri[2] : '-';
$page = isset($_uri[3]) && !empty($_uri[3]) ? $_uri[3] : 1;

$search_main = trim(str_replace('-',' ',$query)); 
$search_locations = trim(str_replace('-',' ',$location));

$Stats->add_search_type($search_main);
$Stats->add_search_location($search_locations);

$arrwordsmain = (array) $search_main;
$arrwordslocations = (array) $search_locations;

//////////// CLIENTTYPES //////////
$ClientTypes->keywords = $arrwordsmain;
$ClientTypes->searchmixed = 1;
$arrtypes = array();
if(!empty($search_main)){
	if($ClientTypes->get()){
		foreach($ClientTypes->data() as $type):
			$arrtypes[] = $type->id;
		endforeach;
	}
}
//show_array($arrtypes);

//////////// GLOSSARY //////////
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
//show_array($arrglossary);

////////////// LOCATIONS ////////////////
$Stores->keywords = $arrwordslocations;
$Stores->searchmixed = 0;
$arridclients = array();
if(!empty($search_locations)){	
	if($Stores->search()){
		foreach($Stores->data() as $idclient):
			$arridclients[] = $idclient->idclient;
		endforeach;
	}
}
//show_array($arridclients);


$page_results = 24;

$Clients->keywords = empty($search_main) ? '' : $search_main;
$Clients->sort = 'promocount';
$Clients->searchmixed = 1;
$Clients->visible = 1;
$Clients->arrtypes = $arrtypes;
$Clients->arrglossary = $arrglossary;
$Clients->arridclients = $arridclients;

$Clients->get();
$total_results = $Clients->data() ? count($Clients->data()) : 0;


$Clients->limit = (($page*$page_results)-$page_results).','.$page_results;
$Clients->get();

///show_array($Clients->search);

$QClients = array();
if(!empty($query) || !empty($location)) $QClients = $Clients->data();

//show_array($Clients->search);