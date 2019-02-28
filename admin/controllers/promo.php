<?php 

$_promo = new Promos();
$_promo->find($_SUBSECTION);
$promodata = $_promo->data();
//show_array($promodata);

$_arrjs[] = ['folder'=>'js/lib/','script'=>'jquery.bootstrap-duallistbox'];
$_arrcss[] = ['folder'=>'css/','style'=>'bootstrap-duallistbox.min','rel'=>'stylesheet'];