<?php

require_once PATH.'vendor/autoload.php';


$MPConfig = new MPConfig;

$payment_info = $MPConfig->find_payment($_subsection);


///show_array($payment_info);