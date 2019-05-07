<?php 

require_once '../config.php';

if(!Input::exists()) die(json_encode(array('Status'=>'fail')));

$Search = new Search();
$Search->keywords = Input::get('Keywords');
$Search->searchmixed = Input::get('SearchMixed');
$Search->limit = '0,10';

switch (Input::get('Mode')):
	case 'locations':
		$Search->locations();
		echo json_encode(array('Status'=>'ok', 'Results'=>$Search->data()));		
		break;

	case 'main':
		$Search->main();
		echo json_encode(array('Status'=>'ok', 'Results'=>$Search->data()));		
		break;
	
	default:
		echo json_encode(array('Status'=>'fail'));
		break;
endswitch;