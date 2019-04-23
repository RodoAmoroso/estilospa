<?php 

$Vouchers = new Vouchers();

if(!$Vouchers->find($_idsection)) Redirect::javascript('home');
$voucherdata = $Vouchers->data();
$Vouchers->getpromos($_idsection);