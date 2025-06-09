<?php

$User = new User();
$Clients = new Clients();
$Features = new Features();
$Stores = new Stores();
$Promos = new Promos();
$Assoc = new Assoc();
$Sales = new Sales();
$MPConfig = new MPConfig();


if(!Input::check(Input::get('required'))) die(Responses::response('fail'));
if(!$User->logged() || $User->data()->idtype != 1) die(Responses::response('restricted'));

switch($_action){

	case 'find':
		$id = Input::get('id');
		$Clients->find($id);
		$Assoc->idclient = $id;
		$Assoc->client_user('get');
		$Stores->get($id);
		$Features->get($id);
		echo Responses::response('ok','',array(
			'client'=>$Clients->data(),
			'stores'=>$Stores->data(),
			'features'=>$Features->data(),
			'users'=>$Assoc->data()
		));
		break;

	case 'get':
		$Clients->keywords = Input::get('keywords');
		$Clients->sort = Input::get('sort');
		$Clients->searchmixed = intval(Input::get('search_mixed'));
		$Clients->get();
		echo Responses::response('ok','',array('results'=>$Clients->data()));
		break;

	case 'save':
		$Clients->isadmin = true;
		if(!$Clients->save(Input::get('ID'))) die(Responses::response('fail'));
		$id = $Clients->getLastId();
		$Assoc->idclient = $id;
		$Assoc->arrusers = Input::get('Users');		
		$Assoc->client_user('save');
		echo Responses::response('ok','',array('id'=>$id));
		break;

	case 'delete':
		if(!$Clients->delete(Input::get('ID'))) die(Responses::response('fail',$Clients->error()));
		echo Responses::response('ok');	
		break;

	case 'gallery':
		$Folder = '../'.Input::get('folder');	
		$upfile = new File($_FILES['file'],$Folder);
		$upfile->MoveFile();
		$file = $upfile->Resize(array(array(1280,720,'-o'),array(600,600,'-t')), '', false);
		echo json_encode($file);
		break;
	case 'logo':
		$Folder = '../'.Input::get('folder');	
		$upfile = new File($_FILES['file'],$Folder);
		$upfile->MoveFile();
		$file = $upfile->Resize(array(array(600,600,'')), '', false);
		echo json_encode($file);
		break;


	case 'unlink_mp':
		$MPConfig->unlink(Input::get('idclient'));
		echo Responses::response('ok');
		break;

	case 'renew_token':
		$MPConfig->renewtoken(Input::get('idclient'));
		echo Responses::response('ok');
		break;


	default:
		echo Responses::response('fail');
		break;

}