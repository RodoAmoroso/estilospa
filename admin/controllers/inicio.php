<?php 

$_promos = new Promos();
$_clients = new Clients();
$_messages = new Messages();

$_clients->sort = 'date';
$_clients->limit = '0,5';
$_clients->get();

$_promos->limit = '0,10';

$_messages->limit = '0,10';
$_messages->get();

function dif_labels($dif=''){
	switch ($dif) {
		case $dif<5:
			return 'danger';
			break;

		case $dif>=5 && $dif<10:
			return 'warning';
			break;
		
		default:
			return 'success';
			break;
	}
}