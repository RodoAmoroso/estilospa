<?php

////$hash = $payment_info["response"]['external_reference'];

if($Sales->check_hash($hash)){

	$saleid = $Sales->data()->id;
	if($Sales->data()->payment_status!=$payment_status){
		$Sales->notified($Sales->data()->id,0);
	}

	$Sales->update($saleid,array(
		'collection_id'=>$collection_id,
		'payment_status'=>$payment_status,
		'payment_type'=>$payment_type,
		'application_fee'=>$fees->application_fee,
		'mercadopago_fee'=>$fees->mercadopago_fee,
		'modified'=>date('Y-m-d H:i:s')
	));

}else{

	//echo_json($Sales->data());
	if(!$Sales->find_temp($hash)) die(http_response_code(400));

	$idpromo = $Sales->data()->idpromo;
	$idclient = $Sales->data()->idclient;
	$iduser = $Sales->data()->iduser;
	$quantity = $Sales->data()->quantity;
	$price = $Sales->data()->price;
	$idcode = $Sales->data()->idcode;
	$reservationid = $Sales->data()->reservationid;

	$arrfields = array(
		'iduser'=>$iduser,
		'idclient'=>$idclient,
		'idpromo'=>$idpromo,
		'collection_id'=>$collection_id,
		'payment_status'=>$payment_status,
		'preference_id'=>'',
		'external_reference'=>$hash,
		'payment_type'=>$payment_type,
		'merchant_order_id'=>$merchant_order_id,
		'price'=>$price,
		'application_fee'=>isset($fees) ? $fees->application_fee : 0,
		'mercadopago_fee'=>isset($fees) ? $fees->mercadopago_fee : 0,
		'added'=>date('Y-m-d H:i:s'),
		'quantity'=>$quantity,
		'hash'=>$hash
	);
	$Sales->save($arrfields);
	$saleid = $Sales->getLastId();


	$Reservations = new Reservations();
	$Reservations->reservations_sales($reservationid,$hash);
	///////// VOUCHER ////////////
	if($idcode){
		if($Vouchers->findcode($idcode)){
			$idvoucher = $Vouchers->data()->idvoucher;
			if($Vouchers->find($idvoucher)){
				$sqlvoucher = array(
					'idvoucher'=>$idvoucher,
					'idcode'=>$idcode,
					'iduser'=>$iduser,
					'idsale'=>$saleid,
					'ispercent'=>$Vouchers->data()->ispercent,
					'value'=>$Vouchers->data()->value,
					'added'=>date('Y-m-d H:i:s')
				);
				$Vouchers->usage($sqlvoucher);
			}
		}
	}

	///$Sales->delete_temp($hash); borrar con cron
	$Promos->take_amount($idpromo,$quantity);
}



if(!$saleid) die(http_response_code(400));

if(!$Sales->find($saleid)) die(http_response_code(400));
$_salesdata = $Sales->data();
$_salesdata->promolink = ROOT.'promo/'.$_salesdata->permalink.'/'.$_salesdata->idpromo.'-'.Permalink($_salesdata->title);

if(!$Promos->find($_salesdata->idpromo)) die(http_response_code(400));
$promo = $Promos->data();

$Stores->get($_salesdata->clientid);
$stores = $Stores->data();
$stores_by_ids = array_column($stores, null, 'id');

$promo_stores_ids = explode(',',$promo->stores);
$promo_stores = [];
if($promo_stores_ids){
	foreach($promo_stores_ids as $store){
		$promo_stores[] = $stores_by_ids[$store] ?? null;
	}
}


$_salesdata->stores = '<ul style="padding:0 16px">';
if($promo_stores){
	foreach($promo_stores as $store){
		$_salesdata->stores .= '<li>'.$store->address.', '.$store->city.' - '.$store->name.' '.(!empty($store->phones) ? ' - Tel: '.$store->phones : '' ).(!empty($store->whatsapp) ? ' - Celular: '.$store->whatsapp : '' ).'</li>';
	}
}
$_salesdata->stores .= '</ul>';

$_salesdata->gift = null;
$_salesdata->image = $Promos->get_image($_salesdata->gallery);

//if($Sales->find_gift($hash)) $_salesdata->gift = $Sales->data();

if(!is_null($_salesdata->voucher_id)){
	if($_salesdata->voucher_percent){
		$_salesdata->price = $_salesdata->price-($_salesdata->voucher_value*$_salesdata->price/100);
	}else{
		$_salesdata->price = $_salesdata->price-$_salesdata->voucher_value;

	}
}


if($collection_status == 'approved' && !$_salesdata->notified){
	$Mailing->sales_success_user($_salesdata);
	$Mailing->sales_success_client($_salesdata);
	$Sales->notified($_salesdata->id,1);
	$_salesdata->notified = 1;
	/*if($_salesdata->gift){
		$Mailing->sales_success_gift($_salesdata);
	}*/
}
if(
	(
		$payment_status == 'pending' || 
		$payment_status == 'in_process' || 
		$payment_status == 'in_mediation' || 
		$payment_status == 'authorized'
	) && !$_salesdata->notified
){
	$Mailing->sales_pending($_salesdata);
}
if(
	(
		$payment_status == 'rejected' || 
		$payment_status == 'cancelled'
	) &&  !$_salesdata->notified
){
	$Mailing->sales_rejected($_salesdata);
}