<?php 

$Sales = new Sales();
$Promos = new Promos();
$Stores = new Stores();


$_arrjs[] = ['folder'=>'lib/','script'=>'upfile'];


if(!$User->logged()) Redirect::to('login#compra/'.$_subsection);

if(!$Sales->find($_subsection)) Redirect::to('404');
$sale = $Sales->data();

if($sale->iduser != $_userdata->id) Redirect::to('restricted');



$Promos->find($sale->idpromo);
$promo = $Promos->data();

$Stores->get($promo->idclient);
$stores = $Stores->data();


$vouchers = $Sales->get_vouchers($sale->id);
