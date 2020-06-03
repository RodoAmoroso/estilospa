<?php

require_once '../config.php';
header("Content-Type: application/json; charset=utf-8", true);

$User = new User();
$Promos = new Promos();
$Sales = new Sales();
$PromoTypes = new PromoTypes();
$Vouchers = new Vouchers();

if(!Input::check(Input::get('required'))) die(Responses::response('fail'));
if(!$User->logged() || $User->data()->idtype != 1) die(Responses::response('restricted'));

switch($_action){


	case 'gallery':

		$Folder = '../'.Input::get('folder');
		$upfile = new File($_FILES['file'],$Folder);
		$upfile->MoveFile();
		$file = $upfile->Resize(array(array(1280,720,'-o'),array(600,600,'-t')), '', false);
		echo json_encode($file);
		break;

	case 'save':

		if( !Input::check(array('Title','Description','Start','Finish','Gallery')) ) die(Responses::response('required'));

		if(!$Promos->save(Input::get('IDClient'))) die(Responses::response('fail'));
		echo Responses::response('ok','',array('id'=>$Promos->getLastId()));
		break;

	case 'get':
		$Promos->keywords = Input::get('keywords');
		$Promos->idclient = Input::get('idclient');
		$Promos->status = Input::get('status');
		$Promos->categoryid = Input::get('categoryid');
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


	default:
		echo Responses::response('fail');
		break;

}