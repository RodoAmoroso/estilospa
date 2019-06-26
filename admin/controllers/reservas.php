<?php 

$_arrcss[] = ['folder'=>'lib/dataTables/','style'=>'datatables.min'];
$_arrcss[] = ['folder'=>'lib/dataTables/','style'=>'datatables.bootstrap.min'];
$_arrjs[] = ['folder'=>'lib/dataTables/','script'=>'datatables.min'];

$Reservations = new Reservations();
$Reservations->excluded_days = false;
$Reservations->limit = 250;

$date = new DateTime();
$date->modify('-5 days');
$today = $date->format('Y-m-d H:i:s');
$today_formatted = $date->format('d/m/Y');

$date->modify('+1 month');
$next_month = $date->format('Y-m-d H:i:s');
$next_month_formatted = $date->format('d/m/Y');


$Reservations->from = empty(Input::get('date_from')) ? $today : Dates::convert_datetime(Input::get('date_from'),'Y-m-d H:i:s');
$Reservations->to = empty(Input::get('date_to')) ? $next_month : Dates::convert_datetime(Input::get('date_to'),'Y-m-d H:i:s');
$_reservations = $Reservations->get();

