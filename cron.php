<?php 

require_once 'config.php';


$minutos = 15;
$mailxhora = 100;
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

http_response_code(200);