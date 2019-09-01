<?php 

$Sales = new Sales();

if(!$Sales->find_gift($_subsection)) Redirect::to('404');
$gift = $Sales->data();

if(!$Sales->find($gift->hash)) Redirect::to('404');
$sale = $Sales->data();



$Promos->find($gift->promoid);
$promo = $Promos->data();

$Stores->get($promo->idclient);
$stores = $Stores->data();


$gift->promo = $promo;
$gift->sale = $sale;
$gift->stores = $stores[0];



$SalesGift = new SalesGift();
$SalesGift->add_download($gift->id);
$SalesGift->get_voucher($gift);