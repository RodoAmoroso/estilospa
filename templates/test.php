<?php 

require '../config.php';

//$Mailing = new Mailing();
///$Templ = new Mailing();

//$obj->name = 'Rodo';
//$obj->email = 'rodosoft@gmail.com';
//$obj->id = 2;
//$obj->hash = 'fde2fd2313fdefeffefdcc';
/////$Mailing->register($obj);

$Promos = new Promos();
$Banners = new Banners();

$obj = new stdClass();
$Promos->exclude = 0;
$Promos->limit = '0,10';
$Promos->get();
$obj->promos = $Promos->data();

$Banners->visible = 1;
$Banners->type = 'main';
$Banners->sort = 'position';
$Banners->limit = '0,2';
$Banners->get();
$obj->banners = $Banners->data();


$template = Templates::template('newsletters/subscription',$obj);
echo Templates::template('email',$template);