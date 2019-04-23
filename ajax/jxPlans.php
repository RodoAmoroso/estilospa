<?php

require_once '../config.php';

if(!Input::exists()){
	die(json_encode(array('Status'=>'fail')));
}

$Plans = new Plans();

switch (Input::get('Mode')) {

	///////////////////// GROUPS ///////////////////////////	
	case 'save':
		if(!$User->logged() || $User->data()->idtype != 1) die(json_encode(array('Status'=>'fail')));
		$Plans->save();
		echo json_encode(array('Status'=>'ok','ID'=>$Plans->getLastId()));
		break;

	case 'get':
		$Plans->get();
		echo json_encode(array('Status'=>'ok','Results'=>$Plans->data()));
		break;

	case 'delete':
		if(!$User->logged() || $User->data()->idtype != 1) die(json_encode(array('Status'=>'fail')));		
		$Plans->delete();
		echo json_encode(array('Status'=>'ok'));
		break;

	case 'find':
		$Plans->find(Input::get('ID'));
		echo json_encode(array('Status'=>'ok', 'Result'=>$Plans->data()));
		break;
	
	default:
		echo json_encode(array('Status'=>'fail'));
		break;
}