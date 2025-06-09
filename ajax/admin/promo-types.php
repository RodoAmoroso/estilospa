<?php

$User = new User();
$PromoTypes = new PromoTypes();

if(!Input::check(Input::get('required'))) die(Responses::response('fail'));
if(!$User->logged() || $User->data()->idtype != 1) die(Responses::response('restricted'));

switch($_action){

	case 'save':
		if(!Input::check(array('Name'))) die(Responses::response('fail'));
		$PromoTypes->save();
		echo Responses::response('ok');
		break;

	case 'get':
		$PromoTypes->get();
		echo Responses::response('ok','',array('results'=>$PromoTypes->data()));
		break;

	case 'find':
		$PromoTypes->find();
		echo Responses::response('ok','',array('result'=>$PromoTypes->data()));
		break;

	case 'delete':
		$PromoTypes->delete();
		echo Responses::response('ok');
		break;

	default:
		echo Responses::response('fail');
		break;

}