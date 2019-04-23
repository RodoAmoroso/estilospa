<?php 

require_once '../config.php';

///////////////////////////////////////////////////////////////
$_section = isset($_REQUEST['sct']) ? $_REQUEST['sct'] : 'dashboard';
$_section = !empty($_section) ? $_section : 'dashboard';
$_subsection = isset($_REQUEST['subsct']) ? $_REQUEST['subsct'] : '';
///////////////////////////////////////////////////////////////

if(!$_USER->logged() || $_USER->data()->idtype != 3) Redirect::to('login#'.ROOT.'cuenta/'.$_section);


if(file_exists('controllers/'.$_section.'.php')) include 'controllers/'.$_section.'.php';
require '../head.php';
require '../header.php';
include 'mainmenu.php';
if(file_exists('views/'.$_section.'.php')): include 'views/'.$_section.'.php'; else: include '../views/404.php'; endif;
require 'footer.php';