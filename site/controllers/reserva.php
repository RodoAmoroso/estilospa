<?php 

if(!$User->logged()) Redirect::to('login#'.ROOT.'reserva/'.$_subsection.'/'.$_idsection);

$action = $_subsection;
$reservationid = intval($_idsection);

$Reservations = new Reservations();
$Mailing = new Mailing();
if(!$_reservation = $Reservations->find($reservationid)) Redirect::to('404');


if($_reservation->status != 1 && $action=='confirmar'){
	$Reservations->confirm($reservationid);
	$Mailing->confirm_reservation_user($reservationid,$_userdata->id);
	$_reservation->status = 1;
}


if($_userdata->id != $_reservation->userid) Redirect::to('restricted');
