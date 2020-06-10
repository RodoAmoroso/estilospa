<?php

header("Content-Type: application/json; charset=utf-8", true);

$User = new User();
$Vouchers = new Vouchers();

if(!Input::check(Input::get('required'))) die(Responses::response('fail'));
if(!$User->logged() || $User->data()->idtype != 1) die(Responses::response('restricted'));

switch($_action){


	case 'get':
		$Vouchers->status = Input::get('status');
		$Vouchers->idpromo = Input::get('idpromo');
		$Vouchers->keywords = Input::get('keywords');
		$Vouchers->get();
		echo Responses::response('ok','',array('results'=>$Vouchers->data()));
		break;

	case 'find':
		$Vouchers->find(Input::get('ID'));
		$vouchers = $Vouchers->data();
		$Vouchers->getcodes(Input::get('ID'));
		$codes = $Vouchers->data();
		$Vouchers->getpromos(Input::get('ID'));
		$promos = $Vouchers->data();

		echo Responses::response('ok','',array('result'=>$vouchers,'codes'=>$codes,'promos'=>$promos));
		break;

	case 'save':

		if(!Input::check(array('Name','Value','Codes'))) die(Responses::response('required'));

		$arrcodes = explode(',',Input::get('Codes'));
		if(count($arrcodes)==1 && !Input::get('ID')){
			if($Vouchers->findcode($arrcodes[0])){
				die(Responses::response('fail','El código ingresado ya está en uso. Elige otro.'));
			}
		}

		if(!$Vouchers->save()) die(Responses::response('fail'));
		echo Responses::response('ok');
		break;

	case 'delete':
		if(!$User->logged() && $User->data()->idtype != 1) die(Responses::response('required'));
		if(!$Vouchers->delete(Input::get('ID'))) die(Responses::response('fail'));
		echo Responses::response('ok');
		break;


	default:
		echo Responses::response('fail');
		break;

}