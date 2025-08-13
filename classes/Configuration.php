<?php

class Configuration extends Core{

	public function get(){

		global $CFG;

		$this->_db->get('configuration');
		if(!$this->_db->count()) return false;

		$this->_data = $this->_db->results();


		$CFG = new stdClass();
		foreach($this->_data as $opt){
			$CFG->{$opt->name} = $opt->value;
		}

		return $this->_data;
	}


	public function find($key=''){
		$this->_db->get('configuration',array('name','=',$key));
		if($this->_db->count()){
			$this->_data = $this->_db->first();
			return true;
		}
		return false;
	}

	public function save(){
		foreach(Input::get_all() as $name=>$value){
			if($this->find($name)){
				$this->_db->update('configuration',array('name','=',$name),array('value'=>$value));
			}
		}
		return true;
	}

	public function data(){
		return $this->_data;
	}


}