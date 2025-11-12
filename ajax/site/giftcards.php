<?php

///use GuzzleHttp\Psr7\Response;

$User = new User;
$_userdata = $User->data();

$MPConfig = new MPConfig;

$GiftCardsUsersAssignments = new GiftCardsUsersAssignments;
$GiftCardsPurchases = new GiftCardsPurchases;

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

	case 'redeem-code':

		if(!$User->logged()) die(Responses::response('restricted'));

		/* die(Responses::response('ok','',[
			'template_thanks'=>Templates::template('giftcards/open-gift-thanks')
		])); */
		
		$assignment = $GiftCardsUsersAssignments->find_by_code(Input::get('code'));

		if($assignment){
			die(Responses::response('fail','El código ya ha sido canjeado'));
		}
		if(!$purchase = $GiftCardsPurchases->find_by_code(Input::get('code'))) die(Responses::response('fail','No se pudo obtener la información de compra del código'));

		if($purchase->payment_status!='approved') die(Responses::response('fail','El pago aún no ha sido aprobado'));

		$expiration = new DateTime;
		$expiration->modify('+'.$purchase->giftcard->expiration.' days');

		$GiftCardsUsersAssignments->save([
			'id'=>null,
			'purchase_id'=>$purchase->id,
			'user_id'=>$_userdata->id,
			//'from_user'=>
			//'to_user'=>
			//'comments'=>
			//'image'=>
			//'gallery_id'=>
			'is_gift'=>$purchase->user_id==$_userdata->id ? 0 : 1,
			'value'=>$purchase->price,
			'expiration'=>$expiration->format('Y-m-d')
		]);

		echo Responses::response('ok','',[
			'template_thanks'=>Templates::template('giftcards/open-gift-thanks')
		]);
		break;
		
	default:
		echo Responses::response('fail');
		break;

endswitch;