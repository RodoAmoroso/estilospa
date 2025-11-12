<?php 

class Templates {

	public static function template($template,$obj=false){
		ob_start();
		include PATH.DS.'templates'.DS.$template.'.php';
		$response = ob_get_contents();
		ob_end_clean();
		return $response;
	}
}