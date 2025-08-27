<?php

class Curl {

	private static 	$_error,
									$_status,
									$_response;


	public static function action($obj=array()){

		if(!is_array($obj)) return false;
		$obj = (object) $obj;

		if(isset($obj->params) && is_array($obj->params)){
			$obj->url = $obj->url.'?'.http_build_query($obj->params,'','&',PHP_QUERY_RFC3986);
		}
		if(!isset($obj->method)){
			$obj->method = 'GET';
		}
		if(!isset($obj->body_json)){
			$obj->body_json = true;
		}

		$curl_params = [
			CURLOPT_URL =>$obj->url,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => "",
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 0,
			CURLOPT_FOLLOWLOCATION => true,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => $obj->method,

			CURLOPT_SSL_VERIFYPEER=>false,
			CURLOPT_SSL_VERIFYHOST=>false
		];

		if(isset($obj->header) && is_array($obj->header)){
			$curl_params[CURLOPT_HTTPHEADER] = $obj->header;
		}

		if(isset($obj->body) && is_array($obj->body)){
			if(isset($obj->is_formdata)){
				$curl_params[CURLOPT_POSTFIELDS] = $obj->body;
			}else{
				if($obj->body_json){
					$curl_params[CURLOPT_POSTFIELDS] = json_encode($obj->body);
				}else{
					$curl_params[CURLOPT_POSTFIELDS] = http_build_query($obj->body,'','&',PHP_QUERY_RFC3986);
				}
			}
		}

		$curl = curl_init();
		curl_setopt_array($curl, $curl_params);

		//show_array($curl_params[CURLOPT_HTTPHEADER],true);

		$response = curl_exec($curl);
		$info = curl_getinfo($curl);

		if($response===false){
			self::$_error =  curl_error($curl);
			return false;
		};
		self::$_status = $info['http_code'];

		///show_array(preg_match('/40/', $info['http_code']));
		if(preg_match('/40/', $info['http_code']) || preg_match('/(^41)/', $info['http_code']) || preg_match('/(^42)/', $info['http_code']) || preg_match('/(^50)/', $info['http_code'])) {
			self::$_error = json_decode($response);
			return false;
		};


		curl_close($curl);
		self::$_response = $response;
		if($response==='') return true;
		return json_decode($response, false, 512, JSON_BIGINT_AS_STRING);

	}

	public static function error(){
		return self::$_error;
	}

	public static function status(){
		return self::$_status;
	}
	public static function response(){
		return self::$_response;
	}

}