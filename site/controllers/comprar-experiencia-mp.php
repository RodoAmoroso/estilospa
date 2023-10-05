<?php


$promoid = (int) $_subsection;

if(!$Promos->find( $promoid )) Redirect::to('404');
$promo = $Promos->data();
if(!$User->logged()) Redirect::to($promo->url,true);


if(!$Clients->find( $promo->idclient )) Redirect::to('404');
$client = $Clients->data();

$Vouchers = new Vouchers;
$Vouchers->status = '1:1';
$has_voucher = $Vouchers->getpromo($Promos->data()->id);
$voucher = $Vouchers->data();
///show_array($promo);



// $MP = new MPConfig;
// Input::set('promoid',960);
// Input::set('amount',1);
// Input::set('total',1000);
// $pref = $MP->create_preference();
//echo_json($pref->id);


$_arrjs[] = [
	'script'=>'https://sdk.mercadopago.com/js/v2'
];