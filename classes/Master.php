<?php

class Master extends Core{

	protected 	$table='master',
							$sizes=['small'=>'-t','big'=>'-n'],
							$folder='master',
							$alias='m';

	private 		$_filters;


	public function search_filters(){

		$this->_filters = parent::core_filters([
			'filters'=>[
				'id'=>"{$this->alias}.id=?",
				'exclude'=>"{$this->alias}.id!=?",
				'ids'=>"{$this->alias}.id IN ($)",
				'visible'=>"{$this->alias}.visible=?"
			],
			'search'=>[
				"{$this->alias}.name","{$this->alias}.title"
			],
			'sort'=>[
				'name'=>"{$this->alias}.name ASC",
				'added'=>"{$this->alias}.added DESC",
				'default'=>"{$this->alias}.position ASC",
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

	public function save(){
		$values = [
			'name'=>Input::get('name'),
			'title'=>Input::get('title'),
			'caption'=>Input::get('caption'),
			'image'=>empty(Input::get('image')) ? null : json_encode(Input::get('image')),
			'visible'=>Input::get('visible')
		];
		if(!parent::core_save(Input::get('id'),$values)) return false;
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