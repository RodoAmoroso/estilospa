<?php

session_start();

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

date_default_timezone_set(ENV::get('APP_TIMEZONE'));

@ini_set("display_errors", Env::get('PHP_INI_DISPLAY_ERRORS'));
@ini_set("upload_max_filesize", Env::get('PHP_INI_UPLOAD_MAX_FILESIZE'));
@ini_set("memory_limit", Env::get('PHP_INI_MEMORY_LIMIT'));
@ini_set("max_file_uploads", Env::get('PHP_INI_MAX_FILE_UPLOADS'));
@ini_set("max_execution_time", Env::get('PHP_INI_MAX_EXECUTION_TIME'));

$GLOBALS['config'] = array(
	'cookie'=>array(
		'cookie_name'=>'hash',
		'cookie_expire'=>60*60*24*30
	),
	'session'=>array(
		'session_name'=>'user',
		'token_name'=>'token',
		'salt'=>Env::get('SESSION_SALT')
	),
	'paths'=>array(
		'root'=>Env::get('SITE_PATH'),
		'admin'=>'admin',
		'site'=>'site',
		'panel'=>'panel',
		'styles'=>'css',
		'scripts'=>'js',
	)
);


require_once PATH.'defines.php';