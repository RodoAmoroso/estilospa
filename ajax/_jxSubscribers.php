<?php 

require_once '../config.php';
$Subscribers = new Subscribers();

ini_set('max_input_vars',5000);

if(!Input::exists()) die(json_encode(array('status'=>'fail')));


switch (Input::get('mode')){

	case 'get':
		if(!$User->logged() && $User->data()->idtype != 1) die(json_encode(array('status'=>'restricted')));
		$Subscribers->limit = '0,500';
		$Subscribers->keywords = Input::get('keywords');
		$data = $Subscribers->get();
		echo json_encode(array('status'=>'ok','results'=>$data));
		break;

	case 'delete':
		if(!$User->logged() && $User->data()->idtype != 1) die(json_encode(array('status'=>'restricted')));
		if($Subscribers->delete(Input::get('id'))) die(json_encode(array('status'=>'fail')));;
		echo json_encode(array('status'=>'ok'));
		break;

	default:
		echo json_encode(array('status'=>'fail','message'=>'Faltan parámetros'));
		break;

}
