<?php

class Redirect {
	public static function to($location = null){
		if($location){
			if(is_numeric($location)){
				switch ($location) {
					case 404:
						header('HTTP/1.0 404 Not Found'.$location);
						//include '404.php';
						exit();
						break;
				}
			}
			header('Location:'.ROOTPATH.$location);
			exit();
		}
	}
	public static function javascript($location = null){
		die('<script>javascript:window.location.href="'.ROOTPATH.$location.'"</script>');
	}
}