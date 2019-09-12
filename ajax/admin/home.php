<?php

header("Content-Type: application/json; charset=utf-8", true);

$User = new User();
$Banners = new Banners();

if(!Input::check(Input::get('required'))) die(Responses::response('fail'));
if(!$User->logged() || $User->data()->idtype != 1) die(Responses::response('restricted'));

switch($_action){

	case 'upmain':
		$Folder = '../'.Input::get('folder');
		$upfile = new File($_FILES['file'],$Folder);
		$upfile->MoveFile();
		$file = $upfile->Resize(array(array(960,960,'-o'),array(400,400,'-t')),'',false);
		echo json_encode($file);
		break;

	case 'upside':
		$Folder = '../'.Input::get('folder');	
		$upfile = new File($_FILES['file'],$Folder);
		$upfile->MoveFile();
		$file = $upfile->Resize(array(array(768,328,'-o'),array(580,248,'-t')),'',false);
		echo json_encode($file);
		break;

	case 'save':

		if(!empty(Input::get('Link')['url']) && !filter_var(Input::get('Link')['url'],FILTER_VALIDATE_URL)) die(Responses::response('url'));

		$Banners->save();
		echo Responses::response('ok','',array('id'=>$Banners->getLastId()));
		break;

	case 'get':
		$Banners->sort = 'position';
		$Banners->get();
		echo Responses::response('ok','',array('results'=>$Banners->data()));
		break;

	case 'find':
		$Banners->find(Input::get('ID'));
		echo Responses::response('ok','',array('result'=>$Banners->data()));
		break;

	case 'delete':
		$Banners->delete();
		echo Responses::response('ok');
		break;

	case 'reorder':
		$Banners->reorder();
		echo Responses::response('ok');
		break;

	default:
		echo Responses::response('fail');
		break;

}