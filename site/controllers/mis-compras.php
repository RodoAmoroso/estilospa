<?php 

if(!$User->logged()) Redirect::to('home');

$Sales = new Sales();
$Sales->iduser = $User->data()->id;
