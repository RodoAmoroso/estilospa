<?php

class GiftCardsPersonalizations extends Core{

	protected 	$table='giftcards_personalizations',
							$sizes=['small'=>'','big'=>''],
							$folder='giftcards',
							$alias='gcp';

	private 		$_filters;


	public function search_filters(){

		$this->_filters = parent::core_filters([
			'filters'=>[
				'id'=>"{$this->alias}.id=?",
				'exclude'=>"{$this->alias}.id!=?",
				'ids'=>"{$this->alias}.id IN ($)",
				'giftcard_purchase'=>"{$this->alias}.giftcard_purchase_id=?"
			],
			'sort'=>[
				'default'=>"{$this->alias}.added ASC"
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
	public function find_by_purchase_id($id=null){
		if(!$id) return false;
		$this->filters = ['giftcard_purchase'=>$id];
		if(!$data = $this->get()) return false;
		return $data[0];
	}

	public function save(){
		$values = [
			'giftcard_purchase_id'=>Input::get('giftcard_purchase_id','int'),
			'from_user'=>Input::get('from_user','xss'),
			'to_user'=>Input::get('to_user','xss'),
			'to_email'=>Input::get('to_email','xss|nullable'),
			'message'=>Input::get('message','xss'),
			'image'=>Input::get('image','json|nullable'),
			'gallery_id'=>Input::get('gallery_id','int')
		];
		if(!parent::core_save(Input::get('id'),$values)) return false;
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