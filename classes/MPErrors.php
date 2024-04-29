<?php

class MPErrors extends Core{

	protected 	$table='mp_errors',
							$alias='mpe';

	private 		$_filters;


	public function search_filters(){

		$this->_filters = parent::core_filters([
			'filters'=>[
				'id'=>"{$this->alias}.id=?",
				'exclude'=>"{$this->alias}.id!=?",
			],
			'search'=>[
				"{$this->alias}.error",
				"{$this->alias}.sale"
			],
			'sort'=>[
				'default'=>"{$this->alias}.added DESC",
			]
		]);

	}


	public function get(){

		$this->search_filters();

		$query =
		"SELECT {$this->alias}.*
		FROM {{$this->table}} {$this->alias}
		{$this->_filters->where}
		{$this->_filters->sort}
		LIMIT {$this->limit}";

		if(!$data = parent::core_get($query,$this->_filters->values)) return false;

		return $data;
	}
	public function get_total(){
		$this->search_filters();
		$query =
		"SELECT COUNT({$this->alias}.id) total
		FROM {{$this->table}} {$this->alias}
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
		return $data[0];
	}

	public function save($values=[]){
		if(!parent::core_save(null,$values)) return false;
		$id = parent::core_lastid();
		return $id;
	}

	public function delete($id=null){
		if(!$id) return false;
		if(!$data = $this->find($id)) return false;
		if(!parent::core_delete($data)) return false;
		return true;
	}

	public function reorder($arrids=array()){
		if(!is_array($arrids)) return false;
		if(!parent::core_reorder($arrids)) return false;
		return true;
	}



}