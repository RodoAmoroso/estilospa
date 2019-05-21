<?php 

$Promos = new Promos();
$Clients = new Clients();
$Notifications = new Notifications();

$Clients->sort = 'date';
$Clients->limit = '0,10';
$Clients->get();

$Promos->limit = '0,10';

$Notifications->limit = '0,20';
$_notifications = $Notifications->get_log();


$Questions = new Questions();
$Questions->limit = 15;
//$Questions->filters = [['clients'=>$_userdata->idclient]];
$questions = $Questions->get_unanswered();

$questions_responses = $Questions->get_unanswered(null,true);
///show_array($questions_responses);

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