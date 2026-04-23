<?php

header("Content-Type: application/json; charset=utf-8", true);
require_once '../config.php';

///////////////////////////////////////////////////////////////
$_uri = explode('/',Input::get('uri'));
$_scope = !empty($_uri[0]) ? $_uri[0] : '';
$_controller = isset($_uri[1]) && !empty($_uri[1]) ? $_uri[1] : '';
$_action = isset($_uri[2]) && !empty($_uri[2]) ? $_uri[2] : '';
$_id = isset($_uri[3]) && !empty($_uri[3]) ? $_uri[3] : '';

if(Input::get('token') != Session::session_hashed()) die(Responses::response('restricted','',Input::get_all()));

$User = new User;
$_userdata=null;
if($User->logged()) $_userdata = $User->data();

if($_scope=='admin'){
	if(!$_userdata) die(Responses::response('restricted'));
	if($_userdata->idtype != 1) die(Responses::response('restricted'));
}

include $_scope.'/'.$_controller.'.php';