<?php

require_once '../config.php';
header("Content-Type: application/json; charset=utf-8", true);

$User = new User();
$Stores = new Stores();

if(!Input::check(Input::get('required'))) die(Responses::response('fail'));
if(!$User->logged() || $User->data()->idtype != 1) die(Responses::response('restricted'));

switch($_action){

	case 'get':		
		$Stores->get(Input::get('IDC'));
		echo Responses::response('ok','',array('results'=>$Stores->data()));
		break;

	case 'find':		
		$Stores->find(Input::get('IDS'));
		$today = $Stores->scheduleToday($Stores->data()->schedules);
		$schedules = $Stores->schedulesList($Stores->data()->schedules);
		echo Responses::response('ok','',array('result'=>$Stores->data(),'schedules'=>$schedules, 'today'=>$today));
		break;

	case 'save':
		$Stores->save(Input::get('IDClient'));
		echo Responses::response('ok','',array('id'=>$Stores->getLastId()));
		break;

	case 'reorder':
		$Stores->reorder();
		echo Responses::response('ok');
		break;

	case 'delete':
		$Stores->delete(Input::get('IDS'));
		echo Responses::response('ok');
		break;


	default:
		echo Responses::response('fail');
		break;

}