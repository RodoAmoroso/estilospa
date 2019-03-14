<?php 

//$_ARRSEARCH = explode('_-_',$_SUBSECTION);

$query = isset($_uri[1]) && !empty($_uri[1]) ? $_uri[1] : '-';
$location = isset($_uri[2]) && !empty($_uri[2]) ? $_uri[2] : '-';
$page = isset($_uri[3]) && !empty($_uri[3]) ? $_uri[3] : 1;


//$searchtext = '';
$search_main = trim(str_replace('-',' ',$query)); //clienttype-glossaryname-promotype
//$search_main = empty($search_main) ? ' ' : $search_main;
$search_locations = trim(str_replace('-',' ',$location));

$_stats->add_search_word($search_main);
$_stats->add_search_location($search_locations);


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
/*$_CLIENTTYPES->keywords = $arrwordsmain;
$_CLIENTTYPES->searchmixed = 1;
$arrtypes = array();
if(!empty($search_main)){
	if($_CLIENTTYPES->get()){
		foreach($_CLIENTTYPES->data() as $type):
			$arrtypes[] = $type->id;
		endforeach;
	}
}*/
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
$_STORES->keywords = $arrwordslocations;
$_STORES->searchmixed = 0;
$arridclients = array();
if(!empty($search_locations)){	
	if($_STORES->search()){
		foreach($_STORES->data() as $store):
			$arridclients[] = $store->idclient;
		endforeach;
	}
}
//show_array(var_dump($arridclients));
//show_array(var_dump($arrwordslocations));
///echo '<br />';
$page_results = 50;
//echo $_page;
////////////// PROMOS ///////////////////
$_PROMOS->keywords = empty($search_main) ? '' : $search_main;
$_PROMOS->status = '1:1';
$_PROMOS->searchmixed = 1;
$_PROMOS->sort = 'position';
$_PROMOS->visible = true;
///$_PROMOS->arrtypes = $arrtypes;
///$_PROMOS->arrpromotypes = $arrpromotypes;
$_PROMOS->arrglossary = $arrglossary;
$_PROMOS->arridclients = $arridclients;
$_PROMOS->get();
$total_results = $_PROMOS->data() ? count($_PROMOS->data()) : 0;

$_PROMOS->limit = (($page*$page_results)-$page_results).','.$page_results;
$_PROMOS->get();

if(!$total_results){
	Redirect::to('categoria/'.$query.'/'.$location);
}
///show_array($_PROMOS->limit);

///////////// CLIENTS ///////////////////
/*$_CLIENTS->keywords = $search_main == ' ' ? '' : $search_main;
$_CLIENTS->sort = 'promocount';
$_CLIENTS->searchmixed = 0;
$_CLIENTS->visible = 1;
$_CLIENTS->limit = '0,'.$totalresults;
$_CLIENTS->arrtypes = $arrtypes;
$_CLIENTS->arrglossary = $arrglossary;
$_CLIENTS->arridclients = $arridclients;
$_CLIENTS->get();
$_QCLIENTS = array();
if(!empty($query) || !empty($location)) $_QCLIENTS = $_CLIENTS->data();

$_BLOG = new Blog();
$_BLOG->limit = '0,'.$totalresults;
$_BLOG->keywords = $search_main;
$_BLOG->arrglossary = $arrglossary;
if(!empty($query) || !empty($location)) $_BLOG->get();*/

$MPConfig = new MPConfig();
$_STORES = new Stores();


///show_array(var_dump($location));