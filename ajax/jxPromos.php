<?php

require_once '../config.php';
error_reporting(0);

if(!Input::exists()) die(json_encode(array('Status'=>'fail')));
$_PROMOS = new Promos();
$_SALES = new Sales();
$_PROMOTYPES = new PromoTypes();
$_VOUCHERS = new Vouchers();

switch (Input::get('Mode')) {
	case 'upimage':
		if(!$_USER->logged() && $_USER->data()->idtype != 1) die(json_encode(array('Status'=>'fail')));
		$Folder = '../'.Input::get('Folder');	
		$upfile = new File($_FILES[0],$Folder);
		$upfile->MoveFile();
		$file = $upfile->Resize(array(array(1280,720,'-o'),array(600,600,'-t')), '', false);
		echo json_encode($file);
		break;

	case 'save':
		if(!$_USER->logged() && $_USER->data()->idtype != 1) die(json_encode(array('Status'=>'fail')));
		if( !Input::check(array('Title','Description','Start','Finish','Gallery')) ) die(json_encode(array('Status'=>'fail')));
		if(!$_PROMOS->save()) die(json_encode(array('Status'=>'fail')));
		echo json_encode(array('Status'=>'ok','ID'=>$_PROMOS->getLastId()));
		break;

	case 'saveclient':
		if(!$_USER->logged() && $_USER->data()->idtype != 3) die(json_encode(array('Status'=>'restricted')));
		if($_USER->data()->idclient != Input::get('IDClient')) die(json_encode(array('Status'=>'restricted')));
		if(!Input::get('ID')){
			$_PROMOS->issale = 1;
			$_PROMOS->idclient = $_USER->data()->idclient;
			$_PROMOS->get();
			$cantpromos = count($_PROMOS->data());
			if($cantpromos == $_USER->data()->cantpromos){
				die(json_encode(array('Status'=>'cantpromos')));
			}
		}
		if(!$_PROMOS->save()) die(json_encode(array('Status'=>'fail')));
		echo json_encode(array('Status'=>'ok','ID'=>$_PROMOS->getLastId()));
		break;

	case 'get':
		$_PROMOS->keywords = Input::get('Keywords');
		$_PROMOS->idclient = Input::get('IDClient');
		$_PROMOS->status = Input::get('Status');
		$_PROMOS->sort = Input::get('Sort');
		$_PROMOS->searchmixed = Input::get('SearchMixed');
		$_PROMOS->get();
		echo json_encode(array('Status'=>'ok','Results'=>$_PROMOS->data()));
		break;

	case 'find':
		if(!$_PROMOS->find(Input::get('ID'))) die(json_encode(array('Status'=>'fail')));
		echo json_encode(array('Status'=>'ok','Result'=>$_PROMOS->data()));
		break;

	case 'delete':
		if(!$_USER->logged() && $_USER->data()->idtype != 1) die(json_encode(array('Status'=>'fail')));
		if(!$_PROMOS->delete(Input::get('ID'))) die(json_encode(array('Status'=>'fail')));
		echo json_encode(array('Status'=>'ok'));
		break;

	case 'reorder':
		if(!$_USER->logged() && $_USER->data()->idtype != 1) die(json_encode(array('Status'=>'fail')));
		if(!$_PROMOS->reorder(Input::get('arrids'))) die(json_encode(array('Status'=>'fail')));
		echo json_encode(array('Status'=>'ok'));
		break;
	
	///////////// PROMO TYPES ////////////////
	case 'savepromotype':
		if(!$_USER->logged() && $_USER->data()->idtype != 1) die(json_encode(array('Status'=>'fail')));
		if( !Input::check(array('Name')) ) die(json_encode(array('Status'=>'fail')));
		$_PROMOTYPES->save();
		echo json_encode(array('Status'=>'ok'));
		break;
	case 'getpromotypes':
		$_PROMOTYPES->get();
		echo json_encode(array('Status'=>'ok', 'Results'=>$_PROMOTYPES->data()));
		break;
	case 'findpromotypes':
		$_PROMOTYPES->find();
		echo json_encode(array('Status'=>'ok', 'Result'=>$_PROMOTYPES->data()));
		break;
	case 'deletepromotype':
		if(!$_USER->logged() && $_USER->data()->idtype != 1) die(json_encode(array('Status'=>'fail')));
		$_PROMOTYPES->delete();
		echo json_encode(array('Status'=>'ok'));
		break;

	///////////// SHOP ///////////////////////
	case 'getmplink':
		$idpromo = intval(Input::get('IDP'));
		$amount = intval(Input::get('Amount'));
		$idcode = intval(Input::get('IDC'));

		if(!$_PROMOS->find($idpromo)) die(json_encode(array('Status'=>'fail')));
		if(!$_PROMOS->data()->statusstart || !$_PROMOS->data()->statusfinish) die(json_encode(array('Status'=>'finished')));
		if($_PROMOS->data()->amount<$amount) die(json_encode(array('Status'=>'amount','Amount'=>$_PROMOS->data()->amount)));
		$MPConfig = new MPConfig();
		if(!$MPConfig->find($_PROMOS->data()->idclient)) die(json_encode(array('Status'=>'fail')));
		if(!$_USER->logged()) die(json_encode(array('Status'=>'logged')));

		$_CLIENTS = new Clients();
		$_CLIENTS->find($_PROMOS->data()->idclient);


		$mplink = '#';
		if($MPConfig->getmplink($_PROMOS->data(),$_USER->data(),$_CLIENTS->data(),$amount,$idcode)){
			$mplink = $MPConfig->mplink()['response']['init_point'];
			echo json_encode(array('Status'=>'ok','Link'=>$mplink,'Hash'=>$MPConfig->hash()));
		}else{
			echo json_encode(array('Status'=>'error','Error'=>$MPConfig->error()));
		}
		break;

	case 'gift':

		if(!$_USER->logged()) die(json_encode(array('Status'=>'logged')));

		$arrfields = array(
			'iduser'=>$_USER->data()->id,
			'fromuser'=>Input::get('From'),
			'touser'=>Input::get('To'),
			'mail'=>Input::get('Mail'),
			'message'=>Input::get('Message'),
			'idpromo'=>intval(Input::get('IDP')),
			'hash'=>Input::get('Hash'),
			'added'=>date('Y-m-d H:i:s')
		);
		$_SALES->savegift($arrfields);
		echo json_encode(array('Status'=>'ok'));
		break;

	default:
		echo json_encode(array('Status'=>'fail'));
		break;
}