<?php 

require_once '../config.php';

///////////////////////////////////////////////////////////////
$_uri = explode('/',Input::get('uri'));
$_section = isset($_uri[0]) && !empty($_uri[0]) ? $_uri[0] : 'dashboard';
$_subsection = isset($_uri[1]) && !empty($_uri[1]) ? $_uri[1] : '';
$_idsection = isset($_uri[2]) && !empty($_uri[2]) ? $_uri[2] : '';
///////////////////////////////////////////////////////////////

Redirect::$root = ROOT;
require 'controllers/main.php';

View::$scope = Config::get('paths/panel');
View::$root = PANEL;

$_controllerpath = View::loader('php','controllers');
if($_controllerpath) include $_controllerpath;


$_URLHEAD =  ROOT.(!empty($_section) ? $_section : '').(!empty($_subsection) ? '/'.$_subsection : '').(!empty($_idsection) ? '/'.$_idsection : '');

require 'views/head.php';
$_sectionpath = View::loader('php','views');
$nomenu = ['404','restricted'];


if($_sectionpath){
	if(!in_array($_section,$nomenu)){
		include '../site/views/header.php';
		include 'views/mainmenu.php';
		include $_sectionpath;
		require 'views/footer.php';		
	}else{
		include $_sectionpath;		
	}
}else{
	include  '../site/views/404.php';
}

require 'views/scripts.php';
