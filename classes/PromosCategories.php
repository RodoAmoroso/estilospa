<?php

class PromosCategories extends Core{

	protected 	$table='promos_categories',
							$folder='categories',
							$sizes=['small'=>'-t','big'=>'-n'];


	public function get(){
		$filters = parent::core_filters([
			'filters'=>[
				'id'=>"pc.id=?",
				'visible'=>"pc.visible=?",
				'exclude'=>"pc.id!=?"
			],
			'search'=>[
				'pc.name','pc.caption'
			],
			'sort'=>[
				'name'=>"pc.name ASC",
				'added'=>"pc.added DESC",
				'default'=>"pc.position ASC"
			]
		]);


		$query =
		"SELECT pc.*, (SELECT COUNT(*) FROM {promos} p WHERE p.categoryid=pc.id ) total_promos
		FROM {{$this->table}} pc
		{$filters->where}
		{$filters->sort}
		LIMIT {$this->limit}";

		if(!$data = parent::core_get($query,$filters->values)) return false;
		foreach($data as $k=>$row){
			$data[$k]->image = parent::core_get_image($row->image);
		}

		return $data;
	}

	public function find($id=0){
		if(!$id) return false;
		$this->filters = ['id'=>$id];
		if(!$data = $this->get()) return false;
		return $data[0];
	}

	public function save(){
		$values = array(
			'name'=>Input::get('name'),
			'caption'=>Input::get('caption'),
			'image'=>empty(Input::get('image')) ? '' : json_encode(Input::get('image')),
			'visible'=>Input::get('visible')
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

	public function reorder($arrids=array()){
		if(!is_array($arrids)) return false;
		if(!parent::core_reorder($arrids)) return false;
		return true;
	}

}