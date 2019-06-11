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
					case 'clientid':
						$where .= empty($where) ? "WHERE " : " AND ";
						$where .= "(p.idclient=? OR c.id=? OR ga.clientid=?)";
						$values[] = $filter[key($filter)];
						$values[] = $filter[key($filter)];
						$values[] = $filter[key($filter)];
						break;

					case 'type':
						if(!empty($filter[key($filter)])){
							$where .= empty($where) ? "WHERE " : " AND ";
							$where .= "q.type=?";
							$values[] = $filter[key($filter)];
						}
						break;
					case 'promos':
						if(!empty($filter[key($filter)])){
							$where .= empty($where) ? "WHERE " : " AND ";
							$where .= "q.rowid=? AND q.type=?";
							$values[] = $filter[key($filter)];
							$values[] = 'promos';
						}
						break;
					case 'clients':
						if(!empty($filter[key($filter)])){
							$where .= empty($where) ? "WHERE " : " AND ";
							$where .= "q.rowid=? AND q.type=?";
							$values[] = $filter[key($filter)];
							$values[] = 'clients';
						}
						break;
					case 'glossary':
						if(!empty($filter[key($filter)])){
							$where .= empty($where) ? "WHERE " : " AND ";
							$where .= "q.rowid=? AND q.type=?";
							$values[] = $filter[key($filter)];
							$values[] = 'glossary';
						}
						break;
					case 'status':
						$where .= empty($where) ? "WHERE " : " AND ";
						if($filter[key($filter)] == 'answered'){
							$where .= "(SELECT COUNT(*) FROM {questions_responses} qr WHERE qr.messageid=q.id)>?";
						}else{
							$where .= "(SELECT COUNT(*) FROM {questions_responses} qr WHERE qr.messageid=q.id)=?";
						}						
						$values[] = 0;
						break;
				}
			}
		}

		$this->_db->query(
			"SELECT q.*, DATE_FORMAT(q.added,'%d/%m/%Y %H:%i') creado, u.name user_name, u.lastname user_lastname, u.mail user_email
			FROM {questions} q
			LEFT JOIN {users} u ON u.id=q.userid
			LEFT JOIN {promos} p ON p.id=q.rowid AND q.type='promos'
			LEFT JOIN {clients} cp ON p.idclient=cp.id
			LEFT JOIN {clients} c ON c.id=q.rowid AND q.type='clients'
			LEFT JOIN {clients_glossary_assignments} ga ON ga.glossaryid=q.rowid AND q.type='glossary'
			{$where}
			GROUP BY q.id
			ORDER BY q.added DESC
			{$limit}",
			$values
		);

		if(!$this->_db->count()) return false;

		$Clients = new Clients();
		$Glossary = new Glossary();
		$Promos = new Promos();

		$data = $this->_db->results();
		foreach($data as $k=>$rs){
			$data[$k]->responses = $this->get_responses($rs->id);
			if($rs->type=='clients'){
				$Clients->find($rs->rowid);
				$data[$k]->client = $Clients->data();
			}
			if($rs->type=='promos'){
				$Promos->find($rs->rowid);
				$data[$k]->promo = $Promos->data();
			}
			if($rs->type=='glossary'){
				$Glossary->find($rs->rowid);
				$data[$k]->glossary = $Glossary->data();
			}
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
		$data = $this->_db->results();
		foreach($data as $k=>$responses){
			$data[$k]->client = $this->find_client($responses->userid);
		}
		return $data;
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

	public function get_unanswered($clientid=null,$answered=false){

		$values = array();
		$where = "";
		if(!$answered){
			$where = "WHERE r.id IS NULL";
		}else{
			$where = "WHERE r.id IS NOT NULL";			
		}
		if(!is_null($clientid)){
			$where .= " AND (p.idclient=? OR c.id=? OR ga.clientid=?)";
			$values[] = $clientid;
			$values[] = $clientid;
			$values[] = $clientid;
		}

		$limit = "LIMIT ".(($this->page*$this->limit)-$this->limit).",".$this->limit;

		$this->_db->query(
			"SELECT q.*, DATE_FORMAT(q.added,'%d/%m/%Y %H:%i') creado, c.permalink, c.name client_name, cp.permalink permalink_promo, p.title promo_title, g.name glossary_name, u.name user_name, u.lastname user_lastname, u.mail user_email
			FROM {questions} q
			LEFT JOIN {users} u ON u.id=q.userid
			LEFT JOIN {promos} p ON p.id=q.rowid AND q.type='promos'
			LEFT JOIN {clients} cp ON p.idclient=cp.id
			LEFT JOIN {clients} c ON c.id=q.rowid AND q.type='clients'
			LEFT JOIN {clients_glossary_assignments} ga ON ga.glossaryid=q.rowid AND q.type='glossary'
			LEFT JOIN {glossary} g ON g.id=ga.glossaryid
			LEFT JOIN {questions_responses} r ON r.messageid=q.id
			{$where}
			GROUP BY q.id
			ORDER BY q.added DESC
			{$limit}",
			$values
		);
		
		//show_array($this->_db->getquery()->queryString);

		if(!$this->_db->count()) return false;

		$output = $this->_db->results();
		foreach($output as $k=>$question){
			$output[$k]->responses = $this->get_responses($question->id);
		}
		return $output;
	}

	public function find_client($userid=0){
		$Clients = new Clients();
		$Clients->getassoc($userid);
		if(!$Clients->data()) return false;

		return $Clients->data();
	}

	public function get_total($type='',$rowid=array()){
		$this->_db->query(
			"SELECT COUNT(*) total
			FROM {questions} q
			WHERE q.type=? AND q.rowid=?
			LIMIT 0,500",
			array($type,$rowid)
		);
		if(!$this->_db->count()) return 0;
		return $this->_db->first()->total;
	}


	public function add($type='',$values=array()){
		if(empty($values)) return false;

		if(!$this->_db->insert($type,$values)) return false;
		return $this->_db->getLastId();
	}

	public function check_privilege($questionid=0,$user=0){
		if(!$question = $this->get($questionid)) return false;
		
		$Promos = new Promos();
		$Clients = new Clients();
		$Glossary = new Glossary();

		switch ($question->type) {
			case 'promos':
				
				if(!$Promos->find($question->rowid)) return false;
				$promo = $Promos->data();

				if(!$Clients->find($promo->idclient)) return false;
				$client = $Clients->data();
				if(!$Clients->check_assoc($user->id,$client->id)) return false;

				break;
			case 'clients':
				if(!$Clients->find($question->rowid)) return false;
				$client = $Clients->data();
				if(!$Clients->check_assoc($user->id,$client->id)) return false;
				break;
			case 'glossary':
				if(!$Glossary->check_assoc($user->idclient,$question->rowid)) return false;
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


	public function add_queue($messageid=0,$clientid=0){
		$this->_db->insert('questions_queue',array(
			'messageid'=>$messageid,
			'clientid'=>$clientid
		));
		return true;
	}

	public function get_queue($limit=15){
		$this->_db->query(
			"SELECT q.id, q.messageid, q.clientid
			FROM {questions_queue} q
			LIMIT 0,{$limit}",
			array()
		);
		if(!$this->_db->count()) return false;
		return $this->_db->results();
	}

	public function delete_queue($queueid=0){
		$this->_db->delete('questions_queue',array('id','=',$queueid));
		return true;
	}

	public function delete_all($userid=0){
		$this->_db->query(
			"DELETE q,qr,qq 
			FROM {questions} q
			LEFT JOIN {questions_responses} qr ON q.id=qr.messageid
			LEFT JOIN {questions_queue} qq ON q.id=qq.messageid
			WHERE q.userid=?",
			array($userid)
		);
		return true;
	}

	public function delete_question($messageid=0){
		$this->_db->delete('questions',array('id','=',$messageid));
		$this->_db->delete('questions_responses',array('messageid','=',$messageid));
		$this->_db->delete('questions_queue',array('messageid','=',$messageid));
		return true;
	}
	public function delete_response($responseid=0){
		$this->_db->delete('questions_responses',array('id','=',$responseid));
		return true;
	}

	public function get_latest(){

		$this->_db->query(
			"SELECT q1.* 
			FROM spa_questions q1
			INNER JOIN 
				(
					SELECT MAX(added) recent, userid
					FROM spa_questions 
					WHERE DATEDIFF(NOW(),added) = 1
					GROUP BY userid
				) q2 
				ON q2.userid=q1.userid AND q2.recent=q1.added
			LIMIT 0,100"
		);

		if(!$this->_db->count()) return false;

		return $this->_db->results();

	}

}