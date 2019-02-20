<?php 

class Stats {

	private $_db,
					$_data,
					$_dbprefix,
					$_lastid;

	public function __construct(){
		$this->_dbprefix = Config::get('mysql/prefix');
		$this->_db = DB::getInstance();
	}

	public function add_search_word($word=''){
		if(empty($word) || strlen($word)<3) return false;
		$this->_db->insert('stats_search_words',array('word'=>$word));
		return true;
	}

	public function add_search_location($location=''){
		if(empty($location) || strlen($location)<3) return false;
		$this->_db->insert('stats_search_locations',array('location'=>$location));
		return true;
	}

}