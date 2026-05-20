<?php

class GiftCards extends Core{

	protected 	$table='giftcards',
							$sizes=['small'=>'-t','big'=>'-n'],
							$folder='giftcards',
							$alias='gc';

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
				"{$this->alias}.description",
				"{$this->alias}.title"
			],
			'sort'=>[

				'title_asc'=>"{$this->alias}.title ASC",
				'title_desc'=>"{$this->alias}.title DESC",

				'added_asc'=>"{$this->alias}.added ASC",
				'added_desc'=>"{$this->alias}.added DESC",

				'default'=>"{$this->alias}.position ASC"
			]
		]);

	}


	public function get(){
		$query = $this->set_query("{$this->alias}.*");
		if(!$data = parent::core_get($query,$this->_filters->values)) return false;
		//dd($data);
		foreach($data as $k=>$row){
			
			$row->value_formatted = '$ '.number_format($row->value,0,',','.');
			$row->value_novat_formatted = '$ '.number_format($row->value/1.21,0,',','.');

			$row->permalink = ROOT.'pase-multispa/'.$row->id.'-'.Permalink($row->title);
			$row->permalink_payment = ROOT.'pase-multispa-compra/'.$row->id.'-'.Permalink($row->title);
			
		}
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

	public function save(){
		$values = [
			'title'=>Input::get('title'),
			'description'=>Input::get('description'),
			'image'=>Input::get('image','json|nullable'),
			'value'=>Input::get('value','float'),
			'expiration'=>Input::get('expiration','int'),
			'visible'=>Input::get('visible')
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

	public function reorder($arrids=[]){
		if(!is_array($arrids)) return false;
		if(!parent::core_reorder($arrids)) return false;
		return true;
	}



}