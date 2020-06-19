<?php

$_arrcss[] = ['folder'=>'lib/dataTables/','style'=>'datatables.min'];
//$_arrcss[] = ['folder'=>'lib/dataTables/','style'=>'datatables.bootstrap.min'];
$_arrjs[] = ['folder'=>'lib/dataTables/','script'=>'datatables.min'];


$HotSale = new HotSale;
$hotsale = $HotSale->get();