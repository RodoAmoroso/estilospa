<?php 

$User = new User();
if(Cookie::exists(Config::get('cookie/cookie_name')) && !Session::exists(Config::get('session/session_name'))){
	$hash = Cookie::get(Config::get('cookie/cookie_name'));
	$hashCheck = DB::getInstance()->get('sessions', array('hash','=',$hash));
	if($hashCheck->count()){
		$User = new User($hashCheck->first()->iduser);
		$User->login();
	}
}

if(!$User->logged()) Redirect::to('login#panel');
if($User->data()->idtype != 3) Redirect::to('restricted');

$_userdata = $User->data();
//show_array($_userdata);
