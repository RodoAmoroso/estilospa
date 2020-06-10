<?php

header("Content-Type: application/json; charset=utf-8", true);

$Subscribers = new Subscribers();
$Mailing = new Mailing();

if(!Input::check(Input::get('required'))) die(Responses::response('fail'));

$secret = '6Lcyu6IZAAAAAMLvjK1gZf6HpHE09qPrM9qlLCn7';


switch($_action){

	case 'newsletter':
		if(!filter_var(Input::get('email'),FILTER_VALIDATE_EMAIL)) die(Responses::response('invalid_email'));

		if(!$Subscribers->verify(Input::get('email'))) die(Responses::response('subscriber_exists'));
		if(!$Subscribers->add(Input::get('email'))) die(Responses::response('fail'));

		echo Responses::response('ok','¡Gracias! Te subscribiste a nuestro Newsletter donde recibirás las mejores ofertas y promociones.');
		break;

	case 'publish':

		if(!filter_var(Input::get('email'),FILTER_VALIDATE_EMAIL)) die(Responses::response('invalid_email'));

		if(!$Mailing->publish()) die(Responses::response('fail'));
		echo Responses::response('ok','El mensaje fue enviado correctamente! En breve nos comunicaremos con vos.');
		break;

	case 'contact':

		if(!filter_var(Input::get('email'),FILTER_VALIDATE_EMAIL)) die(Responses::response('invalid_email'));

		$captcha_response = curl_post('https://www.google.com/recaptcha/api/siteverify',array(
			'secret'=>$secret,
			'response'=>Input::get('g-recaptcha-response'),
			'remoteip'=>IPUSER
		));

		if(!$captcha_response->success) die(Responses::response('fail','El CAPTCHA no ha sido verificado'));

		if(!$Mailing->contact()) die(Responses::response('fail'));
		echo Responses::response('ok','El mensaje fue enviado correctamente! En breve nos comunicaremos con vos.');

		break;

	default:
		echo Responses::response('fail');
		break;

}