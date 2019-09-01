<?php 

$status = $_subsection;
$back_url = isset($_REQUEST['promourl']) ? $_REQUEST['promourl'] : ROOT;
$amount = isset($_REQUEST['amount']) ? $_REQUEST['amount'] : 0;

$Gift = new Sales();
$Gift->find_gift(Input::get('hash'));