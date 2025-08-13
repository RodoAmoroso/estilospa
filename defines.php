<?php

$HTTP = 'http';
if(!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') $HTTP = 'https';
$slash = (!empty(Config::get('paths/root')) ? '/' : '');

$www = empty($_SERVER['HTTP_HOST']) ? 'www.estilospa.com' : $_SERVER['HTTP_HOST'];
define('ROOT',$HTTP.'://'.$www.'/'.Config::get('paths/root').$slash );
define('ADMIN',ROOT.Config::get('paths/admin').'/');
define('SITE',ROOT.Config::get('paths/site').'/');
define('PANEL',ROOT.Config::get('paths/panel').'/');
define('CSS',ROOT.Config::get('paths/styles').'/');
define('JS',ROOT.Config::get('paths/scripts').'/');

define('TOKEN',Session::session_hashed());
define('RAND',rand(11111111,99999999));

define('IPUSER',$_SERVER['REMOTE_ADDR']);

define('PAGENAME', basename(__FILE__,'.php'));
define('MAXFILES',intval(ini_get('max_file_uploads')));

$QUERIES = [];
$DB = DB::getInstance();


$Configuration = new Configuration();
$Configuration->get();
define('TITLE',$CFG->title);
define('DESCRIPTION',$CFG->description);
define('KEYWORDS',$CFG->keywords);

define("ENV",'sandbox');

$_arrcss = [];
$_arrjs = [];