<?php 

$_arrjs[] = ['folder'=>'lib/fullcalendar/','script'=>'moment.min'];
$_arrjs[] = ['folder'=>'lib/fullcalendar/','script'=>'fullcalendar.min'];
$_arrjs[] = ['folder'=>'lib/fullcalendar/','script'=>'es'];
$_arrjs[] = ['folder'=>'site/','script'=>'reservations'];

$_arrcss[] = ['folder'=>'lib/','style'=>'fullcalendar.min'];
$_arrcss[] = ['folder'=>'lib/','style'=>'fullcalendar.print.min','media'=>'print'];

$Reservations = new Reservations();



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

//show_array($Reservations->find(10));