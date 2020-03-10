<?php

require_once PATH.'vendor/autoload.php';


$MPConfig = new MPConfig();

MercadoPago\SDK::setAccessToken($MPConfig->access_token);

$payment_info = MercadoPago\Payment::find_by_id($_subsection);


///show_array($payment_info);