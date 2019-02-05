<?php

require_once '../config.php';

if(!Input::exists()) die(json_encode(array('Status'=>'fail')));


$_BANNERS = new Banners();

switch (Input::get('Mode')):

	case 'upmain':
		if(!$_USER->logged() && $_USER->data()->idtype != 1) die(json_encode(array('Status'=>'fail')));
		$Folder = '../'.Input::get('Folder');	
		$upfile = new File($_FILES[0],$Folder);
		$upfile->MoveFile();
		$file = $upfile->Resize(array(array(960,960,'-o'),array(400,400,'-t')),'',false);
		echo json_encode($file);
		break;

	case 'upside':
		if(!$_USER->logged() && $_USER->data()->idtype != 1) die(json_encode(array('Status'=>'fail')));
		$Folder = '../'.Input::get('Folder');	
		$upfile = new File($_FILES[0],$Folder);
		$upfile->MoveFile();
		$file = $upfile->Resize(array(array(768,328,'-o'),array(580,248,'-t')),'',false);
		echo json_encode($file);
		break;

	case 'save':
		if(!$_USER->logged() || $_USER->data()->idtype != 1) die(json_encode(array('Status'=>'fail')));

		if(!empty(Input::get('Link')['url'])) if(!filter_var(Input::get('Link')['url'],FILTER_VALIDATE_URL)) die(json_encode(array('Status'=>'url')));

		$_BANNERS->save();
		echo json_encode(array('Status'=>'ok','ID'=>$_BANNERS->getLastId()));		
		break;

	case 'get':
		$_BANNERS->sort = 'position';
		$_BANNERS->get();
		echo json_encode(array('Status'=>'ok','Results'=>$_BANNERS->data()));
		break;

	case 'find':
		$_BANNERS->find(Input::get('ID'));
		echo json_encode(array('Status'=>'ok','Result'=>$_BANNERS->data()));
		break;

	case 'delete':
		if(!$_USER->logged() || $_USER->data()->idtype != 1) die(json_encode(array('Status'=>'fail')));
		$_BANNERS->delete();
		echo json_encode(array('Status'=>'ok'));
		break;

	case 'reorder':
		$_BANNERS->reorder();
		echo json_encode(array('Status'=>'ok'));
		break;

	default:
		echo json_encode(array('Status'=>'fail'));
		break;

endswitch;