<?php

require_once '../config.php';
header("Content-Type: application/json; charset=utf-8", true);

$User = new User();
$Sales = new Sales();

if(!Input::check(Input::get('required'))) die(Responses::response('fail'));
if(!$User->logged() || $User->data()->idtype != 1) die(Responses::response('restricted'));

switch($_action){

	case 'get':
		if(!empty(Input::get('From')) && !empty(Input::get('To'))){			
			$from = explode('/',Input::get('From'));
			$to = explode('/',Input::get('To'));
			$Sales->range = true;
			$Sales->from = $from[2].'-'.$from[1].'-'.$from[0].' 00:00:00';
			$Sales->to = $to[2].'-'.$to[1].'-'.$to[0].' 23:59:59';
		}
		$Sales->ordernumber = Input::get('OrderNumber');
		$Sales->idclient = $User->data()->idclient;
		/*if($User->logged() && $User->data()->idtype==3){
		}*/
		if(Input::get('IDClient')){
			$Sales->idclient = Input::get('IDClient');
		}
		$Sales->get();
		echo Responses::response('ok','',array('results'=>$Sales->data()));
		break;

	case 'setstatus':

		$Sales->setStatus();
		echo Responses::response('ok');
		break;

	case 'evolution':

		$datetime = new DateTime();
		$this_month = $datetime->format('Y-m');
		$datetime->modify('-6 month');
		$last_month = $datetime->format('Y-m');

		$stats = $Sales->evolution();

		echo Responses::response('ok','',array(
			'this_month'=>$this_month,
			'last_month'=>$last_month,
			'stats'=>$stats
		));
		break;

	default:
		echo Responses::response('fail');
		break;

}