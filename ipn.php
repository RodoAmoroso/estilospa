<?php
require_once 'config.php';
require_once 'mercadopago/mercadopago.php';
require 'ajax/templates-mail.php';
require 'ajax/phpmailer/PHPMailerAutoload.php';
error_reporting(1);

$mp = new MP("APP_USR-7300466898804487-070519-065286686bbe9e2c819c57c7094d11da__LD_LC__-263157583");
$_SALES = new Sales();
$_VOUCHERS = new Vouchers();
$_PROMOS = new Promos();
$_CLIENTS = new Clients();
$_STORES = new Stores();


if (!isset($_GET["id"], $_GET["topic"]) || !ctype_digit($_GET["id"])) {
	http_response_code(400);
	return;
}

// Get the payment and the corresponding merchant_order reported by the IPN.

switch($_GET["topic"]) {
	case 'payment':
		
		$payment_info = $mp->get('/v1/payments/'.$_GET["id"]);
		
		if($payment_info["status"] == 200) {

			
			/*echo '<pre>';
			print_r(!empty($payment_info['response']['order']));
			echo '</pre>';
			die();*/

			//$external_reference = $payment_info["response"]['external_reference']; #obtengo el hash tempsales, de acá saco idpromo, iduser, price, quantity, voucher (id o descuento ??)
			$hash = $payment_info["response"]['external_reference'];
			//$hash = '08c6578d8d450033563cb955cc9db572ff92fcdb64c3c0ff71fe4e51a65b771b';
			$collection_id = $payment_info["response"]['id'];
			$payment_type = $payment_info["response"]['payment_type_id'];
			$merchant_order_id = !empty($payment_info["response"]['order']) ? $payment_info["response"]['order']['id'] : '';
			$collection_status = $payment_info["response"]['status'];

			if($_SALES->findtemp($hash)){
				
				$idpromo = $_SALES->data()->idpromo;
				$iduser = $_SALES->data()->iduser;
				$quantity = $_SALES->data()->quantity;
				$price = $_SALES->data()->price;
				$idcode = $_SALES->data()->idcode;

				$arrfields = array(
					'iduser'=>$iduser,
					'idpromo'=>$idpromo,
					'collection_id'=>$collection_id,
					'collection_status'=>$collection_status,
					'preference_id'=>'',
					'external_reference'=>$hash,
					'payment_type'=>$payment_type,
					'merchant_order_id'=>$merchant_order_id,	
					'price'=>$price,
					'added'=>date('Y-m-d H:i:s'),
					'quantity'=>$quantity,
					'hash'=>$hash
				);
				$_SALES->save($arrfields);
				$saleid = $_SALES->getLastId();
				///////// VOUCHER ////////////
				if($idcode){
					if($_VOUCHERS->findcode($idcode)){
						$idvoucher = $_VOUCHERS->data()->idvoucher;
						if($_VOUCHERS->find($idvoucher)){
							$sqlvoucher = array(
								'idvoucher'=>$idvoucher,
								'idcode'=>$idcode,
								'iduser'=>$iduser,
								'idsale'=>$saleid,
								'ispercent'=>$_VOUCHERS->data()->ispercent,
								'value'=>$_VOUCHERS->data()->value,
								'added'=>date('Y-m-d H:i:s')
							);
							$_VOUCHERS->usage($sqlvoucher);
						}
					}
				}
				//////////////////////////////
				//$_SALES->deletetemp($hash);
				
			}


			if($_SALES->check($collection_id)){
				$saleid = $_SALES->data()->id;
				$_SALES->update($saleid,array(
					'collection_id'=>$collection_id,
					'collection_status'=>$collection_status,
					'payment_type'=>$payment_type,
					'modified'=>date('Y-m-d H:i:s')
				));
			}

			if(!$saleid) die(http_response_code(400));
			

			/*if(!$_USER->find($iduser)){
				http_response_code(400); 
				return;
			}*/
			/*if(!$_PROMOS->find(intval($idpromo))){
				http_response_code(400);
				return;
			}
			if(!$_CLIENTS->find($_PROMOS->data()->idclient)){
				http_response_code(400);
				return;
			}*/

			///header("Content-Type: application/json; charset=utf-8", true);
			$_SALES->find($saleid);
			$_salesdata = $_SALES->data();
			
			if($collection_status == 'approved'){
				$_PROMOS->discountAmount($idpromo,$quantity);
				$idpromo = $_salesdata->idpromo;
				$iduser = $_salesdata->iduser;
				$quantity = $_salesdata->quantity;
				$price = $_salesdata->price;
				$priceformat = number_format($price,2,',','.');
				
				include 'ipn-success.php';
				//echo 'success';
			}
			if($collection_status == 'pending'){
				include 'ipn-pending.php';
				//echo 'pending';
			}
			if($collection_status == 'rejected'){
				include 'ipn-rejected.php';
				//echo 'rejected';
			}

		}
		http_response_code($payment_info["status"]);		
		break;

	case 'merchant_order':
		try{
			$payment_info = $mp->get('/merchant_orders/'.$_GET["id"]);
		}catch(MercadoPagoException $e){
			//print_r($e->getMessage());
			http_response_code(400);
			return;
		}
		if($payment_info["status"] == 200) {
			/*echo '<pre>';
			print_r($payment_info);
			echo '</pre>';*/
		}
		http_response_code($payment_info["status"]);
		break;
	
	default:
		http_response_code(400);
		break;
}