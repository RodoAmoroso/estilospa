<?php

require_once '../config.php';

if(!Input::exists()){
	die(json_encode(array('Status'=>'fail')));
}

$_PLANS = new Plans();

switch (Input::get('Mode')) {

	///////////////////// GROUPS ///////////////////////////	
	case 'save':
		if(!$_USER->logged() || $_USER->data()->idtype != 1) die(json_encode(array('Status'=>'fail')));
		$_PLANS->save();
		echo json_encode(array('Status'=>'ok','ID'=>$_PLANS->getLastId()));
		break;

	case 'get':
		$_PLANS->get();
		echo json_encode(array('Status'=>'ok','Results'=>$_PLANS->data()));
		break;

	case 'delete':
		if(!$_USER->logged() || $_USER->data()->idtype != 1) die(json_encode(array('Status'=>'fail')));		
		$_PLANS->delete();
		echo json_encode(array('Status'=>'ok'));
		break;

	case 'find':
		$_PLANS->find(Input::get('ID'));
		echo json_encode(array('Status'=>'ok', 'Result'=>$_PLANS->data()));
		break;
	
	default:
		echo json_encode(array('Status'=>'fail'));
		break;
}