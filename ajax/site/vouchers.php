<?php

require_once '../config.php';
header("Content-Type: application/json; charset=utf-8", true);

$Promos = new Promos();
$User = new User();
$Vouchers = new Vouchers();

if(!Input::check(Input::get('required'))) die(Responses::response('fail'));

switch($_action){

	case 'validate':
		if(!$User->logged()) die(Responses::response('require_login'));
		if(!Input::check(Input::get('required'))) die(Responses::response('required'));

		if(!$Vouchers->validate( Input::get('idpromo'),Input::get('code'), $User->data()->id )) die(Responses::response('fail',$Vouchers->errors()));

		echo Responses::response('ok','',array('result'=>$Vouchers->data()));
		break;

	case 'free':
		echo Responses::response('ok');
		break;

	default:
		echo Responses::response('fail');
		break;

}