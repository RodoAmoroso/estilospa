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
			case 'request':
				return (!empty($_REQUEST)) ? true : false;
				break;
			default: 
				return false;
				break;
		}
	}

	public static function get_all(){
		return $_POST;
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
		if(!empty($_POST) && !empty($array)){
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

	public static function validate($array=array()){

		$obj = new stdClass();
		$obj->status = true;
		foreach($array as $key=>$value){
			switch($key){
				case 'password':
					if(strlen($value) < 8) {
						$obj->response = Responses::response('password_length');
						$obj->status = false;
					}
					/*if(!preg_match("#[0-9]+#", $value)) {
						$obj->response = Responses::response('password_number');
						$obj->status = false;
					}

					if(!preg_match("#[a-zA-Z]+#", $value)) {
						$obj->response = Responses::response('password_alpha');
						$obj->status = false;
					}*/
					break;

				case 'email':
					if(!filter_var($value,FILTER_VALIDATE_EMAIL)){
						$obj->response = Responses::response('invalid_email');
						$obj->status = false;
					}
					break;

				case 'phone':
					if(strlen($value) < 8) {
						$obj->response = Responses::response('phone_length');
						$obj->status = false;
					}

					if(!preg_match("#[0-9]+#", $value)) {
						$obj->response = Responses::response('phone_number');
						$obj->status = false;
					}
					break;


			}
		}

		return $obj;

	}
	
}