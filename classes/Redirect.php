<?php

class Redirect {
	public static $root=SITE;


	public static function to($location = null, $external=false){

		if(is_null($location)) $location = '';

		/*if(is_numeric($location)){
			switch ($location) {
				case 404:
					header('HTTP/1.0 404 Not Found'.$location);
					//include '404.php';
					exit();
					break;
			}
		}*/
		$path = $external ? $location : self::$root.$location;
		header('Location:'.$path);
		exit();
		
	}

	public static function javascript($location = null){
		die('<script>javascript:window.location.href="'.self::$root.$location.'"</script>');
	}
}