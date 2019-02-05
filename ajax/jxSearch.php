<?php 

require_once '../config.php';

if(!Input::exists()) die(json_encode(array('Status'=>'fail')));

$_SEARCH = new Search();
$_SEARCH->keywords = Input::get('Keywords');
$_SEARCH->searchmixed = Input::get('SearchMixed');
$_SEARCH->limit = '0,10';

switch (Input::get('Mode')):
	case 'locations':
		$_SEARCH->locations();
		echo json_encode(array('Status'=>'ok', 'Results'=>$_SEARCH->data()));		
		break;

	case 'main':
		$_SEARCH->main();
		echo json_encode(array('Status'=>'ok', 'Results'=>$_SEARCH->data()));		
		break;
	
	default:
		echo json_encode(array('Status'=>'fail'));
		break;
endswitch;