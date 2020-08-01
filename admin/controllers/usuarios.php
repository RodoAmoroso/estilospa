<?php

$_arrjs[] = ['folder'=>'lib/','script'=>'upfile'];

$_arrcss[] = ['folder'=>'lib/dataTables/','style'=>'datatables.min'];
$_arrjs[] = ['folder'=>'lib/dataTables/','script'=>'datatables.min'];

$Users = new Users;
$Users->limit = '0,10000';
$Users->sort = 'logged';
$users = $Users->get();