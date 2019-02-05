<?php

class Validate {

	private $_passed = false,
					$_errors = array(),
					$_db = null;

	public function __construct(){
		$this->_db = DB::getInstance();
	}

	public function check($source, $items=array()){
		foreach ($items as $item => $rules) {
			foreach($rules as $rule => $rule_value){
				$value = $source[$item];
				if($rule === 'required' && empty($value)){
					$this->addError("{$item} es obligatorio");
				}else{
					switch (!empty($value)) {
						case 'min':
								if(strlen($value) < $rule_value){
									$this->addError("{$item} debe contener más de {$rule_value} caracteres.");
								}
							break;
						case 'max':
								if(strlen($value) > $rule_value){
									$this->addError("{$item} debe contener menos de {$rule_value} caracteres.");
								}
							break;
						case 'matches':
								if($value !== $source[$rule_value]){
									$this->addError("{$item} debe coincidir con {$rule_value}");
								}
							break;
						case 'unique':
								$check = $this->_db->get($rule_value,array($item,'=',$value));
								if($check->count()){
									$this->addError("{$item} ya exite en la base de datos.")
								}
							break;
					}
				}
			}
		}

		if(!$empty($this->_errors)){
			$this->_passed = true;
		}

	}

	public function passed(){
		return $this->_passed;
	}

	private function errors(){
		return $this->_errors;
	}

	private function addError($error){
		$this->_errors[] = $error;
	}
}