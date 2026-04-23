<?php

///use GuzzleHttp\Psr7\Response;

$User = new User;
$_userdata = $User->data();

if(!$User->logged()) die(Responses::response('restricted'));

$MPConfig = new MPConfig;

$GiftCardsUsersAssignments = new GiftCardsUsersAssignments;
$GiftCardsPurchases = new GiftCardsPurchases;
$GiftCardsPersonalizations = new GiftCardsPersonalizations;


switch($_action):

	case 'get-mp-preference':

		if(!$preference = $MPConfig->get_giftcard_preference()) die(Responses::response('fail',$MPConfig->get_response()));

		echo Responses::response('ok','',[
			'preference'=>$preference,
			'public_key'=>$MPConfig->get_public_key()
		]);
		break;

	case 'checkout-mp':

		if(!$payment = $MPConfig->create_giftcard_payment()) die(Responses::response('fail',$MPConfig->get_response()));

		echo Responses::response('ok','',[
			'url_thanks'=>ROOT.'pago-giftcard-status/success/'.$payment->external_reference
		]);
		break;

	case 'redeem-code':

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
			'is_gift'=>$purchase->user_id==$_userdata->id ? 0 : 1,
			'value'=>$purchase->price,
			'expiration'=>$expiration->format('Y-m-d')
		]);

		echo Responses::response('ok','',[
			'template_thanks'=>Templates::template('giftcards/open-gift-thanks')
		]);
		break;

	case 'save-personalization':

		if(!$purchase = $GiftCardsPurchases->find(Input::get('giftcard_purchase_id'))) die(Responses::response('fail'));
		if($purchase->user_id != $_userdata->id) die(Responses::response('restricted'));
		///dd($purchase);
		
		if(Input::get('id')){
			if(!$giftcard_personalization = $GiftCardsPersonalizations->find(Input::get('id'))) die(Responses::response('fail'));
			if($giftcard_personalization->giftcard_purchase_id != $purchase->id) die(Responses::response('restricted'));
		}

		if(!$GiftCardsPersonalizations->save()) die(Responses::response('fail'));
		echo Responses::response('ok');
		break;


	case 'upimage':

		$upfile = new File($_FILES['file'],'giftcards/');
		$upfile->MoveFile(true,true);
		$file = $upfile->Resize([[720,720,'']], '', false);
		
		echo Responses::response('ok','',$file);
		break;

	case 'delete-image':
		
		$file_path = IMG.'giftcards/'.Input::get('filename','xss').'.'.Input::get('extension','xss');
		if(file_exists($file_path))unlink($file_path);

		echo Responses::response('ok','');
		break;
		
	default:
		echo Responses::response('fail');
		break;

endswitch;