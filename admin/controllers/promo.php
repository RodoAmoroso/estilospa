<?php 

$Promos = new Promos();
$Promos->find($_subsection);
$_promodata = $Promos->data();
//show_array($promodata);

$_arrjs[] = ['folder'=>'lib/','script'=>'jquery.bootstrap-duallistbox'];
$_arrjs[] = ['folder'=>'lib/','script'=>'upfile'];

$_arrcss[] = ['folder'=>'lib/','style'=>'bootstrap-duallistbox.min','rel'=>'stylesheet'];