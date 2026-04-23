<?php

class SalesComments extends Core{

	protected 	$table='comments',
							$alias='sc';

	private 		$_filters;


	public function search_filters(){

		$this->_filters = parent::core_filters([
			'filters'=>[
				'id'=>"{$this->alias}.id=?",
				'ids'=>"{$this->alias}.id IN ($)",
				'exclude'=>"{$this->alias}.id!=?",
				
				'sale'=>"{$this->alias}.idsale=?",
				'sale_ids'=>"{$this->alias}.idsale IN ($)",
				
				'user'=>"{$this->alias}.iduser=?",
				'user_ids'=>"{$this->alias}.iduser IN ($)",
				
			],
			'search'=>[
				"{$this->alias}.text"				
			],
			'sort'=>[
				'default'=>"{$this->alias}.added DESC"
			]
		]);

	}


	public function get(){
		$query = $this->set_query("{$this->alias}.*");
		if(!$data = parent::core_get($query,$this->_filters->values)) return false;
		return $data;
	}
	public function get_total(){
		$query = $this->set_query("COUNT({$this->alias}.id) total");
		$this->_db->query($query,$this->_filters->values);
		return $this->_db->first()->total;
	}
	protected function set_query($selects="*"){
		$this->search_filters();
		return "
			SELECT {$selects}
			FROM {{$this->table}} {$this->alias}
			{$this->_filters->where}
			{$this->_filters->sort}
			LIMIT {$this->limit}";
	}

	public function find($id=null){
		if(!$id) return false;
		$this->filters = ['id'=>$id];
		if(!$data = $this->get()) return false;
		return $data[0];
	}
	public function find_by_sale_user($idsale=null,$iduser=null){
		if(!$idsale || !$iduser) return false;
		$this->filters = ['sale'=>$idsale,'user'=>$iduser];
		if(!$data = $this->get()) return false;
		return $data[0];
	}

	public function save($values=[]){
		if(!parent::core_save($values['id'],$values)) return false;
		$id = parent::core_lastid();
		return $id;
	}

	public function delete($id=null){
		if(!$id) return false;
		if(is_object($id)){
			$data = $id;
		}else{
			if(!$data = $this->find($id)) return false;
		}
		if(!parent::core_delete($data)) return false;
		return true;
	}


}