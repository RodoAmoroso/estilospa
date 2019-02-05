<?php

class Messages {

	private $_db,
					$_data,
					$_dbprefix;

	public 	$idclient=0,
					$idpromo=0,
					$limit='';

	public function __construct(){
		$this->_dbprefix = Config::get('mysql/prefix');
		$this->_db = DB::getInstance();
	}

	public function get(){
		$where = '';
		if($this->idclient) $where = "WHERE m.idclient={$this->idclient}";
		if($this->idpromo){
			$where .= empty($where) ? "WHERE " : " AND ";
			$where .= "m.idpromo={$this->idpromo}";
		}
		$limit = '';
		if(!empty($this->limit)) $limit = "LIMIT {$this->limit}";
		$this->_db->query(
			"SELECT m.id, m.iduser, m.fullname, m.mail, m.phone, m.message, m.idclient, m.idpromo, m.idglossary, m.added, m.status, DATE_FORMAT(m.added, '%d/%m/%Y %H:%i:%s hs.') creado, p.title promotitle, c.name clientname, c.permalink, g.name glossaryname
			FROM {$this->_dbprefix}messages m
			LEFT JOIN {$this->_dbprefix}promos p ON p.id=m.idpromo
			LEFT JOIN {$this->_dbprefix}clients c ON c.id=m.idclient
			LEFT JOIN {$this->_dbprefix}glossary g ON g.id=m.idglossary
			{$where} 
			ORDER BY m.added DESC 
			{$limit}"
		);
		if($this->_db->count()){
			$this->_data = $this->_db->results();			
			return true;
		}
		return false;
	}

	public function find($id=0){
		$this->_db->query("SELECT * FROM {$this->_dbprefix}messages WHERE id=?",array($id));
		if($this->_db->count()){
			$this->_data = $this->_db->first();
			return true;
		}
		return false;
	}

	public function save(){
		$_CLIENTS = new Clients();
		$_PROMOS = new Promos();
		$idpromo=0;
		$idclient=0;
		$idglossary=0;
		if(!empty(Input::get('IDP'))){
			if($_PROMOS->find(Input::get('IDP'))) $idpromo=$_PROMOS->data()->id;
			$idclient=$_PROMOS->data()->idclient;
		}
		if(!empty(Input::get('IDC'))){
			if($_CLIENTS->find(Input::get('IDC'))) $idclient=$_CLIENTS->data()->id;
		}
		if(!empty(Input::get('IDG'))) $idglossary = Input::get('IDG');
		$_USER = new User();
		$sql = array(
			'iduser'=>($_USER->logged() ? $_USER->data()->id : 0),
			'fullname'=>Input::get('Name'),
			'mail'=>Input::get('Mail'),
			'phone'=>Input::get('Phone'),
			'message'=>Input::get('Message'),
			'idclient'=>$idclient,
			'idpromo'=>$idpromo,
			'idglossary'=>$idglossary,
			'added'=>date('Y-m-d H:i:s'),
			'status'=>0
		);	
		if($this->_db->insert('messages',$sql)):
			$this->_lastid = $this->_db->getLastId();
			return true;
		endif;
		return false;
	}


	public function getLastId(){
		return $this->_lastid;
	}

	public function data(){
		return $this->_data;
	}


}