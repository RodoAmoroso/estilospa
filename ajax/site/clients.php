<?php

header("Content-Type: application/json; charset=utf-8", true);

$Clients = new Clients();
$Stores = new Stores();
$Stats = new Stats();

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

	case 'tracker':
		if(!$Clients->find(Input::get('clientid'))) die(Responses::response('fail'));

		$client = $Clients->data();
		$Stats->add_tracker($client->id,Input::get('event'));

		$Stores = new Stores();
		$client->store = false;
		if($Stores->get($client->id)){
			$client->store = $Stores->data()[0];
		}
		$redirect = $Stats->tracker_redirect($client,Input::get('event'));

		echo Responses::response('ok','',[
			'redirect'=>$redirect
		]);

		break;


	default:
		echo Responses::response('fail');
		break;


}