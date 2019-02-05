<?php

class Jason {

	public static function decode($string){
		return json_decode($string);
	}

	public static function encode($arr = array()){
		return json_encode($arr);
	}
}