<?php

class Newsletters extends Core{

	public function get($limit=50){
		$this->_db->query(
			"SELECT *
			FROM {newsletters_queue}
			ORDER BY added ASC
			LIMIT 0,{$limit}"
		);
		if(!$this->_db->count()) return false;
		return $this->_db->results();
	}


	public function add_queue($obj=array()){

		if(empty($obj)) return false;
		$obj = is_array($obj) ? (object) $obj : $obj;

		$this->_db->insert('newsletters_queue',array(
			'userid'=>$obj->userid,
			'subject'=>$obj->subject,
			'body'=>$obj->body,
			'type'=>$obj->type,
			'contextid'=>$obj->contextid
		));

		return true;
	}


	public function check_queue($userid=0){

		$this->_db->query(
			"SELECT *
			FROM {newsletters_queue}
			WHERE userid=?",
			array($userid)
		);

		if($this->_db->count()) return false;

		return true;

	}


	public function check_log($obj=array()){

		if(empty($obj)) return false;
		$obj = is_array($obj) ? (object) $obj : $obj;

		$this->_db->query(
			"SELECT *
			FROM {newsletters_log}
			WHERE ( DATEDIFF(NOW(),added) < 7 )
			OR (userid=? AND contextid=? AND type=?)",
			array($obj->userid,$obj->contextid,$obj->type)
		);
		//show_array($this->_db->getquery());

		if($this->_db->count()) return false;

		return true;


	}

	public function delete_queue($obj=null){

		if(is_null($obj)) return false;

		$this->add_log(array(
			'userid'=>$obj->userid,
			'type'=>$obj->type,
			'body'=>$obj->body,
			'contextid'=>$obj->contextid
		));
		$this->_db->delete('newsletters_queue',array('id','=',$obj->id));
		return true;
	}

	public function add_log($obj=array()){

		if(empty($obj)) return false;
		//$obj = is_array($obj) ? (object) $obj : $obj;

		$this->_db->insert('newsletters_log',$obj);

		return true;

	}

	public function get_log(){

		$filters = parent::core_filters([
			'filters'=>[
				'id'=>"nl.id=?",
				'user'=>"nl.userid=?",
				'body'=>"nl.body $"
			],
			'sort'=>[
				'default'=>"nl.added DESC"
			]
		]);

		$query =
		"SELECT nl.*
		FROM {newsletters_log} nl
		{$filters->where}
		{$filters->sort}
		LIMIT {$this->limit}";

		if(!$data = parent::core_get($query,$filters->values)) return false;
		return $data;

	}
	public function find_log($id=0){
		if(!$id) return false;
		$this->filters = ['id'=>$id];
		if(!$data = $this->get_log()) return false;
		return $data[0];
	}



}