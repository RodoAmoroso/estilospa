<?php

$User = new User();
if(!$User->logged()) die(Responses::response('restricted'));

$Sales = new Sales();
$SalesVouchers = new SalesVouchers();

if(!Input::check(Input::get('required'))) die(Responses::response('fail'));

switch($_action){

	case 'save':

		if(!$sale = $Sales->find(Input::get('saleid'))) die(Responses::response('fail','No se encontró la compra'));
		///dd($sale);

		if($sale->iduser != $_userdata->id) die(Responses::response('restricted'));

		if(empty(Input::get('id'))){
			$SalesVouchers->filters = ['sale'=>$sale->id];
			$vouchers = $SalesVouchers->get();
			if($vouchers && count($vouchers) >= $sale->quantity) die(Responses::response('fail','Ya no podés generar más vouchers'));
		}

		$voucherid = $SalesVouchers->save();
		echo Responses::response('ok','',['voucherid'=>$voucherid]);
		break;

	case 'update-voucher':
		break;
	case 'find-voucher':
		if(!$voucher = $SalesVouchers->find(Input::get('voucherid'))) die(Responses::response('fail'));
		if(!$sale = $Sales->find($voucher->saleid)) die(Responses::response('fail','No se encontró la compra'));

		if($sale->iduser != $_userdata->id) die(Responses::response('restricted'));
		echo Responses::response('ok','',[
			'voucher'=>$voucher
		]);
		break;


	case 'upimage':

		$upfile = new File($_FILES['file'],'../img/gift');
		$upfile->MoveFile();
		$file = $upfile->Resize(array(array(600,600,'')), '', false);

		echo json_encode($file);

		break;


	default:
		echo Responses::response('fail');
		break;


}