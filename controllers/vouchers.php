<?php 

$_VOUCHERS = new Vouchers();

if(!$_VOUCHERS->find($_IDSECTION)) Redirect::javascript('home');
$voucherdata = $_VOUCHERS->data();
$_VOUCHERS->getpromos($_IDSECTION);