<?php

$Clients = new Clients;
$Clients->get();
$clients = $Clients->data();



$Users = new Users;
$Users->filters = ['has_questions'=>true];
if(Input::get('clients')){
	$Users->filters['has_questions_client'] = Input::get('clients');

}

$users = $Users->get();
//show_array($Users->core_query());