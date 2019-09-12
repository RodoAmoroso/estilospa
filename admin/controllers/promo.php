<?php 

$Promos = new Promos();
$Promos->find($_subsection);
$_promodata = $Promos->data();

$MPConfig = new MPConfig();
if($_promodata) $MPConfig->find($_promodata->idclient);
$mp_client = $MPConfig->data();

$_arrjs[] = ['folder'=>'lib/','script'=>'jquery.bootstrap-duallistbox'];
$_arrjs[] = ['folder'=>'lib/','script'=>'upfile'];

$_arrcss[] = ['folder'=>'lib/','style'=>'bootstrap-duallistbox.min','rel'=>'stylesheet'];