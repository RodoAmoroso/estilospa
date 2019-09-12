<?php 

$query = isset($_uri[1]) && !empty($_uri[1]) ? $_uri[1] : '-';
$location = isset($_uri[2]) && !empty($_uri[2]) ? $_uri[2] : '-';
$page = isset($_uri[3]) && !empty($_uri[3]) ? $_uri[3] : 1;
$_SEARCH = new Search();


$search_main = trim(str_replace('-',' ',$query)); 
$search_locations = trim(str_replace('-',' ',$location));

$Stats->add_search_word($search_main);
$Stats->add_search_location($search_locations);


$arrwordsmain = (array) $search_main;
$arrwordslocations = (array) $search_locations;

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
///print_r($arridclients);
///echo '<br />';
$page_results = 50;
////////////// PROMOS ///////////////////
$Promos->keywords = $search_main == ' ' ? '' : $search_main;
$Promos->status = '1:1';
$Promos->searchmixed = 1;
$Promos->isgift = true;
$Promos->sort = 'position';
$Promos->visible = true;
//$Promos->arrpromotypes = $arrpromotypes;
$Promos->arrglossary = $arrglossary;
$Promos->arridclients = $arridclients;
$Promos->get();
$total_results = $Promos->data() ? count($Promos->data()) : 0;

$Promos->limit = (($page*$page_results)-$page_results).','.$page_results;
$Promos->get();
///print_r($arridclients);
//show_array($Promos->search);

$MPConfig = new MPConfig();
$Stores = new Stores();