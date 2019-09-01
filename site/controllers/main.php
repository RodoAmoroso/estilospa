<?php

$User = new User();
if(Cookie::exists(Config::get('cookie/cookie_name')) && !Session::exists(Config::get('session/session_name'))){
	$hash = Cookie::get(Config::get('cookie/cookie_name'));
	$hashCheck = DB::getInstance()->get('sessions', array('hash','=',$hash));
	if($hashCheck->count()){
		$User = new User($hashCheck->first()->iduser);
		$User->login();
	}
}
$_userdata = null;
if($User->logged()){
	$User->update($User->data()->id,array('logged'=>date('Y-m-d H:i:s')));
	$_userdata = $User->data();
}

$dbprovinces = DB::getInstance()->get('provinces',array('id','!=',0));
foreach($dbprovinces->results() as $p){
	$Provinces[$p->id] = $p->name;
}


$Clients = new Clients();
$Clients->visible = 1;
$ClientTypes = new ClientTypes();
$Promos = new Promos();
$Stores = new Stores();
$Glossary = new Glossary();
$GlossaryGroups = new GlossaryGroups();
$Favs = new Favs();
$colorsequence = array('yellow-2','green-1','cyan-1','pink-1','aqua-2');

$MPConfig = new MPConfig();

$Stats = new Stats();

