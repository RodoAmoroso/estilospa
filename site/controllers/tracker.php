<?php 


if(empty($_subsection) || !isset($_REQUEST['redirect'])) Redirect::to('home');

list($clientid,$event) = explode('-',$_subsection);

$Stats = new Stats();
$Stats->add_tracker($clientid,$event);

Redirect::to($_REQUEST['redirect'],true);
