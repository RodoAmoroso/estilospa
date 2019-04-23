<?php

require_once '../config.php';
header("Content-Type: application/json; charset=utf-8", true);

$Promos = new Promos();
$User = new User();
$Sales = new Sales();
$Mailing = new Mailing();

if(!Input::check(Input::get('required'))) die(Responses::response('fail'));

switch($_action){

	case 'getmplink':
		$idpromo = intval(Input::get('idpromo'));
		$amount = intval(Input::get('amount'));
		$idcode = intval(Input::get('idcode'));

		if(!$Promos->find($idpromo)) die(Responses::response('fail'));
		if(!$Promos->data()->statusstart || !$Promos->data()->statusfinish) die(Responses::response('fail','La promo ha finalizado'));
		if($Promos->data()->amount<$amount) die(Responses::response('fail','La cantidad indicada es mayor que la cantidad de promos disponibles'));
		
		$MPConfig = new MPConfig();
		if(!$MPConfig->find($Promos->data()->idclient)) die(Responses::response('fail'));
		if(!$User->logged()) die(Responses::response('require_login'));

		$Clients = new Clients();
		$Clients->find($Promos->data()->idclient);


		$mplink = '#';
		if(!$MPConfig->getmplink($Promos->data(),$User->data(),$Clients->data(),$amount,$idcode)) die(Responses::response('fail'));

		$mplink = $MPConfig->mplink()['response']['init_point'];
		echo Responses::response('ok','',array('link'=>$mplink,'hash'=>$MPConfig->hash()));
		break;

	case 'gift':

		if(!$User->logged()) die(Responses::response('require_login'));
		if(!filter_var(Input::get('email'),FILTER_VALIDATE_EMAIL)) die(Responses::response('invalid_email'));
		if(!Input::check(Input::get('required'))) die(Responses::response('required'));

		$arrfields = array(
			'iduser'=>$User->data()->id,
			'fromuser'=>Input::get('from'),
			'touser'=>Input::get('to'),
			'mail'=>Input::get('email'),
			'message'=>Input::get('message'),
			'idpromo'=>intval(Input::get('idpromo')),
			'hash'=>Input::get('hash'),
			'added'=>date('Y-m-d H:i:s')
		);
		$Sales->savegift($arrfields);
		echo Responses::response('ok');
		break;


	default:
		echo Responses::response('fail');
		break;


}