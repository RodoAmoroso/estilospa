<?php 

require_once '../config.php';

if(!Input::exists()) die(json_encode(array('Status'=>'fail')));

$_CLIENTTYPES = new ClientTypes();

switch (Input::get('Mode')) {

	///////////////////// GROUPS ///////////////////////////	
	case 'save':
		if(!$_USER->logged() || $_USER->data()->idtype != 1) die(json_encode(array('Status'=>'fail')));
		$_CLIENTTYPES->save();
		echo json_encode(array('Status'=>'ok','ID'=>$_CLIENTTYPES->getLastId()));
		break;

	case 'get':
		$_CLIENTTYPES->get();
		echo json_encode(array('Status'=>'ok','Result'=>$_CLIENTTYPES->data()));
		break;

	case 'delete':
		if(!$_USER->logged() || $_USER->data()->idtype != 1) die(json_encode(array('Status'=>'fail')));		
		$_CLIENTTYPES->delete();
		echo json_encode(array('Status'=>'ok'));
		break;

	case 'find':
		$_CLIENTTYPES->find(Input::get('ID'));
		echo json_encode(array('Status'=>'ok', 'Result'=>$_CLIENTTYPES->data()));
		break;

	case 'reorder':
		$_CLIENTTYPES->reorder();
		echo json_encode(array('Status'=>'ok'));
		break;
	
	default:
		echo json_encode(array('Status'=>'fail'));
		break;
}