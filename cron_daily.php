<?php 

require 'config.php';

$Mailing = new Mailing();

/// SEND ALERTS - QUALIFY AND UPDATE STATUS ///
$Notifications = new Notifications();

$Notifications->range = 2;
$Notifications->get_unstated();
$Notifications->get_unrated();

$Notifications->range = 14;
$Notifications->get_unrated();
$Notifications->get_unstated();

$Notifications->range = 21;
$Notifications->get_unrated();
$Notifications->get_unstated();


//// RENEW TOKENS ///
$MPConfig = new MPConfig();
$MPConfig->renewtoken();


http_response_code(200);