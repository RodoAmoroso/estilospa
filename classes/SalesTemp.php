<?php

class SalesTemp extends Core{

	protected 	$table='sales_temp',							
							$alias='st';

	private 		$_filters;


	public function search_filters(){

		$this->_filters = parent::core_filters([
			'filters'=>[
				'id'=>"{$this->alias}.id=?",
				'exclude'=>"{$this->alias}.id!=?",
				'ids'=>"{$this->alias}.id IN ($)",
				
				'user'=>"{$this->alias}.iduser=?",
				'client'=>"{$this->alias}.idclient=?",
				'promo'=>"{$this->alias}.idpromo=?",
				'code'=>"{$this->alias}.idcode=?",
				'reservation'=>"{$this->alias}.reservationid=?",
				'hash'=>"{$this->alias}.hash=?",

			],
			'sort'=>[
				'default'=>"{$this->alias}.added DESC",
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

	public function save($values){		
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

	public function reorder($arrids=[]){
		if(!is_array($arrids)) return false;
		if(!parent::core_reorder($arrids)) return false;
		return true;
	}



}