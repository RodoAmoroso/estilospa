<?php 

if(!$User->logged()) Redirect::to('home');

$Sales = new Sales();
$Sales->iduser = $User->data()->id;
$Sales->get();

$sale_data = $Sales->data();