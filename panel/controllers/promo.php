<?php

$Promos = new Promos();
$Promos->find($_subsection);
$_promodata = $Promos->data();

if($_promodata){
	if($_userdata->idclient != $_promodata->idclient) Redirect::to('restricted');
}


$MPConfig = new MPConfig();
$MPConfig->find($_userdata->idclient);
$mp_client = $MPConfig->data();


$_arrjs[] = ['folder'=>'lib/','script'=>'jquery.bootstrap-duallistbox'];
$_arrjs[] = ['folder'=>'lib/','script'=>'upfile'];

$_arrcss[] = ['folder'=>'lib/','style'=>'bootstrap-duallistbox.min','rel'=>'stylesheet'];

$PromosCategories = new PromosCategories;
$categories = $PromosCategories->get();