<?php

require_once 'config.php';

$minutos = 30;
$mailxhora = 50;
$limite = floor($minutos*$mailxhora/60);

$Mailing = new Mailing();
$Newsletters = new Newsletters();

if($queue = $Newsletters->get($limite)){
	foreach($queue as $k=>$q){
		if($Mailing->newsletters($q)){
			$Newsletters->delete_queue($q);
		}
	}
}


$Cron = new Cron;
$Cron->add_log('newsletters');

http_response_code(200);