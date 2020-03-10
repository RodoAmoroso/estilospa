<?php

header("Content-Type: application/json; charset=utf-8", true);

$Clients = new Clients();
$Stores = new Stores();

if(!Input::check(Input::get('required'))) die(Responses::response('fail'));

switch($_action){


	case 'findstore':
		$Stores->find(Input::get('idstore'));
		$today = $Stores->scheduleToday($Stores->data()->schedules);
		$schedules = $Stores->schedulesList($Stores->data()->schedules);
		echo Responses::response('ok','',array(
			'result'=>$Stores->data(),
			'today'=>$today,
			'schedules'=>$schedules
		));
		break;


	default:
		echo Responses::response('fail');
		break;


}