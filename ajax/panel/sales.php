<?php

$User = new User();
$Sales = new Sales();
$Mailing = new Mailing();


if(!Input::check(Input::get('required'))) die(Responses::response('fail'));
if(!$User->logged()) die(Responses::response('restricted'));
if($User->data()->idtype != 3 && $User->data()->idtype != 4) die(Responses::response('restricted'));

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
		$Sales->keywords = Input::get('User');
		$Sales->sale_voucher = Input::get('Voucher');


		if($User->data()->idtype == 4){
			if(empty(Input::get('OrderNumber')) && empty(Input::get('User')) && empty(Input::get('Voucher'))){
				die( Responses::response('ok','',array('results'=>null)) );
			}
		}

		$Sales->get();
		echo Responses::response('ok','',array('results'=>$Sales->data()));
		break;

	case 'setstatus':

		$Sales->setStatus();
		echo Responses::response('ok');
		break;


	case 'changeplan':
		if(!$Mailing->change_plan($User->data())) die(Responses::response('fail'));
		echo Responses::response('ok','Tu solicitud fue enviada con éxito! En breve nos comunicaremos con vos.');
		break;


	case 'evolution':

		$datetime = new DateTime();
		$this_month = $datetime->format('Y-m');
		$datetime->modify('-4 month');
		$last_month = $datetime->format('Y-m');

		$stats = $Sales->evolution($User->data()->idclient,4);

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