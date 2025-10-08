<?php

use GuzzleHttp\Psr7\Response;

$User = new User;
$_userdata = $User->data();
$MPConfig = new MPConfig;

switch($_action):

	case 'get-mp-preference':

		if(!$User->logged()) die(Responses::response('restricted'));
		if(!$preference = $MPConfig->get_giftcard_preference()) die(Responses::response('fail',$MPConfig->get_response()));

		echo Responses::response('ok','',[
			'preference'=>$preference,
			'public_key'=>$MPConfig->get_public_key()
		]);
		break;

	case 'checkout-mp':

		if(!$User->logged()) die(Responses::response('restricted'));
		if(!$payment = $MPConfig->create_giftcard_payment()) die(Responses::response('fail',$MPConfig->get_response()));

		echo Responses::response('ok','',[
			'url_thanks'=>ROOT.'pago-giftcard-status/success/'.$payment->external_reference
		]);
		break;
		
	default:
		echo Responses::response('fail');
		break;

endswitch;