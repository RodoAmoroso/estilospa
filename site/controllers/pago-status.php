<?php 

$status = $_subsection;
$back_url = isset($_REQUEST['promourl']) ? $_REQUEST['promourl'] : ROOT;
$amount = isset($_REQUEST['amount']) ? $_REQUEST['amount'] : 0;
$hash = Input::get('hash');

$Sales = new Sales();
$Sales->find_gift($hash);
$giftdata = $Sales->data();


$Sales->find_temp($hash);
$sales_data = $Sales->data();
