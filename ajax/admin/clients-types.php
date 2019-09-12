<?php

header("Content-Type: application/json; charset=utf-8", true);

$User = new User();
$ClientTypes = new ClientTypes();

if(!Input::check(Input::get('required'))) die(Responses::response('fail'));
if(!$User->logged() || $User->data()->idtype != 1) die(Responses::response('restricted'));

switch($_action){

	case 'save':
		$ClientTypes->save();
		echo Responses::response('ok','',array('id'=>$ClientTypes->getLastId()));
		break;

	case 'get':
		$ClientTypes->get();
		echo Responses::response('ok','',array('results'=>$ClientTypes->data()));
		break;

	case 'delete':
		$ClientTypes->delete();
		echo Responses::response('ok');
		break;

	case 'find':
		$ClientTypes->find(Input::get('ID'));
		echo Responses::response('ok','',array('result'=>$ClientTypes->data()));
		break;

	case 'reorder':
		$ClientTypes->reorder();
		echo Responses::response('ok');
		break;

	default:
		echo Responses::response('fail');
		break;

}