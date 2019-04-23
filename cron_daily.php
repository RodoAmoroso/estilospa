<?php 

require 'config.php';

/// SEND ALERTS - QUALIFY AND UPDATE STATUS ///
$Notifications = new Notifications();
$Notifications->range = 7;
$Notifications->getunrated();
$Notifications->getunstated();
$Notifications->range = 14;
$Notifications->getunrated();
$Notifications->getunstated();
$Notifications->range = 21;
$Notifications->getunrated();
$Notifications->getunstated();

//// RENEW TOKENS ///
$MPConfig = new MPConfig();
$MPConfig->renewtoken();
