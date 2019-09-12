<?php
require_once 'config.php';
require_once 'vendor/autoload.php';


if (!isset($_GET["id"], $_GET["topic"]) || !ctype_digit($_GET["id"])) {
	http_response_code(400);
	return false;
}

$MPConfig = new MPConfig();

MercadoPago\SDK::setAccessToken($MPConfig->access_token);

$payment_info = null;

switch($_GET["topic"]) {
	case 'payment':
		try {
			//$payment_info = $mp->get('/v1/payments/'.$_GET["id"]);
			$payment_info = MercadoPago\Payment::find_by_id($_GET["id"]);
		} catch (Exception $e) {
			http_response_code(400);
			return;
		}	
	
		break;

	case 'merchant_order':
		try{
			///$merchant_order = $mp->get('/merchant_orders/'.$_GET["id"]);
			$merchant_order = MercadoPago\MerchantOrder::find_by_id($_GET["id"]);
			//$hash = $merchant_order['response']['external_reference'];
			if(!empty($merchant_order->payments)){
				//$payment_info = $merchant_order->payments[0];			
				$payment_info = MercadoPago\Payment::find_by_id($merchant_order->payments);	
			}else{
				http_response_code(400);
			}
		}catch(MercadoPagoException $e){
			//show_array($e->getMessage());
			http_response_code(400);
			return false;
		}		
		//http_response_code($payment_info["status"]);
		break;
	
	default:
		http_response_code(400);
		return false;
		break;
}


show_array($payment_info);