<?php

class GiftCardsPurchases extends Core{

	protected 	$table='giftcards_purchases',
							$alias='gp';

	private 		$_filters;


	public function search_filters(){

		$this->_filters = parent::core_filters([
			'filters'=>[
				'id'=>"{$this->alias}.id=?",
				'exclude'=>"{$this->alias}.id!=?",
				'ids'=>"{$this->alias}.id IN ($)",
				
        'user'=>"{$this->alias}.user_id=?",
        'giftcard'=>"{$this->alias}.giftcard_id=?",
        
				'payment_status'=>"{$this->alias}.payment_status=?",
				'hash'=>"{$this->alias}.hash=?",
				'code'=>"{$this->alias}.code=?",

			],
			'sort'=>[
				'default'=>"{$this->alias}.added DESC"
			]
		]);

	}

	public function get(){
		$query = $this->set_query("{$this->alias}.*");

		$has_many = [
			[
				'class'=>'GiftCardsUsersAssignments',
				'name'=>'assignment',
				'foreign_key'=>'purchase_id',
				'foreign_filter'=>'purchase_ids'
			]
		];
		if(!$data = parent::core_get($query,$this->_filters->values,$has_many)) return false;

		$users_by_ids = parent::core_extract_ids((object) [
			'data'=>$data,
			'foreign_key'=>'user_id',
			'class'=>'Users'
		]);
		$giftcards_by_ids = parent::core_extract_ids((object) [
			'data'=>$data,
			'foreign_key'=>'giftcard_id',
			'class'=>'GiftCards'
		]);
		
		$GiftCardsUsersAssignments = new GiftCardsUsersAssignments;
		$GiftCardsUsersAssignments->filters = [
			'purchase_ids'=>implode(',',$this->ids())
		];
		//$gifcards_assignments = $GiftCardsUsersAssignments->get();
		//$giftcards_assignments_by_purchase_id = array_column($gifcards_assignments,null,'purchase_id');
		//dd($giftcards_assignments_by_purchase_id);

		foreach($data as $k=>$row){
			$row->user = $users_by_ids[$row->user_id] ?: false;
			$row->giftcard = $giftcards_by_ids[$row->giftcard_id] ?: false;

			if($row->assignment) $row->assignment = reset($row->assignment);

			$row->value_formatted = '$ '.number_format($row->price,0,',','.');
		}
		//dd($data);
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
	public function find_by_hash($hash=null){
		if(!$hash) return false;
		$this->filters = ['hash'=>$hash];
		if(!$data = $this->get()) return false;
		return $data[0];
	}
	public function find_by_code($code=null){
		if(!$code) return false;
		$this->filters = ['code'=>$code];
		if(!$data = $this->get()) return false;
		return $data[0];
	}

	public function save($values=[]){		
		if(!parent::core_save($values['id'],$values)) return false;
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