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
		$now = new DateTime();

		foreach($data as $k=>$row){
			
			$data[$k]->image = json_decode($row->gallery);
			$row->image_main = ROOT.'img/promos/'.$data[$k]->image[0]->photoname.'-t.'.$data[$k]->image[0]->extension;

			$data[$k]->link = ROOT.'promo/'.$row->permalink.'/'.$row->id.'-'.Permalink($row->title);
			$data[$k]->client_link = ROOT.'centros/'.$row->permalink;

			$row->price_formatted = '$ '.number_format($row->price,2,',','.');

			$row->start_obj = new DateTime($row->start);
			$row->finish_obj = new DateTime($row->finish);
			
			$row->active = $row->start_obj <= $now && $row->finish_obj >= $now;
			
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
		
		$start = explode('/',Input::get('Start'));
		$finish = explode('/',Input::get('Finish'));

		$values = [
			'idclient'=>$idclient,
			'idpromotype'=>Input::get('IDPromotype'),
			'categoryid'=>empty(Input::get('CategoryID')) ? null : Input::get('CategoryID'),
			'gift'=>Input::get('Gift'),
			'sale'=>Input::get('Sale'),
			'stores'=>implode(',',Input::get('Stores')),
			'title'=>Input::get('Title'),
			'subtitle'=>Input::get('Subtitle'),
			'label'=>Input::get('Label'),
			'description'=>Input::get('Description'),
			'valid'=>Input::get('Valid'),
			'includes'=>Input::get('Includes'),
			'duration'=>Input::get('Duration'),
			'recomendations'=>Input::get('Recomendations'),
			'reservation'=>Input::get('Reservation'),
			'cancellation'=>Input::get('Cancellation'),
			'gallery'=>json_encode(Input::get('Gallery')),
			'price'=>Input::get('Price'),
			'discount'=>Input::get('Discount'),
			'amount'=>Input::get('Amount'),
			'start'=>$start[2].'-'.$start[1].'-'.$start[0],
			'finish'=>$finish[2].'-'.$finish[1].'-'.$finish[0]
		];

		if(!parent::core_save(Input::get('id'),$values)) return false;
		return true;
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

	public function reorder($arrids=array()){
		if(!is_array($arrids)) return false;
		if(!parent::core_reorder($arrids)) return false;
		return true;
	}

}