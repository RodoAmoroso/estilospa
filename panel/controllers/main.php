<?php

/*$User = new User();
if(Cookie::exists(Config::get('cookie/cookie_name')) && !Session::exists(Config::get('session/session_name'))){
	$hash = Cookie::get(Config::get('cookie/cookie_name'));
	$hashCheck = DB::getInstance()->get('sessions', array('hash','=',$hash));
	if($hashCheck->count()){
		$User = new User($hashCheck->first()->iduser);
		$User->login();
	}
}*/
require PATH.'site/controllers/main.php';

if(!$User->logged()) Redirect::to('login#panel');
if($_userdata->idtype != 3 && $_userdata->idtype != 4) Redirect::to('restricted');

$arrAdminMenu = [
	['name'=>'Inicio','permalink'=>''],
	['name'=>'Mis Ventas','permalink'=>'mi-cuenta']
];
if($_userdata->idtype==3){
	$arrAdminMenu = array_merge($arrAdminMenu,[
		['name'=>'Mi Centro','permalink'=>'mi-centro'],
		['name'=>'Experiencias','permalink'=>'promos'],
		['name'=>'Reservas','permalink'=>'reservas'],
		['name'=>'Calendario','permalink'=>'calendario'],
		['name'=>'Preguntas','permalink'=>'preguntas'],
		['name'=>'Vinculación con Mercado Pago','permalink'=>'mp']
	]);
}

////$_userdata = $User->data();

///show_array($_userdata);


$Clients = new Clients;
$Clients->find($_userdata->idclient);
if(!$client = $Clients->data()) Redirect::to('restricted');
