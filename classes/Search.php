<?php 

class Search {
	private $_db,
					$_data,
					$_dbprefix,
					$_lastid;
	public 	$sort='',
					$exclude=0,
					$keywords='',
					$searchmixed=0,
					$limit='';

	public function __construct(){
		$this->_dbprefix = Env::get('DB_PREFIX');
		$this->_db = DB::getInstance();
	}

	public function search(){
		return $this->sort;
	}

	public function main(){
		$glossary = new Glossary();		
		$glossary->keywords = $this->keywords;
		$glossary->limit = $this->limit;
		$glossary->searchmixed = $this->searchmixed;
		if($glossary->get()){
			$this->_data = $glossary->data();
			return true;
		}
		return false;
	}

	public function locations(){
		$search_main = BuildSearch($this->keywords,$this->searchmixed,array('s.city','p.name'));
		$search = empty($search_main) ? "" : "WHERE".$search_main;
		
		$this->_db->query("SELECT s.id, s.city name FROM {$this->_dbprefix}stores s LEFT JOIN {$this->_dbprefix}provinces p ON p.id=s.idprovince {$search} GROUP BY s.city LIMIT 0,50");
		if($this->_db->count()){
			$this->_data = $this->_db->results();
			return true;
		}
		return false;
	}


	public function data(){
		return $this->_data;
	}
}