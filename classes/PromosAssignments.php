<?php

class PromosAssignments extends Core{

	protected 	$table='promos_categories_assignments';


	public function get(){
		$filters = parent::core_filters([
			'filters'=>[
				'id'=>"pca.id=?",
				'promo'=>"pca.promoid=?",
				'category'=>"pca.categoryid=?",
				'exclude'=>"pca.id!=?"
			]
		]);


		$query =
		"SELECT pca.*
		FROM {{$this->table}} pca
		{$filters->where}";

		if(!$data = parent::core_get($query,$filters->values)) return false;
		return $data;
	}

	public function find($id=0){
		if(!$id) return false;
		$this->filters = ['id'=>$id];
		if(!$data = $this->get()) return false;
		return $data[0];
	}

	public function save($promoid=0,$categoryid=0){

		$this->_db->query("DELETE FROM {{$this->table}} WHERE promoid=? AND categoryid=?",[$promoid,$categoryid]);

		$values = array(
			'promoid'=>$promoid,
			'categoryid'=>$categoryid
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

}