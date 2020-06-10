<?php

header("Content-Type: application/json; charset=utf-8", true);

$Questions = new Questions();
$User = new User();

if(!Input::check(Input::get('required'))) die(Responses::response('fail'));
if(!$User->logged() || $User->data()->idtype != 1) die(Responses::response('restricted'));

switch($_action){


	case 'delete_question':
		$Questions->delete_question(Input::get('id'));
		echo Responses::response('ok');
		break;


	case 'delete_response':
		$Questions->delete_response(Input::get('id'));
		echo Responses::response('ok');
		break;


	default:
		echo Responses::response('fail');
		break;

}