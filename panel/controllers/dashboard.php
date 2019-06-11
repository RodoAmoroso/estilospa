<?php 

$Promos = new Promos();
$Clients = new Clients();
//$_messages = new Messages();


$Clients->getassoc($User->data()->id);
if(!$Clients->data()) Redirect::to('404');

$Promos->limit = '0,5';
$Promos->idclient = $Clients->data()->id;

///  STATS ////
$Stats = new Stats();
$top_promos = $Stats->get_top_promos($_userdata->idclient);
$top_promos_questions = $Stats->get_top_promos_questions($_userdata->idclient);

$total_views = $Stats->get_total_client_views($_userdata->idclient);
$rating = $Clients->rating($_userdata->idclient);

$total_favs = $Stats->get_total_client_favs($_userdata->idclient);
$total_events = $Stats->get_total_client_events($_userdata->idclient);
$total_sales = $Stats->get_total_client_sales($_userdata->idclient);
$total_reservations = $Stats->get_total_client_reservations($_userdata->idclient);
$total_questions = $Stats->get_total_client_questions($_userdata->idclient);
//show_array($total_events);



/// RESERVATIONS ///
//$Reservations = new Reservations();
//$Reservations->limit = 10;
//$Reservations->sort = 'r.book_date ASC';
//$Reservations->status = 1;
///$Reservations->from = date('Y-m-d 00:00:00');
///$today = new DateTime();
///$today->modify('+6 month');
///$Reservations->to = $today->format('Y-m-d 00:00:00');
//$_reservations = $Reservations->get($_userdata->idclient);

//show_array();

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