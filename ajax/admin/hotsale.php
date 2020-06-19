<?php

header("Content-Type: application/json; charset=utf-8", true);

$HotSale = new HotSale;
$User = new User;

if(!$User->logged() || $User->data()->idtype != 1) die(Responses::response('restricted'));

switch($_action){


	case 'delete':
		$HotSale->delete(Input::get('id'));
		echo Responses::response('ok');
		break;


	default:
		echo Responses::response('fail');
		break;

}