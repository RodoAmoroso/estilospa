<?php

class Cookie{

	public static function put($name,$value){
		if(!setcookie($name,$value,time()+Config::get('cookie/cookie_expire'),'/'.Config::get('paths/root'))) return false;
		return true;

	}

	public static function exists($name){
		return isset($_COOKIE[$name]) ? true : false;
	}

	public static function get($name){
		if(!self::exists($name)) return false;
		return $_COOKIE[$name];
	}

	public static function delete($name){
		setcookie($name,'',time()-1,'/'.Config::get('paths/root'));
	}
}