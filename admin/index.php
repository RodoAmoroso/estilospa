<?php 

require_once '../config.php';

///////////////////////////////////////////////////////////////
$_SECTION = isset($_REQUEST['sct']) ? $_REQUEST['sct'] : 'inicio';
$_SECTION = !empty($_SECTION) ? $_SECTION : 'inicio';
$_SUBSECTION = isset($_REQUEST['subsct']) ? $_REQUEST['subsct'] : '';
///////////////////////////////////////////////////////////////

if(!$_USER->logged() || $_USER->data()->idtype != 1) Redirect::to('home');

if(file_exists('controllers/'.$_SECTION.'.php')) include 'controllers/'.$_SECTION.'.php';
$hidechat = true;
require '../head.php';
require '../header.php';
include 'mainmenu.php';
if(file_exists('views/'.$_SECTION.'.php')): include 'views/'.$_SECTION.'.php'; else: include '../views/404.php'; endif;
require 'footer.php';