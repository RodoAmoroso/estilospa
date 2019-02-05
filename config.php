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
		'root'=>'estilospa/',
		'admin'=>'admin/'
	)
);

spl_autoload_register(function($class){
	require_once 'classes/'.$class.'.php';
});

require_once 'functions.php';

$HTTP = 'http';
if(!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') $HTTP = 'https';
define('ROOTPATH',$HTTP.'://'.$_SERVER['HTTP_HOST'].'/'.Config::get('paths/root'));
define('ADMINPATH',ROOTPATH.Config::get('paths/admin'));
define('PATH',__DIR__);
define('IPUSER',$_SERVER['REMOTE_ADDR']);

define('PAGENAME', basename(__FILE__,'.php'));
define('MAXFILES',intval(ini_get('max_file_uploads')));

$_CONFIG = DB::getInstance()->get('config',array('id','!=',0));
define("TITLE",$_CONFIG->results()[0]->value);
define("DESCRIPTION",$_CONFIG->results()[1]->value);
define("KEYWORDS",$_CONFIG->results()[4]->value);
date_default_timezone_set('America/Argentina/Buenos_Aires');
//////////////////
$_USER = new User();
if(Cookie::exists(Config::get('cookie/cookie_name')) && !Session::exists(Config::get('session/session_name'))){
	$hash = Cookie::get(Config::get('cookie/cookie_name'));
	$hashCheck = DB::getInstance()->get('sessions', array('hash','=',$hash));
	if($hashCheck->count()){
		$_USER = new User($hashCheck->first()->iduser);
		$_USER->login();
	}
}