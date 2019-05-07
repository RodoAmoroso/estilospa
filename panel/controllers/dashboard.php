<?php 

$Promos = new Promos();
$Clients = new Clients();
//$_messages = new Messages();


$Clients->getassoc($User->data()->id);
if(!$Clients->data()) Redirect::to('404');

$Promos->limit = '0,5';
$Promos->idclient = $Clients->data()->id;

$_stats = new Stats();
$top_promos = $_stats->get_top_promos($_userdata->idclient);
$top_promos_questions = $_stats->get_top_promos_questions($_userdata->idclient);

$Questions = new Questions();
$Questions->limit = 10;
$questions = $Questions->get_unanswered($_userdata->idclient);

//show_array($questions);

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