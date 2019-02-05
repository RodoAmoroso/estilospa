<?php

require_once '../config.php';

if(!Input::exists()){
	die(json_encode(array('Status'=>'fail')));
}

$_BLOG = new Blog();
$_BLOGCATEGORIES = new BlogCategories();

switch (Input::get('Mode')) {
	
	case 'insertimage':
		if(!$_USER->logged() && $_USER->data()->idtype != 1) die(json_encode(array('Status'=>'fail')));
		$Folder = '../'.Input::get('Folder');	
		$upfile = new File($_FILES[0],$Folder);
		$upfile->MoveFile();
		$file = $upfile->Resize(array(array(1280,720,'')),'',false);
		echo json_encode($file);
		break;
	case 'upgallery':
		if(!$_USER->logged() && $_USER->data()->idtype != 1) die(json_encode(array('Status'=>'fail')));
		$Folder = '../'.Input::get('Folder');	
		$upfile = new File($_FILES[0],$Folder);
		$upfile->MoveFile();
		$file = $upfile->Resize(array(array(1280,720,'-o'),array(480,480,'-t')),'',false);
		echo json_encode($file);
		break;
	case 'save':
		if(!$_USER->logged() && $_USER->data()->idtype != 1) die(json_encode(array('Status'=>'fail')));
		$_BLOG->save();		
		echo json_encode(array('Status'=>'ok'));
		break;
	case 'get':
		$_BLOG->keywords = Input::get('Keywords');
		$_BLOG->idcategory = Input::get('IDCategory');
		$_BLOG->limit = '0,100';
		$_BLOG->searchmixed = intval(Input::get('SearchMixed'));
		$_BLOG->get();
		echo json_encode(array('Status'=>'ok', 'Results'=>$_BLOG->data()));		
		break;
	case 'find':
		$_BLOG->find(Input::get('ID'));
		echo json_encode(array('Status'=>'ok', 'Result'=>$_BLOG->data()));
		break;
	case 'delete':
		$_BLOG->delete();
		echo json_encode(array('Status'=>'ok'));
		break;

	///////////// CATEGORY ////////////////////////////////
	case 'savecategory':
		if(!$_USER->logged() && $_USER->data()->idtype != 1) die(json_encode(array('Status'=>'fail')));
		if(!Input::check(array('Name'))) die(json_encode(array('Status'=>'fail')));
		$_BLOGCATEGORIES->save();		
		echo json_encode(array('Status'=>'ok','ID'=>$_BLOGCATEGORIES->getLastId()));
		break;
	case 'getcategories':
		$_BLOGCATEGORIES->get();
		echo json_encode(array('Status'=>'ok', 'Results'=>$_BLOGCATEGORIES->data()));
		break;
	case 'findcategory':
		$_BLOGCATEGORIES->find(Input::get('ID'));
		echo json_encode(array('Status'=>'ok', 'Result'=>$_BLOGCATEGORIES->data()));
		break;
	case 'deletecategory':
		if(!$_USER->logged() && $_USER->data()->idtype != 1) die(json_encode(array('Status'=>'fail')));
		$_BLOGCATEGORIES->delete();
		echo json_encode(array('Status'=>'ok'));
		break;
	case 'reordercategory':
		$_BLOGCATEGORIES->reorder();
		echo json_encode(array('Status'=>'ok'));
		break;

	default:
		echo json_encode(array('Status'=>'fail'));
		break;

}