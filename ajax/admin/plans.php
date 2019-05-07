<?php

require_once '../config.php';
header("Content-Type: application/json; charset=utf-8", true);

$User = new User();
$Plans = new Plans();

if(!Input::check(Input::get('required'))) die(Responses::response('fail'));
if(!$User->logged() || $User->data()->idtype != 1) die(Responses::response('restricted'));

switch($_action){

	case 'save':
		$Plans->save();
		echo Responses::response('ok','',array('id'=>$Plans->getLastId()));		
		break;

	case 'get':
		$Plans->get();
		echo Responses::response('ok','',array('results'=>$Plans->data()));
		break;

	case 'delete':
		$Plans->delete();
		echo Responses::response('ok');
		break;

	case 'find':
		$Plans->find(Input::get('ID'));
		echo Responses::response('ok','',array('result'=>$Plans->data()));
		break;

	default:
		echo Responses::response('fail');
		break;

}