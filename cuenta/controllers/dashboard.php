<?php 

$_promos = new Promos();
$_clients = new Clients();
$_messages = new Messages();


$_clients->getassoc($_USER->data()->id);
if(!$_clients->data()) Redirect::to('views/404');

$_promos->limit = '0,5';
$_promos->idclient = $_clients->data()->id;

$_messages->limit = '0,10';
$_messages->idclient = $_clients->data()->id;
$_messages->get();