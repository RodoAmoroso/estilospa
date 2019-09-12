<?php

header("Content-Type: application/json; charset=utf-8", true);

$User = new User();
$Blog = new Blog();

if(!Input::check(Input::get('required'))) die(Responses::response('fail'));
if(!$User->logged() || $User->data()->idtype != 1) die(Responses::response('restricted'));

switch($_action){

	case 'save':
		$Blog->save();
		echo Responses::response('ok');
		break;

	case 'get':
		$Blog->keywords = Input::get('keywords');
		$Blog->idcategory = Input::get('idcategory');
		$Blog->limit = '0,100';
		$Blog->searchmixed = intval(Input::get('search_mixed'));
		$Blog->get();
		echo Responses::response('ok','',array('results'=>$Blog->data()));
		break;

	case 'find':
		$Blog->find(Input::get('blogid'));
		echo Responses::response('ok','',array('result'=>$Blog->data()));
		break;

	case 'delete':
		$Blog->delete();
		echo Responses::response('ok');
		break;

	case 'insertimage':
		$folder = '../'.Input::get('folder');	
		$upfile = new File($_FILES['file'],$folder);
		$upfile->MoveFile();
		$file = $upfile->Resize(array(array(1280,720,'')),'',false);
		echo json_encode($file);
		break;

	case 'gallery':
		$Folder = '../'.Input::get('folder');	
		$upfile = new File($_FILES['file'],$Folder);
		$upfile->MoveFile();
		$file = $upfile->Resize(array(array(1280,720,'-o'),array(480,480,'-t')),'',false);
		echo json_encode($file);
		break;

	default:
		echo Responses::response('fail');
		break;

}