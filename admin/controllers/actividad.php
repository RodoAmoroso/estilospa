<?php 

$Notifications = new Notifications();

$Notifications->limit = '0,100';
if(!empty(Input::get('type'))) $Notifications->filters = ['type'=>Input::get('type')];
$_notifications = $Notifications->get_log();

function set_type($reference){
	$output = '';
	switch ($reference) {
		case 'promo':
			$output = 'Promo';
			break;
		case 'sale':
			$output = 'Venta';
			break;
		case 'reservation':
			$output = 'Reserva';
			break;
		case 'question':
			$output = 'Pregunta';
			break;	
	}
	return $output;
}