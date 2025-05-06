<?php

$Sales = new Sales();
$Promos = new Promos();
$Stores = new Stores();


$_arrjs[] = ['folder'=>'lib/','script'=>'upfile'];


if(!$User->logged()) Redirect::to('login#compra/'.$_subsection);

if(!$Sales->find($_subsection)) Redirect::to('404');
$sale = $Sales->data();

if($sale->iduser != $_userdata->id) Redirect::to('restricted');
if($sale->collection_status != 'approved') Redirect::to('404');


if(!$Promos->find($sale->idpromo)) Redirect::to('404');
$promo = $Promos->data();

$Stores->get($promo->idclient);
$stores = $Stores->data();


$vouchers = $Sales->get_vouchers($sale->id);
//echo_json($sale);