<?php 

require_once 'config.php';
///////////////////////////////////////////////////////////////
$_SECTION = isset($_REQUEST['sct']) ? $_REQUEST['sct'] : 'home';
$_SECTION = !empty($_SECTION) ? $_SECTION : 'home';
$_SUBSECTION = isset($_REQUEST['subsct']) ? $_REQUEST['subsct'] : '';
$_IDSECTION = isset($_REQUEST['ids']) ? $_REQUEST['ids'] : '';
///////////////////////////////////////////////////////////////
if($_USER->logged()){
	if($_SECTION == 'login' || $_SECTION == 'registro'){
		Redirect::to('home');
	}
}else{
	if($_SECTION == 'perfil'){
		Redirect::to('home');
	}
}

require 'controllers/main.php';
if(file_exists('controllers/'.$_SECTION.'.php')) include 'controllers/'.$_SECTION.'.php';
$_URLHEAD =  ROOTPATH.(!empty($_SECTION) ? $_SECTION : '').(!empty($_SUBSECTION) ? '/'.$_SUBSECTION : '').(!empty($_IDSECTION) ? '/'.$_IDSECTION : '');
require 'head.php';
include 'header.php';
include 'mainmenu.php';
if(file_exists('views/'.$_SECTION.'.php')): include 'views/'.$_SECTION.'.php'; else: include 'views/404.php'; endif;
require 'footer.php';
