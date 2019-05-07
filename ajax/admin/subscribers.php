<?php

require_once '../config.php';
header("Content-Type: application/json; charset=utf-8", true);

$User = new User();
$Subscribers = new Subscribers();

if(!Input::check(Input::get('required'))) die(Responses::response('fail'));
if(!$User->logged() || $User->data()->idtype != 1) die(Responses::response('restricted'));

switch($_action){


	case 'get':
		$Subscribers->limit = '0,500';
		$Subscribers->keywords = Input::get('keywords');
		$data = $Subscribers->get();
		echo Responses::response('ok','',array('results'=>$data));
		break;

	case 'delete':
		if(!$Subscribers->delete(Input::get('id'))) die(Responses::response('fail'));
		echo Responses::response('ok');
		break;


	default:
		echo Responses::response('fail');
		break;

}