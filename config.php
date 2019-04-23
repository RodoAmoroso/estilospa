<?php
session_start();

$GLOBALS['config'] = array(
	'mysql'=>array(
		'host'=>'localhost',
		'dbname'=>'estilospa',
		'user'=>'root',
		'pass'=>'',
		'prefix'=>'spa_'
	),
	'cookie'=>array(
		'cookie_name'=>'hash',
		'cookie_expire'=>60*60*24*30
	),
	'session'=>array(
		'session_name'=>'user',
		'token_name'=>'token'
	),
	'paths'=>array(
		'root'=>'estilospa',
		'admin'=>'admin',
		'site'=>'site',
		'panel'=>'cuenta',
		'styles'=>'css',
		'scripts'=>'js',
	)
);

spl_autoload_register(function($class){
	require_once 'classes/'.$class.'.php';
});

require_once 'functions.php';

$HTTP = 'http';
if(!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') $HTTP = 'https';
$slash = (!empty(Config::get('paths/root')) ? '/' : '');

define('ROOT',$HTTP.'://'.$_SERVER['HTTP_HOST'].'/'.Config::get('paths/root').$slash );
define('ADMIN',ROOT.Config::get('paths/admin').'/');
define('SITE',ROOT.Config::get('paths/site').'/');
define('CSS',ROOT.Config::get('paths/styles').'/');
define('JS',ROOT.Config::get('paths/scripts').'/');

define('DS',DIRECTORY_SEPARATOR);
define('PATH',__DIR__.DS);
define('IMG',PATH.'img'.DS);


define('IPUSER',$_SERVER['REMOTE_ADDR']);

define('PAGENAME', basename(__FILE__,'.php'));
define('MAXFILES',intval(ini_get('max_file_uploads')));

$_site = new Options();
$_site->get();

define("TITLE",$_site->info()['title']);
define("DESCRIPTION",$_site->info()['description']);
define("KEYWORDS",$_site->info()['keywords']);
date_default_timezone_set('America/Argentina/Buenos_Aires');


$_arrcss = array();
$_arrjs = array();