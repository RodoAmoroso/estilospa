<?php

session_start();
@ini_set("display_errors", 1);
@ini_set("upload_max_filesize", "32M");
@ini_set("memory_limit", "1024M");
@ini_set("max_file_uploads", "32");
@ini_set("max_execution_time", "800");

date_default_timezone_set('America/Argentina/Buenos_Aires');

define('DS',DIRECTORY_SEPARATOR);
define('PATH',__DIR__.DS);
define('IMG',PATH.'img'.DS);


require_once PATH.'vendor'.DS.'autoload.php';
require_once PATH.'functions.php';

spl_autoload_register(function($class){
	if(file_exists(PATH.'classes'.DS.$class.'.php')){
		require_once PATH.'classes'.DS.$class.'.php';
	}
});


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
		'token_name'=>'token',
		'salt'=>'We are the colors of the prism, shining light into the darkness'
	),
	'paths'=>array(
		'root'=>'',
		'admin'=>'admin',
		'site'=>'site',
		'panel'=>'panel',
		'styles'=>'css',
		'scripts'=>'js',
	)
);


require_once PATH.'defines.php';