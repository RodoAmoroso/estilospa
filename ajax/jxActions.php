<?php 

require_once '../config.php';

if(!Input::exists()){
	die(Jason::encode(array('Status'=>'fail')));
}
switch (Input::get('Mode')) {
	case 'add':
		$stats = DB::getInstance()->insert('stats',array(
			'st_device'=>'desktop',
			'st_ip'=>'127.0.0.1',
			'st_added'=>date('Y-m-d H:i:s')
		));		
		die(Jason::encode(array('Status'=>'ok')));
	break;
	
	default:
		die(Jason::encode(array('Status'=>'fail')));
	break;
}
