<?php

require_once '../config.php';
header("Content-Type: application/json; charset=utf-8", true);

$Promos = new Promos();
$User = new User();
$Sales = new Sales();
$Mailing = new Mailing();

if(!Input::check(Input::get('required'))) die(Responses::response('required'));

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
		if(!$preference = $MPConfig->getmplink($Promos->data(),$User->data(),$Clients->data(),$amount,$idcode)) die(Responses::response('fail'));

		///$preference = $MPConfig->mplink()['response']['init_point'];
		///show_array($MPConfig->mplink()['response']);
		echo Responses::response('ok','',array('link'=>$preference->init_point,'hash'=>$MPConfig->hash()));
		break;

	case 'gift':

		if(!$User->logged()) die(Responses::response('require_login'));
		if(!filter_var(Input::get('email'),FILTER_VALIDATE_EMAIL)) die(Responses::response('invalid_email'));

		$from_user = $User->data()->id;

		
		if(!$User->find(Input::get('email'))){

			$hash = hash('sha256', uniqid());
			$password = rand(11111,99999);

			if(!$to_user = $User->create(
				array(
					'name'=>Input::get('to'),
					'mail'=>strtolower(Input::get('email')),
					'pass'=>password_hash($password,PASSWORD_DEFAULT),
					'created'=>date('Y-m-d H:i:s'),
					'hash'=>$hash,
					'active'=>0,
					'idtype'=>2
				)
			)) die(Responses::response('fail'));

		}else{
			$to_user = $User->data()->id;
		}

		$Sales->save_gift(array(
			'from_user'=>$from_user,
			'to_user'=>$to_user,
			'message'=>Input::get('message'),
			'promoid'=>intval(Input::get('idpromo')),
			'hash'=>Input::get('hash'),
			'added'=>date('Y-m-d H:i:s')
		));
		echo Responses::response('ok');
		break;


	default:
		echo Responses::response('fail');
		break;


}