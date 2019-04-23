<?php 

require_once '../config.php';

///////////////////////////////////////////////////////////////
$_uri = explode('/',Input::get('uri'));
$_scope = !empty($_uri[0]) ? $_uri[0] : '';
$_controller = isset($_uri[1]) && !empty($_uri[1]) ? $_uri[1] : '';
$_action = isset($_uri[2]) && !empty($_uri[2]) ? $_uri[2] : '';
$_id = isset($_uri[3]) && !empty($_uri[3]) ? $_uri[3] : '';

include $_scope.'/'.$_controller.'.php';