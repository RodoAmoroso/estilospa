<?php

$Questions = new Questions();
$Mailing = new Mailing();
$Promos = new Promos();
$User = new User();

if(!Input::check(Input::get('required'))) die(Responses::response('fail'));

switch($_action){


	case 'get':
		$Questions->filters = [[Input::get('type')=>Input::get('rowid')]];
		$Questions->limit = intval(Input::get('limit'));
		$Questions->limit_responses = intval(Input::get('limit_responses'));
		$Questions->page = intval(Input::get('page'));
		$results = $Questions->get();
		$total = $Questions->get_total(Input::get('type'),Input::get('rowid'));
		echo Responses::response('ok','',array('results'=>$results,'total'=>$total));
		break;


	case 'add':

		if(!$User->logged()) die(Responses::response('require_login'));

		/*if(!$User->logged()){
			if(!$User->find(Input::get('email'))){

				if(!filter_var(Input::get('email'),FILTER_VALIDATE_EMAIL)) die(Responses::response('invalid_email'));

				$hash = hash('sha256', uniqid());
				$password = rand(11111,99999);

				if(!$idu = $User->create(
					array(
						'name'=>Input::get('name'),
						'mail'=>strtolower(Input::get('email')),
						'pass'=>password_hash($password,PASSWORD_DEFAULT),
						'created'=>date('Y-m-d H:i:s'),
						'hash'=>$hash,
						'active'=>0,
						'idtype'=>2
					)
				)) die(Responses::response('fail'));

				$userdata = new stdClass();
				$userdata->id = $idu;
				$userdata->name = Input::get('name');
				$userdata->mail = Input::get('email');
				$userdata->hash = $hash;
				if(!$Mailing->register($userdata)) die(Responses::response('fail','No se pudo enviar el email'));

			}else{
				$idu = $User->data()->id;
			}
		}*/
		$idu = $User->data()->id;


		$User->update($idu, array(
			'phone'=>Input::get('phone')
		));



		$questionid = $Questions->add('questions',array(
			'type'=>Input::get('type','xss'),
			'rowid'=>Input::get('rowid','int'),
			'message'=>Input::get('message','xss'),
			'userid'=>$idu,
			'added'=>date('Y-m-d H:i:s')
		));

		if(!$Mailing->question($questionid)) die(Responses::response('fail'));
		echo Responses::response('ok');
		break;

	case 'response':

		if(!$User->logged()) die(Responses::response('require_login'));
		if(!Input::check(Input::get('required'))) die(Responses::response('restricted'));

		$values = [
			'messageid'=>Input::get('messageid'),
			'userid'=>$User->data()->id,
			'message'=>strip_tags(Input::get('message')),
			'added'=>date('Y-m-d H:i:s'),
			'approved'=>0
		];
		$questionid = $Questions->add('questions_responses',$values);
		//if(!$Mailing->response($questionid)) die(Responses::response('fail'));
		echo Responses::response('ok');
		break;

	default:
		echo Responses::response('fail');
		break;

}