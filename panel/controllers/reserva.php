<?php 

$Reservations = new Reservations();
if(!$_reservation = $Reservations->find($_subsection)) Redirect::to('panel/reservas');
if($_userdata->idclient != $_reservation->client->id) Redirect::to('restricted');

$_arrjs[] = ['folder'=>'site/','script'=>'reservations'];

$_today = new DateTime();

$_arrdays = array();
for($i=1; $i<=4; $i++){
	$_arrdays[] = array(
		'day'=>$_today->format('d'),
		'dayname'=>$_today->format('D'),
		'name'=>Dates::translateDays($_today->format('l'))
	);	
	$_today->modify('+1 day');
}



//show_array($_reservation->promo);