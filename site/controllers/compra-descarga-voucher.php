<?php 

$Sales = new Sales();

if(!$User->logged()) Redirect::to('login#compra-descarga-voucher/'.$_subsection);

if(!$voucher = $Sales->find_voucher($_subsection)) Redirect::to('404');

$SalesVouchers = new SalesVouchers();

$SalesVouchers->add_download($voucher->id);

if(!$SalesVouchers->voucher($voucher)) Redirect::to('404');
