<?php

$Experiences = new Experiences();
$experiences = $Experiences->get();


$_arrjs[] = ['folder'=>'classes/','script'=>'upfile'];

$_arrcss[] = ['folder'=>'lib/dataTables/','style'=>'datatables.min'];
$_arrjs[] = ['folder'=>'lib/dataTables/','script'=>'datatables.min'];

$_arrjs[] = ['folder'=>'classes/','script'=>'crud'];
$_arrjs[] = ['folder'=>'classes/','script'=>'tables'];

$_arrjs[] = ['folder'=>'lib/ckeditor/adapters/','script'=>'jquery'];
$_arrjs[] = ['folder'=>'lib/ckeditor/','script'=>'ckeditor'];