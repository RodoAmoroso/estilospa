<?php 

require_once '../config.php';
$_subscribers = new Subscribers();

ini_set('max_input_vars',5000);

if(!Input::exists()) die(json_encode(array('status'=>'fail')));


switch (Input::get('mode')){

	case 'get':
		if(!$_USER->logged() && $_USER->data()->idtype != 1) die(json_encode(array('status'=>'restricted')));
		$_subscribers->limit = '0,500';
		$_subscribers->keywords = Input::get('keywords');
		$data = $_subscribers->get();
		echo json_encode(array('status'=>'ok','results'=>$data));
		break;

	case 'delete':
		if(!$_USER->logged() && $_USER->data()->idtype != 1) die(json_encode(array('status'=>'restricted')));
		if($_subscribers->delete(Input::get('id'))) die(json_encode(array('status'=>'fail')));;
		echo json_encode(array('status'=>'ok'));
		break;

	default:
		echo json_encode(array('status'=>'fail','message'=>'Faltan parámetros'));
		break;

}
