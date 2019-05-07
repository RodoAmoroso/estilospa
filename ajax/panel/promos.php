<?php

require_once '../config.php';
header("Content-Type: application/json; charset=utf-8", true);

$User = new User();
$Clients = new Clients();
$Promos = new Promos();
$PromoTypes = new PromoTypes();


if(!Input::check(Input::get('required'))) die(Responses::response('fail'));
if(!$User->logged() || $User->data()->idtype != 3) die(Responses::response('restricted'));

switch($_action){


	case 'gettypes':
		$PromoTypes->get();
		echo Responses::response('ok','',array('results'=>$PromoTypes->data()));
		break;

	case 'get':
		$Promos->keywords = Input::get('keywords');
		$Promos->idclient = $User->data()->idclient;
		$Promos->status = Input::get('status');
		$Promos->sort = Input::get('sort');
		$Promos->searchmixed = Input::get('search_mixed');
		$Promos->get();
		echo Responses::response('ok','',array('results'=>$Promos->data()));
		break;

	case 'find':
		if(!$Promos->find(Input::get('ID'))) die(Responses::response('fail'));
		echo Responses::response('ok','',array('result'=>$Promos->data()));
		break;

	case 'delete':
		if(!$Promos->delete(Input::get('ID'))) die(Responses::response('fail'));
		echo Responses::response('ok');
		break;

	case 'reorder':
		if(!$Promos->reorder(Input::get('arrids'))) die(Responses::response('fail'));
		echo Responses::response('ok');
		break;


	case 'save':
		
		if(!Input::get('ID')){
			$Promos->issale = 1;
			$Promos->idclient = $User->data()->idclient;
			$Promos->get();
			$cantpromos = count($Promos->data());
			if($cantpromos == $User->data()->cantpromos){
				///die(json_encode(array('Status'=>'cantpromos')));
				die(Responses::response('fail','Tu plan contratado no te permite agregar más promociones.'));
			}
		}
		if(!$Promos->save($User->data()->idclient)) die(Responses::response('fail'));
		echo Responses::response('ok','La promo se guardó correctamente!',array('ID'=>$Promos->getLastId()));
		break;

	case 'gallery':

		$Folder = '../'.Input::get('folder');	
		$upfile = new File($_FILES['file'],$Folder);
		$upfile->MoveFile();
		$file = $upfile->Resize(array(array(1280,720,'-o'),array(600,600,'-t')), '', false);
		echo json_encode($file);
		break;

	default:
		echo Responses::response('fail');
		break;

}