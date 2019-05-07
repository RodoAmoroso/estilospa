<?php

require_once '../config.php';
header("Content-Type: application/json; charset=utf-8", true);

$Search = new Search();
$Search->keywords = Input::get('keywords');
$Search->searchmixed = Input::get('search_mixed');
$Search->limit = '0,10';

if(!Input::check(Input::get('required'))) die(Responses::response('fail'));

switch($_action){

	case 'locations':
		$Search->locations();
		echo Responses::response('ok','',array('results'=>$Search->data()));
		break;

	case 'main':
		$Search->main();
		echo Responses::response('ok','',array('results'=>$Search->data()));
		break;

	default:
		echo Responses::response('fail');
		break;

}