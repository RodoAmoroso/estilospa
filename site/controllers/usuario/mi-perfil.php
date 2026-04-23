<?php 

if(!$User->logged()) Redirect::to('home');

$_arrjs[] = ['folder'=>'lib/','script'=>'upfile'];