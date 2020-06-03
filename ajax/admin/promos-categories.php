<?php

require_once '../config.php';
header("Content-Type: application/json; charset=utf-8", true);

$User = new User;
$PromosCategories = new PromosCategories;

if(!Input::check(Input::get('required'))) die(Responses::response('fail'));
if(!$User->logged() || $User->data()->idtype != 1) die(Responses::response('restricted'));

switch($_action){

	case 'image':
		$Folder = PATH.Input::get('folder');
		$upfile = new File($_FILES['file'],$Folder);
		$upfile->MoveFile();
		$file = $upfile->Resize(array(array(960,960,'-o'),array(400,400,'-t')),'',false);
		echo json_encode($file);
		break;

	case 'save':
		if(!$PromosCategories->save()) die(Responses::response('fail'));
		echo Responses::response('ok');
		break;

	case 'get':
		$PromosCategories->filters = Input::get('filters');
		$categories = $PromosCategories->get();
		echo Responses::response('ok','',array('results'=>$categories));
		break;

	case 'find':
		if(!$category = $PromosCategories->find(Input::get('id'))) die(Responses::response('fail'));
		echo Responses::response('ok','',array('result'=>$category));
		break;

	case 'delete':
		$PromosCategories->delete(Input::get('id'));
		echo Responses::response('ok');
		break;

	default:
		echo Responses::response('fail');
		break;

}