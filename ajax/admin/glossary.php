<?php

$User = new User();
$Glossary = new Glossary();

if(!Input::check(Input::get('required'))) die(Responses::response('fail'));
if(!$User->logged() || $User->data()->idtype != 1) die(Responses::response('restricted'));

switch($_action){


	case 'save':
		
		$Glossary->save();
		echo Responses::response('ok','',array('id'=>$Glossary->getLastId()));
		break;

	case 'get':
		$glossary = array();
		if(Input::get('glossaryid')){
			$Glossary->find(Input::get('glossaryid'));
			$glossary[] = $Glossary->data();
		}else{
			$Glossary->get();
			$glossary = $Glossary->data();
		}
		echo Responses::response('ok','',array('results'=>$glossary));
		break;

	case 'delete':
		$Glossary->delete();
		echo Responses::response('ok');
		break;

	case 'find':
		$Glossary->find(Input::get('ID'));
		echo Responses::response('ok','',array('result'=>$Glossary->data()));
		break;
	
	case 'reorder':
		$Glossary->reorder();
		echo Responses::response('ok');
		break;

	case 'upimage':
		
		$Folder = '../'.Input::get('folder');	
		$upfile = new File($_FILES['file'],$Folder);
		$upfile->MoveFile();		
		$file = $upfile->Resize(array(array(1280,720,'')), '', false);
		echo json_encode($file);
		break;

	case 'upheader':
		$Folder = '../'.Input::get('folder');	
		$upfile = new File($_FILES['file'],$Folder);
		$upfile->MoveFile();		
		$file = $upfile->Resize(array(array(1280,720,'')), '', false);
		echo json_encode($file);
		break;



	default:
		echo Responses::response('fail');
		break;


}