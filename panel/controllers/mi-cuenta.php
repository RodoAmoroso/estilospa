<?php

$Sales = new Sales();
$Sales->idclient = $User->data()->idclient;
//show_array($User->data());

$Promos = new Promos;
$total_promos = $Promos->get_total($User->data()->idclient);

$_arrjs[] = ['folder'=>'lib/flot/','script'=>'jquery.flot.min'];
$_arrjs[] = ['folder'=>'lib/flot/','script'=>'jquery.flot.time.min'];
$_arrjs[] = ['folder'=>'lib/flot/','script'=>'jquery.flot.tooltip.min'];
$_arrjs[] = ['folder'=>'lib/flot/','script'=>'jquery.flot.stack.min'];
$_arrjs[] = ['folder'=>'lib/flot/','script'=>'jquery.flot.resize.min'];