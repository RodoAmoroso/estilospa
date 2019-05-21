<?php 

class Subscribers {

	private $_db,
					$_data,
					$_dbpx,
					$_lastid;

	public 	$keywords='',
					$limit='';

	public function __construct(){
		$this->_dbpx = Config::get('mysql/prefix');
		$this->_db = DB::getInstance();
	}

	public function get(){
		$limit = '';
		$where = '';

		if(!empty($this->keywords)){
			$where = "WHERE email LIKE '%{$this->keywords}%'";
		}

		if(!empty($this->limit)){
			$limit = "LIMIT {$this->limit}";
		}

		$this->_db->query(
			"SELECT s.*, DATE_FORMAT(s.added, '%d/%m/%Y %H:%i') creado
			FROM {subscribers} s
			{$where}
			ORDER BY s.added DESC
			{$limit}"
		);

		if(!$this->_db->count()) return false;

		return $this->_db->results();
	}

	public function delete($id=0){
		if(!$this->_db->delete('subscribers',array('id','=',$id))) return false;

		return true;

	}

	public function add($email=''){
		if(!$this->_db->insert('subscribers',array(
				'email'=>Input::get('email'),
				'added'=>date('Y-m-d H:i:s'),
				'hash'=>hash('sha256', uniqid())
		))) return false;

		return true;
	}

	public function verify($email=''){

		$this->_db->get('subscribers',array('email','=',$email));
		if($this->_db->count()) return false;

		return true;

	}



}