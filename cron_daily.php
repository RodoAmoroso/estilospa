<?php 

require 'config.php';

/// SEND ALERTS - QUALIFY AND UPDATE STATUS ///
$_notifications = new Notifications();
$_notifications->range = 7;
$_notifications->getunrated();
$_notifications->getunstated();
$_notifications->range = 14;
$_notifications->getunrated();
$_notifications->getunstated();
$_notifications->range = 21;
$_notifications->getunrated();
$_notifications->getunstated();

//// RENEW TOKENS ///
$_mpconfig = new MPConfig();
$_mpconfig->renewtoken();
