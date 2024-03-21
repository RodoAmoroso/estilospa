<?php


$promoid = (int) $_subsection;

if(!$Promos->find( $promoid )) Redirect::to('404');
$promo = $Promos->data();
if(!$User->logged()) Redirect::to($promo->url,true);
if(!$promo->sale) Redirect::to($promo->url,true);


if(!$Clients->find( $promo->idclient )) Redirect::to('404');
$client = $Clients->data();

$Vouchers = new Vouchers;
$Vouchers->status = '1:1';
$has_voucher = $Vouchers->getpromo($Promos->data()->id);
$voucher = $Vouchers->data();

$Sales = new Sales;
$promo->client = $client;
$promo->user = $User->data();
$promo->has_voucher = $has_voucher;
$promo->voucher = $voucher;
$sale_temp = $Sales->init_temp($promo);
////echo_json($sale_temp);

/*if(Cookie::get('sale_hash')){
	$Sales->find_temp(Cookie::get('sale_hash'));
	$sale_temp = $Sales->data();
	if(!$has_voucher){
		$Sales->update_temp($sale_temp->id,['idcode'=>null]);
	}
}
*/
///echo_json($promo);


// $MP = new MPConfig;
// Input::set('promoid',960);
// Input::set('amount',1);
// Input::set('total',1000);
// $pref = $MP->create_preference();
//echo_json($pref->id);


$_arrjs[] = ['script'=>'https://sdk.mercadopago.com/js/v2'];