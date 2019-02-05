<?php

//$dbclientcount = $_DB->query("SELECT COUNT(*) total FROM spa_clients")->first();
///$dbtypes = $_DB->query("SELECT * FROM spa_clienttypes ORDER BY position ASC")->results();

$dbprovinces = DB::getInstance()->get('provinces',array('id','!=',0));
foreach($dbprovinces->results() as $p){
	$_PROVINCES[$p->id] = $p->name;
}

$_CLIENTS = new Clients();
$_CLIENTS->visible = 1;
$_CLIENTTYPES = new ClientTypes();
$_PROMOS = new Promos();
$_STORES = new Stores();
$_GLOSSARY = new Glossary();
$_GLOSSARYGROUPS = new GlossaryGroups();
$_FAVS = new Favs();
$colorsequence = array('yellow-2','green-1','cyan-1','pink-1','aqua-2');