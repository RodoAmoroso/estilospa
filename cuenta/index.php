<?php 

require_once '../config.php';

///////////////////////////////////////////////////////////////
$_SECTION = isset($_REQUEST['sct']) ? $_REQUEST['sct'] : 'dashboard';
$_SECTION = !empty($_SECTION) ? $_SECTION : 'dashboard';
$_SUBSECTION = isset($_REQUEST['subsct']) ? $_REQUEST['subsct'] : '';
///////////////////////////////////////////////////////////////

if(!$_USER->logged() || $_USER->data()->idtype != 3) Redirect::to('login#'.ROOTPATH.'cuenta/'.$_SECTION);


if(file_exists('controllers/'.$_SECTION.'.php')) include 'controllers/'.$_SECTION.'.php';
require '../head.php';
require '../header.php';
include 'mainmenu.php';
if(file_exists('views/'.$_SECTION.'.php')): include 'views/'.$_SECTION.'.php'; else: include '../views/404.php'; endif;
require 'footer.php';