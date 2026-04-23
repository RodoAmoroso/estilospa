<?php

class Sales extends Core{

	protected 	$table='sales',							
							$alias='s';

	private 		$_filters;


	public function search_filters(){

		$this->_filters = parent::core_filters([
			'filters'=>[
				'id'=>"{$this->alias}.id=?",
				'exclude'=>"{$this->alias}.id!=?",
				'ids'=>"{$this->alias}.id IN ($)",
				
				'hash'=>"{$this->alias}.hash=?",
				'user'=>"{$this->alias}.iduser=?",
				'to_gift_user'=>"sg.to_user=?",
				'from_gift_user'=>"sg.from_user=?",
				'client'=>"{$this->alias}.idclient=?",
				'promo'=>"{$this->alias}.idpromo=?",
				'status'=>"{$this->alias}.status=?",
				'payment_status'=>"{$this->alias}.payment_status=?",
				'voucher'=>"{$this->alias}.id IN (
					SELECT saleid 
					FROM {sales_vouchers} 
					WHERE id=?
				)",

				'from_date'=>"{$this->alias}.added >= ?",
				'to_date'=>"{$this->alias}.added <= ?",

			],
			'search'=>[
				"u.name",
				"u.lastname",
				"u.mail"
			],
			'sort'=>[
				
				'added_asc'=>"{$this->alias}.added ASC",
				'added_desc'=>"{$this->alias}.added DESC",

				'default'=>"{$this->alias}.added DESC",
			]
		]);

	}


	public function get(){
		$query = $this->set_query("
			{$this->alias}.*, 
			vu.idvoucher voucher_id, vu.ispercent voucher_percent, vu.value voucher_value, 
			vc.code voucher_code, 
			ss.name status_name
		");
		if(!$data = parent::core_get($query,$this->_filters->values)) return false;

		$promos_by_id = parent::core_extract_ids((object) [
			'data'=>$data,
			'foreign_key'=>'idpromo',
			'class'=>'Experiences'
		]);

		foreach($data as $k=>$row){
			$data[$k]->promo = $promos_by_id[$row->idpromo] ?? false;
			$data[$k]->price_formatted = format_price($row->price);
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
		return 
			"SELECT {$selects}
			FROM {{$this->table}} {$this->alias}
			LEFT JOIN {vouchers_usage} vu ON vu.idsale={$this->alias}.id
			LEFT JOIN {vouchers_codes} vc ON vc.id=vu.idcode
			LEFT JOIN {sales_status} ss ON ss.id={$this->alias}.status
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
			'name'=>Input::get('name'),
			'title'=>Input::get('title'),
			'caption'=>Input::get('caption'),
			'image'=>Input::get('image','json|nullable'),
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