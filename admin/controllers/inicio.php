<?php 

$_promos = new Promos();
$_clients = new Clients();
$_messages = new Messages();

$_clients->sort = 'date';
$_clients->limit = '0,5';
$_clients->get();

$_promos->limit = '0,5';

$_messages->limit = '0,10';
$_messages->get();