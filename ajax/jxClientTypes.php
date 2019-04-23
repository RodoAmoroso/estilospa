<?php 

require_once '../config.php';

if(!Input::exists()) die(json_encode(array('Status'=>'fail')));

$ClientTypes = new ClientTypes();

switch (Input::get('Mode')) {

	///////////////////// GROUPS ///////////////////////////	
	case 'save':
		if(!$User->logged() || $User->data()->idtype != 1) die(json_encode(array('Status'=>'fail')));
		$ClientTypes->save();
		echo json_encode(array('Status'=>'ok','ID'=>$ClientTypes->getLastId()));
		break;

	case 'get':
		$ClientTypes->get();
		echo json_encode(array('Status'=>'ok','Result'=>$ClientTypes->data()));
		break;

	case 'delete':
		if(!$User->logged() || $User->data()->idtype != 1) die(json_encode(array('Status'=>'fail')));		
		$ClientTypes->delete();
		echo json_encode(array('Status'=>'ok'));
		break;

	case 'find':
		$ClientTypes->find(Input::get('ID'));
		echo json_encode(array('Status'=>'ok', 'Result'=>$ClientTypes->data()));
		break;

	case 'reorder':
		$ClientTypes->reorder();
		echo json_encode(array('Status'=>'ok'));
		break;
	
	default:
		echo json_encode(array('Status'=>'fail'));
		break;
}