<?php

class Users extends Core{

	public 		$table='users',
						$sizes=['small'=>'-t','big'=>'-o'],
						$folder='users';

	private 	$_filters;

	public function search_filters(){
		$this->_filters = parent::core_filters([
			'filters'=>[
				'id'=>"u.id=?",
				'ids'=>"u.id IN ($)",
				'active'=>"u.active=?",
				'exclude'=>"u.id!=?",
				'type'=>"u.idtype=?",
				'has_questions'=>"(
					SELECT COUNT(q.id) 
					FROM {questions} q 
					WHERE q.userid=u.id AND q.type='promos'
				) > 0",
				'has_questions_client'=>"(
						SELECT COUNT(q.id)
						FROM {questions} q
						LEFT JOIN {promos} p ON p.id=q.rowid
						WHERE q.userid=u.id AND q.type='promos' AND p.idclient=?
					) > 0",
				'google_id'=>"u.google_id=?"
			],
			'search'=>[
				'u.name','u.lastname','u.mail'
			],
			'sort'=>[
				'logged'=>"u.logged DESC",
				'default'=>"u.created DESC"
			]
		]);
	}


	public function get(){

		$this->search_filters();

		$query =
		"SELECT
			u.*, CONCAT_WS(' ',u.name,u.lastname) fullname,
			ut.name type_name, ut.type type_reference
		FROM {{$this->table}} u
		LEFT JOIN {usertypes} ut ON ut.id=u.idtype
		{$this->_filters->where}
		{$this->_filters->sort}
		LIMIT {$this->limit}";

		if(!$data = parent::core_get($query,$this->_filters->values)) return false;

		return $data;
	}
	public function get_total(){

		$this->search_filters();

		$query =
		"SELECT COUNT(u.id) total
		FROM {{$this->table}} u
		{$this->_filters->where}
		{$this->_filters->sort}
		LIMIT {$this->limit}";
		$this->_db->query($query,$this->_filters->values);
		return $this->_db->first()->total;

	}

	public function find($id=null){
		if(!$id) return false;
		$this->filters = ['id'=>$id];
		if(!$data = $this->get()) return false;
		$data[0]->pass = '';
		return $data[0];
	}

	public function save(){
		$values = array(
			'name'=>Input::get('name'),
			'lastname'=>Input::get('lastname')
		);
		if(!parent::core_save(Input::get('id'),$values)) return false;
		return true;
	}
	public function store(){
		echo_json(Input::get_all(),true);
		return true;
	}

	public function delete($id=null){
		if(is_null($id)) return false;
		if(!$data = $this->find($id)) return false;

		if($data->blocked) die(Responses::response('fail','No puedes borrar este usuario'));
		if(!parent::core_delete($data)) return false;
		return true;
	}

	public function get_promos_questions($userid=0){

		$this->_db->query("
			SELECT
				p.*,
				c.name client_name, c.permalink
			FROM {promos} p
			LEFT JOIN {clients} c ON c.id=p.idclient
			WHERE (SELECT COUNT(q.id) FROM {questions} q WHERE q.rowid=p.id AND type=? AND userid=?) > 0",
			['promos',$userid]
		);

		if(!$this->_db->count()) return false;

		$data = $this->_db->results();
		foreach($data as $k=>$row){
			$data[$k]->questions = $this->get_questions_promos($userid,$row->id);
			$data[$k]->sales = $this->get_sales_promos($userid,$row->id);
		}

		return $data;

	}

	public function get_questions_promos($userid=0,$promoid=0){
		$this->_db->query(
			"SELECT
				q.*, qr.message response
			FROM {questions} q
			LEFT JOIN {questions_responses} qr ON qr.messageid=q.id
			WHERE q.userid=? AND q.rowid=? AND q.type=?",
			[$userid,$promoid,'promos']
		);
		if(!$this->_db->count()) return false;
		return $this->_db->results();
	}
	public function get_sales_promos($userid=0,$promoid=0){
		$this->_db->query(
			"SELECT
				s.*,
				vu.idvoucher voucher_id, vu.ispercent voucher_percent, vu.value voucher_value
			FROM {sales} s
			LEFT JOIN {vouchers_usage} vu ON vu.idsale=s.id
			WHERE s.iduser=? AND s.idpromo=?",
			[$userid,$promoid]
		);
		if(!$this->_db->count()) return false;
		return $this->_db->results();
	}

}