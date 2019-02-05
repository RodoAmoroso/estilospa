<?php 

class Input {
	public static function exists($type='post'){
		switch($type){
			case 'post':
				return (!empty($_POST)) ? true : false;
			break;
			case 'get':
				return (!empty($_GET)) ? true : false;
			break;
			default: 
				return false;
			break;
		}
	}

	public static function get($item){
		if(isset($_POST[$item])){
			return $_POST[$item];
		}else if(isset($_GET[$item])){
			return $_GET[$item];
		}
		return '';
	}

	public static function check($array=array()){
		if(!empty($_POST)){
			foreach($array as $item){
				if(!array_key_exists($item, $_POST)){
					return false;
				}else{
					if(empty($_POST[$item])){
						return false;
					}
				}
			}
		}
		return true;
	}
}