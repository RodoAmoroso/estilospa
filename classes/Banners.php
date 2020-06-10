<?php

class Banners extends Core{

	protected $table='banners',
						$sizes=['small'=>'-t','big'=>'-o'],
						$folder='home';


	public function save(){
		$values = array(
			'name'=>Input::get('name'),
			'title'=>Input::get('title'),
			'caption'=>Input::get('caption'),
			'image'=>json_encode(Input::get('image')),
			'link'=>json_encode(Input::get('link')),
			'visible'=>Input::get('visible'),
			'type'=>Input::get('type')
		);

		if(!parent::core_save(Input::get('id'),$values)) return false;
		return true;
	}

	public function delete($id=0){
		if(!$id) return false;
		if(!$data = $this->find($id)) return false;
		if(!parent::core_delete($data)) return false;
		return true;
	}

	public function get(){

		$filters = parent::core_filters([
			'filters'=>[
				'id'=>"b.id=?",
				'exclude'=>"b.id!=?",
				'visible'=>"b.visible=?",
				'type'=>"b.type=?"
			],
			'sort'=>[
				'rand'=>"RAND()",
				'default'=>"b.position ASC"
			]
		]);

		$query =
		"SELECT b.*
		FROM {{$this->table}} b
		{$filters->where}
		{$filters->sort}
		LIMIT {$this->limit}";

		if(!$data = parent::core_get($query,$filters->values)) return false;
		foreach($data as $k=>$row){
			$data[$k]->image = json_decode($row->image);
			$data[$k]->link = json_decode($row->link);
		}

		return $data;
	}

	public function find($id=0){
		if(!$id) return false;
		$this->filters = ['id'=>$id];
		if(!$data = $this->get()) return false;
		return $data[0];
	}

	public function reorder($arrids=array()){
		if(!is_array($arrids)) return false;
		if(!parent::core_reorder($arrids)) return false;
		return true;
	}

}