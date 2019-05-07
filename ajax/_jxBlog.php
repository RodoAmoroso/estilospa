<?php

require_once '../config.php';

if(!Input::exists()){
	die(json_encode(array('Status'=>'fail')));
}

$Blog = new Blog();
$BlogCategories = new BlogCategories();

switch (Input::get('Mode')) {
	
	case 'insertimage':
		if(!$User->logged() && $User->data()->idtype != 1) die(json_encode(array('Status'=>'fail')));
		$Folder = '../'.Input::get('Folder');	
		$upfile = new File($_FILES[0],$Folder);
		$upfile->MoveFile();
		$file = $upfile->Resize(array(array(1280,720,'')),'',false);
		echo json_encode($file);
		break;
	case 'upgallery':
		if(!$User->logged() && $User->data()->idtype != 1) die(json_encode(array('Status'=>'fail')));
		$Folder = '../'.Input::get('Folder');	
		$upfile = new File($_FILES[0],$Folder);
		$upfile->MoveFile();
		$file = $upfile->Resize(array(array(1280,720,'-o'),array(480,480,'-t')),'',false);
		echo json_encode($file);
		break;
	case 'save':
		if(!$User->logged() && $User->data()->idtype != 1) die(json_encode(array('Status'=>'fail')));
		$Blog->save();		
		echo json_encode(array('Status'=>'ok'));
		break;
	case 'get':
		$Blog->keywords = Input::get('Keywords');
		$Blog->idcategory = Input::get('IDCategory');
		$Blog->limit = '0,100';
		$Blog->searchmixed = intval(Input::get('SearchMixed'));
		$Blog->get();
		echo json_encode(array('Status'=>'ok', 'Results'=>$Blog->data()));		
		break;
	case 'find':
		$Blog->find(Input::get('ID'));
		echo json_encode(array('Status'=>'ok', 'Result'=>$Blog->data()));
		break;
	case 'delete':
		$Blog->delete();
		echo json_encode(array('Status'=>'ok'));
		break;

	///////////// CATEGORY ////////////////////////////////
	case 'savecategory':
		if(!$User->logged() && $User->data()->idtype != 1) die(json_encode(array('Status'=>'fail')));
		if(!Input::check(array('Name'))) die(json_encode(array('Status'=>'fail')));
		$BlogCategories->save();		
		echo json_encode(array('Status'=>'ok','ID'=>$BlogCategories->getLastId()));
		break;
	case 'getcategories':
		$BlogCategories->get();
		echo json_encode(array('Status'=>'ok', 'Results'=>$BlogCategories->data()));
		break;
	case 'findcategory':
		$BlogCategories->find(Input::get('ID'));
		echo json_encode(array('Status'=>'ok', 'Result'=>$BlogCategories->data()));
		break;
	case 'deletecategory':
		if(!$User->logged() && $User->data()->idtype != 1) die(json_encode(array('Status'=>'fail')));
		$BlogCategories->delete();
		echo json_encode(array('Status'=>'ok'));
		break;
	case 'reordercategory':
		$BlogCategories->reorder();
		echo json_encode(array('Status'=>'ok'));
		break;

	default:
		echo json_encode(array('Status'=>'fail'));
		break;

}