<?php

/*$back_url = isset($_REQUEST['promourl']) ? $_REQUEST['promourl'] : ROOT;
$amount = isset($_REQUEST['amount']) ? $_REQUEST['amount'] : 0;*/
$status = $_subsection;
$hash = $_idsection;

$Sales = new Sales();
$Sales->find_gift($hash);
$giftdata = $Sales->data();


$Sales->find_temp($hash);
$sales_data = $Sales->data();
///echo_json($sales_data);

$client = false;
if($sales_data){
	$Clients = new Clients;
	$Clients->find($sales_data->idclient);
	$client = $Clients->data();
}