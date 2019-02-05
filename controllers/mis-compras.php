<?php 

if(!$_USER->logged()) Redirect::to('Home');

$_SALES = new Sales();
$_SALES->iduser = $_USER->data()->id;
