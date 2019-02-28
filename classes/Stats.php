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

	public function get_top_words(){
		$this->_db->query("
			SELECT word, COUNT(word) total
			FROM {stats_search_words} 
			GROUP BY word
			ORDER BY total DESC 
			LIMIT 0,10"
		);
		if(!$this->_db->count()) return false;
		return $this->_db->results();
	}
	public function get_top_locations(){
		$this->_db->query("
			SELECT location, COUNT(location) total
			FROM {stats_search_locations}
			GROUP BY location
			ORDER BY total DESC 
			LIMIT 0,10"
		);
		if(!$this->_db->count()) return false;
		return $this->_db->results();
	}

	public function get_top_promos(){
		$this->_db->query("
			SELECT p.id, p.title, p.views, c.name, c.permalink
			FROM {promos} p
			LEFT JOIN {clients} c ON c.id=p.idclient
			ORDER BY views DESC 
			LIMIT 0,10"
		);
		if(!$this->_db->count()) return false;
		return $this->_db->results();
	}
	public function get_top_clients(){
		$this->_db->query("
			SELECT id, name, views, permalink
			FROM {clients}
			ORDER BY views DESC 
			LIMIT 0,10"
		);
		if(!$this->_db->count()) return false;
		return $this->_db->results();
	}
	public function get_top_glossary(){
		$this->_db->query("
			SELECT id, name, views
			FROM {glossary}
			ORDER BY views DESC 
			LIMIT 0,10"
		);
		if(!$this->_db->count()) return false;
		return $this->_db->results();
	}
	public function get_top_blog(){
		$this->_db->query("
			SELECT id, title, views
			FROM {blog}
			ORDER BY views DESC 
			LIMIT 0,10"
		);
		if(!$this->_db->count()) return false;
		return $this->_db->results();
	}

}