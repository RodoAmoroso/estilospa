<?php

if(!$User->logged()) Redirect::to('login#usuario/compra-experiencia/'.$_idsection);

$Sales = new Sales();
$SalesVouchers = new SalesVouchers();
$Promos = new Promos();
$Stores = new Stores();

if(!$sale = $Sales->find($_idsection)) Redirect::to('404');

if($sale->iduser != $_userdata->id) Redirect::to('restricted');
if($sale->payment_status != 'approved') Redirect::to('404');


if(!$Promos->find($sale->idpromo)) Redirect::to('404');
$promo = $Promos->data();

$Stores->get($promo->idclient);
$stores = $Stores->data();


$SalesVouchers->filters = ['sale'=>$sale->id];
$vouchers = $SalesVouchers->get();


$_arrjs[] = ['folder'=>'lib/','script'=>'upfile'];