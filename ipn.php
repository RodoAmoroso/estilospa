<?php
require_once 'config.php';
//require_once 'mercadopago/mercadopago.php';
require_once 'vendor/autoload.php';
///require 'ajax/templates-mail.php';
///require 'ajax/phpmailer/PHPMailerAutoload.php';
///error_reporting(1);

if (!isset($_GET["id"], $_GET["topic"]) || !ctype_digit($_GET["id"])) {
	http_response_code(400);
	return false;
}


$Sales = new Sales();
$Vouchers = new Vouchers();
$Promos = new Promos();
$Clients = new Clients();
$Stores = new Stores();
$Mailing = new Mailing();
$MPConfig = new MPConfig();

if(!$access_token = $MPConfig->get_access_token($_GET["idclient"])){

	http_response_code(400);
	return false;
}
//MercadoPago\SDK::setClientId($MPConfig->app_id);
//MercadoPago\SDK::setClientSecret($MPConfig->secret_key);
MercadoPago\SDK::setAccessToken($access_token);
//$mp = new MP($access_token); ///token del seller
//$mp = new MP("APP_USR-7300466898804487-070519-065286686bbe9e2c819c57c7094d11da__LD_LC__-263157583");

// Get the payment and the corresponding merchant_order reported by the IPN.

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
			if(is_object($merchant_order->payments[0])){
				//$payment_info = $merchant_order->payments[0];			
				$payment_info = MercadoPago\Payment::find_by_id($merchant_order->payments[0]->id);	
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


$hash = $payment_info->external_reference;

$collection_id = $payment_info->id;
$payment_type = $payment_info->payment_type_id;
$merchant_order_id = !empty($payment_info->order) ? $payment_info->order->id : '';
$collection_status = $payment_info->status;


$fees = $MPConfig->get_fees($payment_info->fee_details);

include 'payment-process.php';
http_response_code(200);