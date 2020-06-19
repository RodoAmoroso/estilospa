<?php

class HotSale extends Core {

	protected 	$table='hotsale';

	public function get(){
		$filters = parent::core_filters([
			'filters'=>[
				'id'=>"h.id=?",
				'email'=>"h.email=?"
			],
			'search'=>[
				'h.email','h.name'
			],
			'sort'=>[
				'name'=>"h.name ASC",
				'email'=>"h.email ASC",
				'default'=>"h.added DESC"
			]
		]);


		$query =
		"SELECT h.*
		FROM {{$this->table}} h
		{$filters->where}
		{$filters->sort}";

		if(!$data = parent::core_get($query,$filters->values)) return false;

		return $data;
	}

	public function find($id=0){
		if(!$id) return false;
		$field = is_numeric($id) ? 'id' : 'email';
		$this->filters = [$field=>$id];
		if(!$data = $this->get()) return false;
		return $data[0];
	}

	public function save(){

		if($this->find(Input::get('email'))) return true;

		$values = array(
			'name'=>Input::get('name'),
			'email'=>strtolower(Input::get('email'))
		);
		if(!parent::core_save(null,$values)) return false;
		return true;
	}

	public function delete($id=0){
		if(!$id) return false;
		if(!$data = $this->find($id)) return false;
		if(!parent::core_delete($data)) return false;
		return true;
	}


}