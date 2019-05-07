<?php 

require_once 'config.php';
require_once 'vendor/autoload.php';
///use MercadoPago\MercadoPago\SDK;


//MercadoPago\SDK::setAccessToken("APP_USR-7030611358224519-050401-40a4130219ec8743f65509dc8a65f78d-417751838");

MercadoPago\SDK::setClientId("7030611358224519");
MercadoPago\SDK::setClientSecret("5ziaNn6vMrN4FR1xodfDgfqvJT4RnLVN");

$preference = new MercadoPago\Preference();

$item = new MercadoPago\Item();
$item->title = "Heavy Duty Plastic Clock";
$item->quantity = 8;
$item->currency_id = "ARS";
$item->unit_price = 61.91;

$payer = new MercadoPago\Payer();
$payer->email = "test_user_19653727@testuser.com";

$preference->items = array($item);
$preference->payer = $payer;
$preference->marketplace_fee = 2.56;
$preference->notification_url = "https://www.estilospa.com/ipn.php";

$preference->back_urls = array(
	'success'=>'http://localhost/estilospa/pago-status/success',
	'failure'=>'http://localhost/estilospa/pago-status/failure',
	'pending'=>'http://localhost/estilospa/pago-status/pending'
);
$preference->external_reference = hash('sha256', uniqid());

$preference->save();
echo $preference->init_point;
show_array($preference);