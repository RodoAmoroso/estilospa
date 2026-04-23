<?php

if(!$User->logged()) Redirect::to('login#'.ROOT.'usuario/mis-compras');

$sales = [];

$Sales = new Sales();
$Sales->filters = [
	'user'=>$_userdata->id
];
if($promo_sales = $Sales->get()){
	foreach($promo_sales as $p_sale){
		$sales[$p_sale->added_obj->format('YmdHis')] = (object) [
			'id'=>$p_sale->id,
			'hash'=>$p_sale->hash,
			'added'=>$p_sale->added,
			'type'=>'promo',
			'payment_status'=>$p_sale->payment_status,
			'total'=>$p_sale->price * $p_sale->quantity,
			'order_number'=>$p_sale->collection_id
		];
	}
}


$GiftCardsPurchases = new GiftCardsPurchases();
$GiftCardsPurchases->filters = [
	'user'=>$_userdata->id
];
if($giftcards_purchases = $GiftCardsPurchases->get()){
	foreach($giftcards_purchases as $g_sale){
		$sales[$g_sale->added_obj->format('YmdHis')] = (object) [
			'id'=>$g_sale->id,
			'hash'=>$g_sale->hash,
			'added'=>$g_sale->added,
			'type'=>'giftcard',
			'payment_status'=>$g_sale->payment_status,
			'total'=>$g_sale->price,
			'order_number'=>$g_sale->mp_payment_id
		];
	}
}
krsort($sales);
//show_array($sales);