<?php

header("Content-Type: application/json; charset=utf-8", true);

$User = new User();
if(!$User->logged()) die(Responses::response('restricted'));

$Sales = new Sales();

if(!Input::check(Input::get('required'))) die(Responses::response('fail'));

switch($_action){

		case 'add':

			if(!$Sales->find(Input::get('saleid'))) die(Responses::response('fail','No se encontró la compra'));
			$sale = $Sales->data();

			if($sale->iduser != $User->data()->id) die(Responses::response('restricted'));

			$vouchers = $Sales->get_vouchers($sale->id);
			if($vouchers && count($vouchers) >= $sale->quantity) die(Responses::response('fail','Ya no podés generar más vouchers'));

			$voucherid = $Sales->add_voucher();
			echo Responses::response('ok','',array('voucherid'=>$voucherid));
			break;


		case 'upimage':

			$folder = '../'.Input::get('folder');
			$upfile = new File($_FILES['file'],$folder);
			$upfile->MoveFile();
			$file = $upfile->Resize(array(array(600,600,'')), '', false);

			echo json_encode($file);

		break;


		default:
		echo Responses::response('fail');
		break;


}