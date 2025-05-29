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
		$file = $upfile->Resize(array(array(1920,600,'-o'),array(720,360,'-t')),'',false);
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

		if(!empty(Input::get('link')['url']) && !filter_var(Input::get('link')['url'],FILTER_VALIDATE_URL)) die(Responses::response('url'));

		$bannerid = $Banners->save();
		echo Responses::response('ok','',array('id'=>$bannerid));
		break;

	case 'get':
		$Banners->sort = 'position';
		$banners = $Banners->get();
		echo Responses::response('ok','',array('results'=>$banners));
		break;

	case 'find':
		$banner = $Banners->find(Input::get('id'));
		echo Responses::response('ok','',array('result'=>$banner));
		break;

	case 'delete':
		$Banners->delete(Input::get('id'));
		echo Responses::response('ok');
		break;

	case 'reorder':
		$Banners->reorder(Input::get('arrids'));
		echo Responses::response('ok');
		break;

	default:
		echo Responses::response('fail');
		break;

}