<?php

$User = new User();
$BlogCategories = new BlogCategories();

if(!Input::check(Input::get('required'))) die(Responses::response('fail'));
if(!$User->logged() || $User->data()->idtype != 1) die(Responses::response('restricted'));

switch($_action){


	case 'save':		
		$BlogCategories->save();		
		echo Responses::response('ok','',array('id'=>$BlogCategories->getLastId()));
		break;
	case 'get':
		$BlogCategories->get();
		echo Responses::response('ok','',array('results'=>$BlogCategories->data()));
		break;
	case 'find':
		$BlogCategories->find(Input::get('ID'));
		echo Responses::response('ok','',array('result'=>$BlogCategories->data()));
		break;
	case 'delete':
		$BlogCategories->delete();
		echo Responses::response('ok');
		break;
	case 'reorder':
		$BlogCategories->reorder();
		echo Responses::response('ok');
		break;


	default:
		echo Responses::response('fail');
		break;

}
