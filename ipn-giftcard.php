<?php

require 'config.php';


$response = file_get_contents('php://input');
$DB->insert('mp_webhooks',[
	'body'=>$response
]);
$response = json_decode($response);

$MPConfig = new MPConfig;

if(!property_exists($response, 'action')){
	echo 'Acción no requerida.';
	http_response_code(200);
	exit;
}

$payment = false;

switch($response->action):

	case 'payment.created':

		if(!$payment = $MPConfig->find_payment($response->data->id)){
			http_response_code(404);
			echo 'Pago no encontrado.';
			exit;
		}

		break;

	default:
		echo 'Acción no requerida.';
		break;

endswitch;


$GiftCardsPurchases = new GiftCardsPurchases;
$purchase = $GiftCardsPurchases->find_by_hash($payment->external_reference);
//dd($payment->toArray());

if($purchase){

	$fees = 0;
	if($payment->fee_details){
		foreach($payment->fee_details as $fee_detail){
			$fees += $fee_detail->amount;
		}
	}

	$GiftCardsPurchases->save([
		'id'=>$purchase->id,
		'payment_status'=>$payment->status,
		'mp_fee'=>$fees,
		'mp_payment_id'=>$payment->id,
		'payment_type'=>$payment->payment_type_id.': '.$payment->payment_method_id
	]);

	/// Send Email
	if($payment->status=='approved' && $purchase->payment_status != 'approved'){
		$Mailing = new Mailing;
		$Mailing->giftcard_purchase($purchase);

	}

	http_response_code(200);
	exit;
}

http_response_code(404);