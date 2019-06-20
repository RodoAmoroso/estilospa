<?php 
require 'config.php';




$obj = new stdClass();
$content = Templates::template('reservations/notification-user',$obj);
$body = Templates::template('email',$content);

echo $body;