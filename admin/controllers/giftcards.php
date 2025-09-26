<?php

$Experiences = new Experiences();
$experiences = $Experiences->get();


$_arrjs[] = ['folder'=>'lib/ckeditor/','script'=>'ckeditor'];
$_arrjs[] = ['folder'=>'lib/ckeditor/adapters/','script'=>'jquery'];


$_arrcss[] = ['folder'=>'lib/','style'=>'datatables.min'];
$_arrjs[] = ['folder'=>'lib/','script'=>'datatables.min'];

$_arrjs[] = ['folder'=>'classes/','script'=>'crud'];
$_arrjs[] = ['folder'=>'classes/','script'=>'tables'];

$_arrjs[] = ['folder'=>'classes/','script'=>'upfile'];