<?php

require_once '../config.php';

if(!Input::exists()) die(json_encode(array('Status'=>'fail')));


$Banners = new Banners();

switch (Input::get('Mode')):

	case 'upmain':
		if(!$User->logged() && $User->data()->idtype != 1) die(json_encode(array('Status'=>'fail')));
		$Folder = '../'.Input::get('Folder');	
		$upfile = new File($_FILES[0],$Folder);
		$upfile->MoveFile();
		$file = $upfile->Resize(array(array(960,960,'-o'),array(400,400,'-t')),'',false);
		echo json_encode($file);
		break;

	case 'upside':
		if(!$User->logged() && $User->data()->idtype != 1) die(json_encode(array('Status'=>'fail')));
		$Folder = '../'.Input::get('Folder');	
		$upfile = new File($_FILES[0],$Folder);
		$upfile->MoveFile();
		$file = $upfile->Resize(array(array(768,328,'-o'),array(580,248,'-t')),'',false);
		echo json_encode($file);
		break;

	case 'save':
		if(!$User->logged() || $User->data()->idtype != 1) die(json_encode(array('Status'=>'fail')));

		if(!empty(Input::get('Link')['url'])) if(!filter_var(Input::get('Link')['url'],FILTER_VALIDATE_URL)) die(json_encode(array('Status'=>'url')));

		$Banners->save();
		echo json_encode(array('Status'=>'ok','ID'=>$Banners->getLastId()));		
		break;

	case 'get':
		$Banners->sort = 'position';
		$Banners->get();
		echo json_encode(array('Status'=>'ok','Results'=>$Banners->data()));
		break;

	case 'find':
		$Banners->find(Input::get('ID'));
		echo json_encode(array('Status'=>'ok','Result'=>$Banners->data()));
		break;

	case 'delete':
		if(!$User->logged() || $User->data()->idtype != 1) die(json_encode(array('Status'=>'fail')));
		$Banners->delete();
		echo json_encode(array('Status'=>'ok'));
		break;

	case 'reorder':
		$Banners->reorder();
		echo json_encode(array('Status'=>'ok'));
		break;

	default:
		echo json_encode(array('Status'=>'fail'));
		break;

endswitch;