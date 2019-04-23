<?php

require_once '../config.php';
header("Content-Type: application/json; charset=utf-8", true);

$Questions = new Questions();
$Mailing = new Mailing();
$Promos = new Promos();
$User = new User();

if(!Input::check(Input::get('required'))) die(Responses::response('fail'));

switch($_action){

	case 'getbypromo':

		$Questions->filters = [['promo'=>Input::get('rowid')]];
		$Questions->limit = intval(Input::get('limit'));
		$Questions->page = intval(Input::get('page'));
		$results = $Questions->get();
		$total = $Questions->get_total('promo',Input::get('rowid'));
		echo Responses::response('ok','',array('results'=>$results,'total'=>$total));

		break;

	case 'add':

		if(!$User->logged()) die(Responses::response('require_login'));
		if(!Input::check(Input::get('required'))) die(Responses::response('restricted'));
		$questionid = $Questions->add('questions',array(
			'table'=>Input::get('table'),
			'rowid'=>Input::get('rowid'),
			'message'=>Input::get('message'),
			'userid'=>$User->data()->id,
			'added'=>date('Y-m-d H:i:s')
		));
		if(!$Mailing->question_promo($questionid)) die(Responses::response('fail'));
		echo Responses::response('ok');
		break;

	case 'response':

		if(!$User->logged()) die(Responses::response('require_login'));
		if(!Input::check(Input::get('required'))) die(Responses::response('restricted'));
		$questionid = $Questions->add('questions_responses',array(
			'messageid'=>Input::get('messageid'),
			'userid'=>$User->data()->id,
			'message'=>Input::get('message'),
			'added'=>date('Y-m-d H:i:s')
		));
		if(!$Mailing->response_promo($questionid)) die(Responses::response('fail'));
		echo Responses::response('ok');
		break;

}