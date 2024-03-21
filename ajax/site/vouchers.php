<?php

header("Content-Type: application/json; charset=utf-8", true);

$Promos = new Promos();
$User = new User();
$Vouchers = new Vouchers();
$Sales = new Sales();
$Stores = new Stores();
$Mailing = new Mailing();

if(!Input::check(Input::get('required'))) die(Responses::response('required'));

switch($_action){

	case 'validate':
		if(!$User->logged()) die(Responses::response('require_login'));
		if(!$Vouchers->validate( Input::get('idpromo'), Input::get('code'), $User->data()->id )) die(Responses::response('fail',$Vouchers->errors()));

		$voucher = $Vouchers->data();

		if(!$Sales->find_temp(Cookie::get('sale_hash'))) die(Responses::response('fail'));
		$Sales->update_temp($Sales->data()->id,[
			'idcode'=>$voucher->codeid,
			'modified'=>date('Y-m-d H:i:s')
		]);

		echo Responses::response('ok','',[
			'result'=>$voucher
		]);
		break;

	case 'free':

		if(!$User->logged()) die(Responses::response('require_login'));

		$code = Input::get('code');
		$idpromo = Input::get('idpromo');

		if(!$Vouchers->findcode($code)) die(Responses::response('fail','El código ingresado no es válido',array('voucher'=>$code)));

		if(!$Promos->find($idpromo)) die(Responses::response('fail','No se encuentra la promo.'));

		$idvoucher = $Vouchers->data()->idvoucher;
		$idcode = $Vouchers->data()->id;
		$hash = hash('sha256',uniqid());

		$Sales->create_temp(array(
			'iduser'=>$User->data()->id,
			'idclient'=>$Promos->data()->idclient,
			'idpromo'=>$idpromo,
			'idcode'=>$idcode,
			'quantity'=>1,
			'price'=>0,
			'hash'=>$hash,
			'added'=>date('Y-m-d H:i:s')
		));

		$collection_id = date('YmdHis');
		$payment_type = 'free';
		$merchant_order_id = date('YmdHis');
		$collection_status = 'approved';

		require PATH.'payment-process.php';


		$promo_url = ROOT.'promo/'.$Promos->data()->permalink.'/'.$Promos->data()->id.'-'.Permalink($Promos->data()->title);

		echo Responses::response('ok','',array('promourl'=>$promo_url,'hash'=>$hash));
		break;

	default:
		echo Responses::response('fail');
		break;

}