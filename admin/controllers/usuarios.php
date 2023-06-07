<?php

$provinces = DB::getInstance()->get('provinces',array('id','!=',0));
$user_types = DB::getInstance()->get('usertypes',array('id','!=',0));
$clients = new Clients();
$clients->get();

$_arrjs[] = ['folder'=>'lib/','script'=>'upfile'];

$_arrcss[] = ['folder'=>'lib/dataTables/','style'=>'datatables.min'];
$_arrjs[] = ['folder'=>'lib/dataTables/','script'=>'datatables.min'];

$_arrjs[] = ['folder'=>'','script'=>'tables'];

/*$Users = new Users;
$Users->limit = '0,10000';
$Users->sort = 'logged';
$users = $Users->get();*/
$users = false;
