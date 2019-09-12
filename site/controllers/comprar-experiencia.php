<?php 

$reservationid = $_subsection;

$Reservations = new Reservations();
if(!$reservation = $Reservations->find($reservationid)) Redirect::to('404');


$Reservations->reservations_sales($reservationid);
show_array($reservation);