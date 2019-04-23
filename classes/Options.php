<?php 

class Options {

	private $_db,
					$_data,
					$_dbpx;

	public 	$fields = array();

	public function __construct(){
		$this->_dbpx = Config::get('mysql/prefix');
		$this->_db = DB::getInstance();
	}

	public function get(){
		$this->_db->get('config',array('id','!=',0));
		if($this->_db->count()){
			$this->_data = $this->_db->results();
			return true;
		}
		return false;
	}

	public function info(){
		$arr = array();
		if($this->_db->count()){
			foreach($this->_db->results() as $opt){
				$arr[$opt->name] = $opt->value;
			}
			return $arr;
		}
		return false;
	}

	public function find($key=''){
		$this->_db->get('config',array('name','=',$key));
		if($this->_db->count()){
			$this->_data = $this->_db->first();
			return true;
		}
		return false;
	}

	public function save(){
		$sql = array(
			'title'=>Input::get('title'),
			'description'=>Input::get('description'),
			'keywords'=>Input::get('keywords'),
			'phones'=>Input::get('phones'),
			'mails'=>Input::get('mails')
		);
		foreach($sql as $k=>$val):
			$this->_db->query("UPDATE {config} SET value=? WHERE name=?",array($val,$k));
		endforeach;
		return true;
	}

	public function data(){
		return $this->_data;
	}


}