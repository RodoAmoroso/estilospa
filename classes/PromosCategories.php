<?php

class PromosCategories extends Core{

	protected 	$table='promos_categories',
							$folder='categories',
							$sizes=['small'=>'-t','big'=>'-o'],
							$alias='pc';

	private 		$_filters;

	public function search_filters(){

		$this->_filters = parent::core_filters([
			'filters'=>[
				'id'=>"{$this->alias}.id=?",
				'visible'=>"{$this->alias}.visible=?",
				'exclude'=>"{$this->alias}.id!=?",

				'main_category'=>"{$this->alias}.main_category_id=?",
				'main_category_ids'=>"{$this->alias}.main_category_id IN ($)"
			],
			'search'=>[
				"{$this->alias}.name",
				"{$this->alias}.caption"
			],
			'sort'=>[
				'name'=>"{$this->alias}.name ASC",
				'added'=>"{$this->alias}.added DESC",
				'default'=>"{$this->alias}.position ASC"
			]
		]);

	}

	public function get(){

		$query = $this->set_query("
			{$this->alias}.*, (
				SELECT COUNT(*)
				FROM {promos} p
				WHERE p.categoryid=pc.id
			) total_promos,
			mc.name main_category_name
		");

		if(!$data = parent::core_get($query,$this->_filters->values)) return false;

		foreach($data as $k=>$row){
			$data[$k]->image = parent::core_get_image($row->image);

			$data[$k]->permalink = ROOT.'categoria/'.$row->id.'-'.Permalink($row->name);
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
			LEFT JOIN {main_categories} mc ON mc.id={$this->alias}.main_category_id
			{$this->_filters->where}
			{$this->_filters->sort}
			LIMIT {$this->limit}";
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
			'main_category_id'=>Input::get('main_category_id'),
			'caption'=>Input::get('caption'),
			'image'=>Input::get('image','json'),
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