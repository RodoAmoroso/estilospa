<?php

$Blog = new Blog();

if(!Input::check(Input::get('required'))) die(Responses::response('fail'));

switch($_action){

	default:
		echo Responses::response('fail');
		break;

}