<?php

require_once '../config.php';


$minutos = 30;
$mailxhora = 50;
$limite = floor($minutos*$mailxhora/60);

$Mailing = new Mailing();
$Notifications = new Notifications();


if($queue_n = $Notifications->get($limite)){
	foreach($queue_n as $k=>$q){
		if($Mailing->notifications($q)){
			$Notifications->delete($q);
		}
	}
}


$Cron = new Cron;
$Cron->add_log('notifications');

http_response_code(200);