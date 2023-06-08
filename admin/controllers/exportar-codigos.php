<?php

$voucherid = Input::get('id','int');
$Vouchers = new Vouchers;
if(!$Vouchers->find(Input::get('id','int'))) Redirect::to('404');
$voucher = $Vouchers->data();
$Vouchers->getcodes($voucherid);
$codes = $Vouchers->data();


$fp = fopen('php://memory', 'w');
fprintf($fp, chr(0xEF).chr(0xBB).chr(0xBF));
foreach($codes as $code){
	fputcsv($fp,[$code->code],';');
}
fseek($fp, 0);
$excelfile= "codigos_".Permalink($voucher->name).'_'.$voucher->id.".xls";
header('Content-Type: text/csv');
header('Content-Disposition: attachment; filename="'.$excelfile.'";');
fpassthru($fp);
exit;