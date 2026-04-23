<?php

$Promos = new Promos();
$Promos->find($_subsection);
$_promodata = $Promos->data();

$MPConfig = new MPConfig();
if($_promodata) $MPConfig->find($_promodata->idclient);
$mp_client = $MPConfig->data();

$PromosCategories = new PromosCategories;
$categories = $PromosCategories->get();

$MainCategories = new MainCategories;
$main_categories = $MainCategories->get();


$_arrjs[] = ['folder'=>'lib/','script'=>'jquery.bootstrap-duallistbox'];
$_arrjs[] = ['folder'=>'lib/','script'=>'upfile'];

$_arrcss[] = ['folder'=>'lib/','style'=>'bootstrap-duallistbox.min','rel'=>'stylesheet'];