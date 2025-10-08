<?php

$User = new User;
$_userdata = $User->data();
$Promos = new Promos;
$Sales = new Sales;
$Mailing = new Mailing;

$MPConfig = new MPConfig;

if(!Input::check(Input::get('required'))) die(Responses::response('required'));

switch($_action){

	case 'getmplinkx':

		$idpromo = intval(Input::get('idpromo'));
		$amount = intval(Input::get('amount'));
		$idcode = intval(Input::get('idcode'));

		$reservationid = Input::get('_vars');



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
		echo Responses::response('ok','',array(
			'link'=>$preference->init_point,
			'hash'=>$MPConfig->hash()
		));
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



	case 'get-mp-preference':

		if(!$User->logged()) die(Responses::response('restricted'));
		if(!$preference = $MPConfig->get_preference()) die(Responses::response('fail'));

		echo Responses::response('ok','',[
			'preference'=>$preference
		]);
		break;
	case 'checkout-mp':
		if(!$User->logged()) die(Responses::response('restricted'));
		if(!$response = $MPConfig->create_payment()) die(Responses::response('fail',$MPConfig->get_response()));
		echo Responses::response('ok','',['response'=>$response]);
		break;

	case 'update-sale':

		if(!$Sales->find_temp(Cookie::get('sale_hash'))) die(Responses::response('fail'));
		$Sales->update_temp($Sales->data()->id,[
			'quantity'=>Input::get('quantity','int'),
			'modified'=>date('Y-m-d H:i:s')
		]);
		echo Responses::response('ok');
		break;

	case 'update-user':
		if(!$User->logged()) die(Responses::response('restricted'));
		if(!Input::check(Input::get('required'))) die(Responses::response('fail','Todos los campos son obligatorios'));

		$User->update($User->data()->id,[
			'name'=>Input::get('firstname'),
			'lastname'=>Input::get('lastname'),
			'phone'=>Input::get('phone')
		]);

		echo Responses::response('ok','Los datos fueron guardados correctamente!');
		break;


	default:
		echo Responses::response('fail');
		break;


}