<?php

/*$back_url = isset($_REQUEST['promourl']) ? $_REQUEST['promourl'] : ROOT;
$amount = isset($_REQUEST['amount']) ? $_REQUEST['amount'] : 0;*/
$status = $_subsection;
$hash = $_idsection;

$Sales = new Sales();
$Sales->find_gift($hash);
$giftdata = $Sales->data();


$Sales->find_temp($hash);
$sale_temp = $Sales->data();

$Sales->find($hash);
$sale = $Sales->data();
///echo_json($sale);
/*$client = false;
if($sale_temp){
	$Clients = new Clients;
	$Clients->find($sale_temp->idclient);
	$client = $Clients->data();
}*/
Cookie::delete('sale_hash');