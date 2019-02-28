<?php 

require 'config.php';


$_clients = new Clients();

$_clients->get();
$clients = $_clients->data();

$_db = DB::getInstance();


// CLIENTS-GLOSSARY & CLIENTS-TYPES 
if($clients){
	foreach ($clients as $key => $client) {
		$glossary = explode(',',$client->glossary);
		$arr_glossary = array();
		foreach($glossary as $gl){
			$arr_glossary[] = array($client->id,$gl);
		}

		$types = explode(',',$client->types);
		$arr_types = array();
		foreach($types as $ty){
			$arr_types[] = array($client->id,$ty);
		}
		///show_array($arr);
		//$_db->insertmultiple('clients_glossary',array('clientid','glossaryid'),$arr_glossary);
		//$_db->insertmultiple('clients_types',array('clientid','typeid'),$arr_types);
	}
}

