<?php

require_once '../config.php';
header("Content-Type: application/json; charset=utf-8", true);

$Subscribers = new Subscribers();
$Mailing = new Mailing();

if(!Input::check(Input::get('required'))) die(Responses::response('fail'));

switch($_action){

	case 'newsletter':
		if(!filter_var(Input::get('email'),FILTER_VALIDATE_EMAIL)) die(Responses::response('invalid_email'));

		if(!$Subscribers->verify(Input::get('email'))) die(Responses::response('subscriber_exists'));
		if(!$Subscribers->add(Input::get('email'))) die(Responses::response('fail'));

		echo Responses::response('ok','¡Gracias! Te subscribiste a nuestro Newsletter donde recibirás las mejores ofertas y promociones.');
		break;

	case 'publish':

		if(!filter_var(Input::get('email'),FILTER_VALIDATE_EMAIL)) die(Responses::response('invalid_email'));
		
		$user = new stdClass();
		$user->email = Input::get('email');
		$user->fullname = Input::get('fullname');
		$user->web = Input::get('web');
		$user->phone = Input::get('phone');
		$user->company = Input::get('company');
		$user->knowus = Input::get('knowus');
		$user->message = Input::get('message');

		if(!$Mailing->publish($user)) die(Responses::response('fail'));
		echo Responses::response('ok','El mensaje fue enviado correctamente! En breve nos comunicaremos con vos.');
		break;

	case 'contact':

		if(!filter_var(Input::get('email'),FILTER_VALIDATE_EMAIL)) die(Responses::response('invalid_email'));

		$user = new stdClass();
		$user->email = Input::get('email');
		$user->fullname = Input::get('fullname');
		$user->phone = Input::get('phone');
		$user->subject = Input::get('subject');
		$user->message = Input::get('message');

		if(!$Mailing->contact($user)) die(Responses::response('fail'));
		echo Responses::response('ok','El mensaje fue enviado correctamente! En breve nos comunicaremos con vos.');

		break;

	default:
		echo Responses::response('fail');
		break;

}