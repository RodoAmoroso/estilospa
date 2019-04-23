<?php 

require_once '../config.php';

///////////////////////////////////////////////////////////////
$_section = isset($_REQUEST['sct']) ? $_REQUEST['sct'] : 'inicio';
$_section = !empty($_section) ? $_section : 'inicio';
$_subsection = isset($_REQUEST['subsct']) ? $_REQUEST['subsct'] : '';
///////////////////////////////////////////////////////////////

if(!$_USER->logged() || $_USER->data()->idtype != 1) Redirect::to('home');

include 'controllers/main.php';
if(file_exists('controllers/'.$_section.'.php')) include 'controllers/'.$_section.'.php';
$hidechat = true;
require '../head.php';
require '../header.php';
include 'mainmenu.php';
if(file_exists('views/'.$_section.'.php')): include 'views/'.$_section.'.php'; else: include '../views/404.php'; endif;
require 'footer.php';