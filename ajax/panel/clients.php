<?php

header("Content-Type: application/json; charset=utf-8", true);

$User = new User();
$Clients = new Clients();
$Assoc = new Assoc();
$Features = new Features();
$Stores = new Stores();
$MPConfig = new MPConfig();

if(!Input::check(Input::get('required'))) die(Responses::response('fail'));
if(!$User->logged() || $User->data()->idtype != 3) die(Responses::response('restricted'));

switch($_action){

	case 'find':
		$id = $User->data()->idclient;
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


	case 'save':

		if(!Input::check(array('Name','Subtitle','Gallery','Features','Mail'))) die(Response::response('required'));
		$Clients->isadmin = false;
		$Clients->save($User->data()->idclient);
		/////////////////////////////////////////////////
		echo Responses::response('ok','Los datos fueron guardados exitosamente!');
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

	case 'unlink':
		$MPConfig->unlink($User->data()->idclient);
		echo Responses::response('ok');
		break;


	default:
		echo Responses::response('fail');
		break;

}