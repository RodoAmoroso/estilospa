<?php

class Experiences extends Core{

	protected 	$table='promos',
							$folder='promos',
							$sizes=['small'=>'-t','big'=>'-o'];


	public function get(){
		$filters = parent::core_filters([
			'filters'=>[
				'id'=>"p.id=?",
				'exclude'=>"p.id!=?",
				'category'=>"p.categoryid=?",
				'client'=>"p.idclient=?",

				'active'=>"p.start<=NOW() AND p.finish>=NOW()",
				'finished'=>"p.finish<NOW()",
				'unstarted'=>"p.start>NOW()",
				'expiring'=>"(DATEDIFF(p.finish, NOW()) < 25 AND DATEDIFF(p.finish, NOW()) > 0)",

				'sale'=>"p.sale=?",
				'gift'=>"p.gift=?",

				//'city'=>"(SELECT s.id FROM {stores} s WHERE s.id IN(p.stores) AND s.city=?) IS NOT NULL"
				'city'=>"(
					SELECT s.id
					FROM {stores} s
					WHERE FIND_IN_SET(s.id,p.stores) AND s.city=?
					LIMIT 0,1
				) IS NOT NULL",

				'visible'=>"c.visible=?"

			],
			'search'=>[
				'p.title','p.subtitle','p.label','p.description'
			],
			'sort'=>[
				'rand'=>"RAND()",
				'title'=>"p.title ASC",
				'added'=>"p.added DESC",
				'position_client'=>"p.position_client ASC",
				'default'=>"p.position ASC"
			]
		]);


		$query =
		"SELECT
			p.*,
			pc.name category_name,
			c.permalink, c.name client_name, c.show_reservation
		FROM {{$this->table}} p
		LEFT JOIN {promos_categories} pc ON p.categoryid=pc.id
		LEFT JOIN {clients} c ON c.id=p.idclient
		{$filters->where}
		{$filters->sort}
		LIMIT {$this->limit}";

		if(!$data = parent::core_get($query,$filters->values)) return false;
		///parent::core_get($query,$filters->values);
		///show_array(parent::core_query(),true);
		foreach($data as $k=>$row){
			$data[$k]->image = json_decode($row->gallery);
			$data[$k]->link = ROOT.'promo/'.$row->permalink.'/'.$row->id.'-'.Permalink($row->title);
			$data[$k]->client_link = ROOT.'centros/'.$row->permalink;
		}

		return $data;
	}

	public function find($id=0){
		if(!$id) return false;
		$this->filters = ['id'=>$id];
		if(!$data = $this->get()) return false;
		return $data[0];
	}

	/*public function save(){
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
	}*/

	public function reorder($arrids=array()){
		if(!is_array($arrids)) return false;
		if(!parent::core_reorder($arrids)) return false;
		return true;
	}

}