<?php

require_once '../config.php';
header("Content-Type: application/json; charset=utf-8", true);

$User = new User();
$Blog = new Blog();

if(!Input::check(Input::get('required'))) die(Responses::response('fail'));

switch($_action){

	default:
		echo Responses::response('fail');
		break;

}