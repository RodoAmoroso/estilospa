<?php

$Sales = new Sales();

if(!$User->logged()) Redirect::to('login#compra-descarga-voucher/'.$_subsection);

if(!$voucher = $Sales->find_voucher($_subsection)) Redirect::to('404');


if(!$Sales->find($voucher->saleid)) Redirect::to('404');
$sale = $Sales->data();

if($_userdata->idtype != 1 && $_userdata->idclient != $sale->idclient && $sale->iduser != $_userdata->id) Redirect::to('restricted');


$SalesVouchers = new SalesVouchers();
$SalesVouchers->add_download($voucher->id);
if(!$SalesVouchers->voucher($voucher)) Redirect::to('404');
