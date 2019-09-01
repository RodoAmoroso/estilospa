<?php 

$Sales = new Sales();
$Promos = new Promos();
$Stores = new Stores();



if(!$Sales->find_gift($_subsection)) Redirect::to('404');
$gift = $Sales->data();

if(!$Sales->find($gift->hash)) Redirect::to('404');
$sale = $Sales->data();


//// log user ???
if(!$User->logged()){
	$User = new User($gift->from_user->id);
	$_userdata = $User->data();
}


$datetime = new DateTime($gift->added);
$gift->added = $datetime->format('d/m/Y H:i');

$Promos->find($gift->promoid);
$promo = $Promos->data();

$Stores->get($promo->idclient);
$stores = $Stores->data();

$gift->promo = $promo;
$gift->sale = $sale;
$gift->stores = $stores[0];

//show_array($gift->stores);
