<?php 

class Questions {

	private $_db,
					$_data,
					$_lastid;

	public 	$filters=array(),
					$page=1,
					$limit=20;

	public function __construct(){
		$this->_db = DB::getInstance();
	}

	public function get($id=null){

		$where = "";
		$values = array();

		if(!is_null($id)){
			$where .= empty($where) ? "WHERE " : " AND ";
			$where .= "q.id=?";
			$values[] = $id;
		}

		$limit = "LIMIT ".(($this->page*$this->limit)-$this->limit).",".$this->limit;

		if(!empty($this->filters)){
			foreach($this->filters as $filter){
				switch(key($filter)) {
					case 'userid':
						if(!empty($filter[key($filter)])){
							$where .= empty($where) ? "WHERE " : " AND ";
							$where .= "q.userid=?";
							$values[] = $filter[key($filter)];
						}
						break;
					case 'promo':
						if(!empty($filter[key($filter)])){
							$where .= empty($where) ? "WHERE " : " AND ";
							$where .= "q.rowid=? AND q.table=?";
							$values[] = $filter[key($filter)];
							$values[] = 'promo';
						}
						break;
					case 'client':
						if(!empty($filter[key($filter)])){
							$where .= empty($where) ? "WHERE " : " AND ";
							$where .= "q.rowid=? AND q.table=?";
							$values[] = $filter[key($filter)];
							$values[] = 'client';
						}
						break;
					case 'glossary':
						if(!empty($filter[key($filter)])){
							$where .= empty($where) ? "WHERE " : " AND ";
							$where .= "q.rowid=? AND q.table=?";
							$values[] = $filter[key($filter)];
							$values[] = 'glossary';
						}
						break;
				}
			}
		}

		$this->_db->query(
			"SELECT q.*, DATE_FORMAT(q.added,'%d/%m/%Y %H:%i') creado
			FROM {questions} q
			{$where}
			ORDER BY q.added DESC
			{$limit}",
			$values
		);

		if(!$this->_db->count()) return false;

		$data = $this->_db->results();
		foreach($data as $k=>$rs){
			$data[$k]->responses = $this->get_responses($rs->id);
		}

		if(!is_null($id)) $data = (object) $data[0];

		return $data;
	}
	public function get_responses($messageid=0){
		$this->_db->query(
			"SELECT r.*, DATE_FORMAT(r.added,'%d/%m/%Y %H:%i') creado
			FROM {questions_responses} r
			WHERE r.messageid=?
			ORDER BY r.added ASC",
			array($messageid)
		);
		if(!$this->_db->count()) return false;
		return $this->_db->results();
	}
	public function get_response($responseid=0){
		$this->_db->query(
			"SELECT r.*, DATE_FORMAT(r.added,'%d/%m/%Y %H:%i') creado
			FROM {questions_responses} r
			WHERE r.id=?",
			array($responseid)
		);
		if(!$this->_db->count()) return false;
		return $this->_db->first();
	}

	public function get_total($table='',$rowid=array()){
		$this->_db->query(
			"SELECT COUNT(*) total
			FROM {questions} q
			WHERE q.table=? AND q.rowid=?
			LIMIT 0,500",
			array($table,$rowid)
		);
		if(!$this->_db->count()) return 0;
		return $this->_db->first()->total;
	}


	public function add($table='',$values=array()){
		if(empty($values)) return false;

		if(!$this->_db->insert($table,$values)) return false;
		return $this->_db->getLastId();
	}

	public function check_privilege($questionid=0,$userid=0){
		if(!$question = $this->get($questionid)) return false;
		switch ($question->table) {
			case 'promo':
				
				$Promos = new Promos();
				$Clients = new Clients();
				if(!$Promos->find($question->rowid)) return false;
				$promo = $Promos->data();

				if(!$Clients->find($promo->idclient)) return false;
				$client = $Clients->data();

				if(!$Clients->check_assoc($userid,$promo->idclient)) return false;
				//$user = $User->data();

				break;
			case 'client':
				# code...
				break;
			case 'glossary':
				# code...
				break;
			
			
		}
		return true;

	}

	public function take_question($messageid=0){
		$this->_db->update('questions',$messageid,array('taken'=>1));
		return true;
	}
	public function take_response($responseid=0){
		$this->_db->update('questions_responses',$responseid,array('taken'=>1));
		return true;
	}

	public function has_response($messageid=0,$userid=0){
		$this->_db->query(
			"SELECT r.*, DATE_FORMAT(r.added,'%d/%m/%Y %H:%i:%s') creado
			FROM {questions_responses} r
			WHERE messageid=? AND userid=?",
			array($messageid,$userid)
		);
		if(!$this->_db->count()) return false;
		return $this->_db->first();
	}


}