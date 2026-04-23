<?php 

if(!$User->logged()) Redirect::to('home');

$Sales = new Sales();
$Sales->filters = [
	'user'=>$_userdata->id
];
$sales = $Sales->get();

$SalesComments = new SalesComments;