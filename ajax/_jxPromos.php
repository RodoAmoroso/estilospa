<?php

require_once '../config.php';
error_reporting(0);

if(!Input::exists()) die(json_encode(array('Status'=>'fail')));
$Promos = new Promos();
$Sales = new Sales();
$PromoTypes = new PromoTypes();
$Vouchers = new Vouchers();

switch (Input::get('Mode')) {
	case 'upimage':
		if(!$User->logged() && $User->data()->idtype != 1) die(json_encode(array('Status'=>'fail')));
		$Folder = '../'.Input::get('Folder');	
		$upfile = new File($_FILES[0],$Folder);
		$upfile->MoveFile();
		$file = $upfile->Resize(array(array(1280,720,'-o'),array(600,600,'-t')), '', false);
		echo json_encode($file);
		break;

	case 'save':
		if(!$User->logged() && $User->data()->idtype != 1) die(json_encode(array('Status'=>'fail')));
		if( !Input::check(array('Title','Description','Start','Finish','Gallery')) ) die(json_encode(array('Status'=>'fail')));
		if(!$Promos->save()) die(json_encode(array('Status'=>'fail')));
		echo json_encode(array('Status'=>'ok','ID'=>$Promos->getLastId()));
		break;

	case 'saveclient':
		if(!$User->logged() && $User->data()->idtype != 3) die(json_encode(array('Status'=>'restricted')));
		if($User->data()->idclient != Input::get('IDClient')) die(json_encode(array('Status'=>'restricted')));
		if(!Input::get('ID')){
			$Promos->issale = 1;
			$Promos->idclient = $User->data()->idclient;
			$Promos->get();
			$cantpromos = count($Promos->data());
			if($cantpromos == $User->data()->cantpromos){
				die(json_encode(array('Status'=>'cantpromos')));
			}
		}
		if(!$Promos->save()) die(json_encode(array('Status'=>'fail')));
		echo json_encode(array('Status'=>'ok','ID'=>$Promos->getLastId()));
		break;

	case 'get':
		$Promos->keywords = Input::get('Keywords');
		$Promos->idclient = Input::get('IDClient');
		$Promos->status = Input::get('Status');
		$Promos->sort = Input::get('Sort');
		$Promos->searchmixed = Input::get('SearchMixed');
		$Promos->get();
		echo json_encode(array('Status'=>'ok','Results'=>$Promos->data()));
		break;

	case 'find':
		if(!$Promos->find(Input::get('ID'))) die(json_encode(array('Status'=>'fail')));
		echo json_encode(array('Status'=>'ok','Result'=>$Promos->data()));
		break;

	case 'delete':
		if(!$User->logged() && $User->data()->idtype != 1) die(json_encode(array('Status'=>'fail')));
		if(!$Promos->delete(Input::get('ID'))) die(json_encode(array('Status'=>'fail')));
		echo json_encode(array('Status'=>'ok'));
		break;

	case 'reorder':
		if(!$User->logged() && $User->data()->idtype != 1) die(json_encode(array('Status'=>'fail')));
		if(!$Promos->reorder(Input::get('arrids'))) die(json_encode(array('Status'=>'fail')));
		echo json_encode(array('Status'=>'ok'));
		break;
	
	///////////// PROMO TYPES ////////////////
	case 'savepromotype':
		if(!$User->logged() && $User->data()->idtype != 1) die(json_encode(array('Status'=>'fail')));
		if( !Input::check(array('Name')) ) die(json_encode(array('Status'=>'fail')));
		$PromoTypes->save();
		echo json_encode(array('Status'=>'ok'));
		break;
	case 'getpromotypes':
		$PromoTypes->get();
		echo json_encode(array('Status'=>'ok', 'Results'=>$PromoTypes->data()));
		break;
	case 'findpromotypes':
		$PromoTypes->find();
		echo json_encode(array('Status'=>'ok', 'Result'=>$PromoTypes->data()));
		break;
	case 'deletepromotype':
		if(!$User->logged() && $User->data()->idtype != 1) die(json_encode(array('Status'=>'fail')));
		$PromoTypes->delete();
		echo json_encode(array('Status'=>'ok'));
		break;

	///////////// SHOP ///////////////////////
	case 'getmplink':
		$idpromo = intval(Input::get('IDP'));
		$amount = intval(Input::get('Amount'));
		$idcode = intval(Input::get('IDC'));

		if(!$Promos->find($idpromo)) die(json_encode(array('Status'=>'fail')));
		if(!$Promos->data()->statusstart || !$Promos->data()->statusfinish) die(json_encode(array('Status'=>'finished')));
		if($Promos->data()->amount<$amount) die(json_encode(array('Status'=>'amount','Amount'=>$Promos->data()->amount)));
		
		$MPConfig = new MPConfig();
		if(!$MPConfig->find($Promos->data()->idclient)) die(json_encode(array('Status'=>'fail')));
		if(!$User->logged()) die(json_encode(array('Status'=>'logged')));

		$Clients = new Clients();
		$Clients->find($Promos->data()->idclient);


		$mplink = '#';
		if($MPConfig->getmplink($Promos->data(),$User->data(),$Clients->data(),$amount,$idcode)){
			$mplink = $MPConfig->mplink()['response']['init_point'];
			echo json_encode(array('Status'=>'ok','Link'=>$mplink,'Hash'=>$MPConfig->hash()));
		}else{
			echo json_encode(array('Status'=>'error','Error'=>$MPConfig->error()));
		}
		break;

	case 'gift':

		if(!$User->logged()) die(json_encode(array('Status'=>'logged')));

		$arrfields = array(
			'iduser'=>$User->data()->id,
			'fromuser'=>Input::get('From'),
			'touser'=>Input::get('To'),
			'mail'=>Input::get('Mail'),
			'message'=>Input::get('Message'),
			'idpromo'=>intval(Input::get('IDP')),
			'hash'=>Input::get('Hash'),
			'added'=>date('Y-m-d H:i:s')
		);
		$Sales->savegift($arrfields);
		echo json_encode(array('Status'=>'ok'));
		break;

	default:
		echo json_encode(array('Status'=>'fail'));
		break;
}