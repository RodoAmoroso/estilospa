<?php

header("Content-Type: application/json; charset=utf-8", true);

$Questions = new Questions;
$User = new User;
$Mailing = new Mailing;

if(!Input::check(Input::get('required'))) die(Responses::response('fail'));
if(!$User->logged() || $User->data()->idtype != 1) die(Responses::response('restricted'));

switch($_action){


	case 'delete_question':
		$Questions->delete_question(Input::get('id'));
		echo Responses::response('ok');
		break;


	case 'delete_response':
		$Questions->delete_response(Input::get('id'));
		echo Responses::response('ok');
		break;


	case 'find-question':
		if(!$question = $Questions->get(Input::get('messageid'))) die(Responses::response('fail'));
		echo Responses::response('ok','',['question'=>$question]);
		break;

	case 'reply-question':

		$messageid = $Questions->add('questions_responses',array(
			'messageid'=>Input::get('messageid'),
			'userid'=>$User->data()->id,
			'message'=>strip_tags(Input::get('reply')),
			'added'=>date('Y-m-d H:i:s')
		));
		if(!$Mailing->response($messageid)) die(Responses::response('fail','No se pudo enviar el email al usuario.'));
		echo Responses::response('ok');
		break;


	default:
		echo Responses::response('fail');
		break;

}