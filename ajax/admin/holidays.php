<?php 


$User = new User();
$Holidays = new Holidays();

if(!Input::check(Input::get('required'))) die(Responses::response('fail'));
if(!$User->logged() || $User->data()->idtype != 1) die(Responses::response('restricted'));

switch($_action):

	case 'add':
		$id = $Holidays->save();
		echo Responses::response('ok','',array('id'=>$id));
		break;

	case 'delete':
		$Holidays->delete(Input::get('id'));
		echo Responses::response('ok');
		break;


	default:
		echo Responses::response('fail');
		break;

endswitch;