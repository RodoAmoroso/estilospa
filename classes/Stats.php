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

	public function add_search_type($word=''){
		if(empty($word) || strlen($word)<3) return false;
		$this->_db->insert('stats_search_types',array('word'=>$word));
		return true;
	}

	public function add_search_location($location=''){
		if(empty($location) || strlen($location)<3) return false;
		$this->_db->insert('stats_search_locations',array('location'=>$location));
		return true;
	}

	public function add_promo_view($userid=0,$promoid=0){
		if(!$userid && !$promoid) return false;
		$this->_db->insert('promo_views',array(
			'promoid'=>$promoid,
			'userid'=>$userid,
			'added'=>date('Y-m-d H:i:s')
		));
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

	public function get_top_promos($idclient=null){
		$where = '';
		$values = array();
		if(!is_null($idclient)){
			$where = "WHERE p.idclient=?";
			$values[] = $idclient;
		}
		$this->_db->query("
			SELECT p.id, p.title, p.views, c.name, c.permalink
			FROM {promos} p
			LEFT JOIN {clients} c ON c.id=p.idclient
			{$where}
			ORDER BY views DESC 
			LIMIT 0,10",
			$values
		);
		if(!$this->_db->count()) return false;
		return $this->_db->results();
	}

	public function get_top_promos_questions($idclient=null){
		
		$where = "WHERE q.type=?";
		$values = array('promos');
		if(!is_null($idclient)){
			$where .= "AND c.id=?";
			$values[] = $idclient;
		}
		$this->_db->query("
			SELECT p.id, p.title, c.name, c.permalink, COUNT(*) total
			FROM {questions} q 
			LEFT JOIN {promos} p ON p.id=q.rowid
			LEFT JOIN {clients} c ON c.id=p.idclient
			{$where}
			GROUP BY p.id
			ORDER BY total DESC 
			LIMIT 0,10",
			$values
		);
		if(!$this->_db->count()) return false;
		return $this->_db->results();
	}
	public function get_top_clients_questions($idclient=null){
		
		$where = "WHERE q.type=?";
		$values = array('clients');
		if(!is_null($idclient)){
			$where .= "AND c.id=?";
			$values[] = $idclient;
		}
		$this->_db->query("
			SELECT c.name, c.permalink, COUNT(*) total
			FROM {questions} q 
			LEFT JOIN {clients} c ON c.id=q.rowid
			{$where}
			GROUP BY c.id
			ORDER BY total DESC 
			LIMIT 0,10",
			$values
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

	//// CLIENT STATS ///


	public function add_tracker($clientid=0,$event=''){
		$this->_db->insert('stats_events',array(
			'clientid'=>$clientid,
			'event'=>$event
		));
		return true;
	}

	public function get_total_client_views($idclient=0){

		$total = 0;

		$this->_db->query("
			SELECT c.views+(SELECT SUM(p.views) FROM {promos} p WHERE p.idclient=?) total
			FROM {clients} c
			WHERE c.id=?",
			array($idclient,$idclient)
		);
		if(!$this->_db->count()) return 0;

		return $this->_db->first()->total;

	}
	public function get_total_client_favs($idclient=0){
		$total = 0;
		$this->_db->query("
			SELECT COUNT(*) total
			FROM {favs} f
			WHERE f.idclient=?",
			array($idclient)
		);
		if(!$this->_db->count()) return 0;
		return $this->_db->first()->total;
	}

	public function get_total_client_events($clientid=0){
		$this->_db->query(
			"SELECT e.event, COUNT(*) total
			FROM {stats_events} e
			WHERE e.clientid=?
			GROUP BY e.event",
			array($clientid)
		);
		if(!$this->_db->count()) return 0;

		return $this->_db->results();
	}

}