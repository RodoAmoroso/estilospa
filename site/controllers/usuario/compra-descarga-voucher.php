<?php

$Sales = new Sales();
$SalesVouchers = new SalesVouchers();

if(!$User->logged()) Redirect::to('login#compra-descarga-voucher/'.$_idsection);

if(!$voucher = $SalesVouchers->find($_idsection)) Redirect::to('404');

if(!$sale = $Sales->find($voucher->saleid)) Redirect::to('404');
if($sale->payment_status!='approved') Redirect::to('404');

if($_userdata->idtype != 1 && $_userdata->idclient != $sale->idclient && $sale->iduser != $_userdata->id) Redirect::to('restricted');


$SalesVouchers->add_download($voucher->id);
if(!$SalesVouchers->voucher($voucher,$sale)) Redirect::to('404');
