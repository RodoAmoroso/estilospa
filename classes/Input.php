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
		return empty($_POST) ? $_GET : $_POST;
	}

	public static function get($item,$type=''){
		$input = '';
		if(isset($_POST[$item])){
			$input = $_POST[$item];
		}else if(isset($_GET[$item])){
			$input = $_GET[$item];
		}
		switch ($type) {
			case 'float':
				$input = (float) $input;
				break;
			case 'int':
				$input = (int) preg_replace('/[^0-9]/', '', $input);
				break;
			case 'array':
				$input = (array) $input;
				break;
			case 'object':
				$input = (object) $input;
				break;
			case 'json':
				$input = empty($input) ? '' : json_encode($input);
				break;
			case 'json|nullable':
				$input = empty($input) ? null : json_encode($input);
				break;
			case 'bool':
				$input = (bool) $input;
				break;
			case 'date':
				$input = DateTime::createFromFormat('d/m/Y',$input)->format('Y-m-d');
				break;
			case 'datetime':
				$input = DateTime::createFromFormat('d/m/Y H:i:s',$input)->format('Y-m-d H:i:s');
				break;
			case 'string':
				$input = htmlentities($input);
				break;
			case 'email':
				$input = strtolower(filter_var($input, FILTER_SANITIZE_EMAIL));
				break;
			case 'nullable':
				$input = empty($input) ? null : $input;
				break;
			case 'xss':
				$input = addslashes(strip_tags($input));
				break;
			default:
				$input = $input;
				break;
		}
		return $input;
	}

	public static function set($item='',$value='',$type='post'){
		switch ($type) {
			case 'post':
				$_POST[$item] = $value;
				break;
			case 'get':
				$_GET[$item] = $value;
				break;
			case 'request':
				$_REQUEST[$item] = $value;
				break;
		}
		return true;
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
		$obj->message = '';
		$fail = false;

		foreach($array as $key=>$value){
			switch($key){

				case 'password':
					if(strlen($value) < 8) {
						$fail = 'password_length';
					}
					if(!preg_match("#[0-9]+#", $value)) {
						$fail = 'password_number';
					}
					if(!preg_match("#[a-zA-Z]+#", $value)) {
						$fail = 'password_alpha';
					}
					break;

				case 'email':
					if(!filter_var($value,FILTER_VALIDATE_EMAIL)){
						$fail = 'invalid_email';
					}
					break;

				case 'phone':
					if(strlen($value) < 8) {
						$fail = 'phone_length';
					}
					if(!preg_match("#[0-9]+#", $value)) {
						$fail = 'phone_number';
					}
					break;

				case 'dni':
					if(!preg_match("/[0-9]{7}$/", $value) && strlen($value)!=8) {
						$fail = 'dni';
					}
					break;
				case 'cuit':
					if(!preg_match("/[0-9]{10}$/", $value) && strlen($value)!=11) {
						$fail = 'cuit';
					}
					break;

				case 'adult':
					$birth = DateTime::createFromFormat(self::detect_date_format($value),$value);
					$age = $birth->diff(new DateTime('now'))->y;
					if($age<18){
						$fail = 'adult';
					}
					break;


			}
		}

		if($fail){
			$obj->message = Responses::response($fail,'',[],false)['message'];
			$obj->status = false;
		}

		return $obj;

	}

	public static function detect_date_format($fecha){

		if (preg_match('/^\d{2}\/\d{2}\/\d{4}$/', $fecha)) return 'd/m/Y';
		if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $fecha)) return 'Y-m-d';
		return false;

	}

}