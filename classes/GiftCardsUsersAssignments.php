<?php

class GiftCardsUsersAssignments extends Core{

	protected 	$table='giftcards_users_assignments',							
							$alias='gua';

	private 		$_filters;


	public function search_filters(){

		$this->_filters = parent::core_filters([
			'filters'=>[
				'id'=>"{$this->alias}.id=?",
				'exclude'=>"{$this->alias}.id!=?",
				'ids'=>"{$this->alias}.id IN ($)",
				
				'user'=>"{$this->alias}.user_id=?",
				
				'purchase'=>"{$this->alias}.purchase_id=?",
				'purchase_ids'=>"{$this->alias}.purchase_id IN ($)",

				'is_gift'=>"{$this->alias}.is_gift=?",

				'code'=>"gp.code=?",
				'payment_status'=>"gp.payment_status=?"

			],
			'sort'=>[
				'default'=>"{$this->alias}.added DESC"
			]
		]);

	}


	public function get(){
		$query = $this->set_query("{$this->alias}.*");
		if(!$data = parent::core_get($query,$this->_filters->values)) return false;

		foreach($data as $k=>$row){
			$row->expiration_obj = new DateTime($row->expiration);
			$row->expiration = $row->expiration_obj->format('d/m/Y');
			$row->usage = false;
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
			LEFT JOIN {giftcards_purchases} gp ON gp.id={$this->alias}.purchase_id
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

	public function user_balance(){
		if(!$this->userdata) return false;

		$this->filters = [
			'user'=>$this->userdata->id,
			'payment_status'=>'approved'
		];
		
		/// restar usage
		$query = $this->set_query("SUM({$this->alias}.value) - IFNULL((SELECT SUM(value) FROM {giftcard_usage} gu WHERE gu.user_assignment_id={$this->alias}.id),0) total, MIN(expiration) expiration");

		$this->_db->query($query,$this->_filters->values);		
		if(!$total = $this->_db->first()->total) return false;

		$expiration = new DateTime($this->_db->first()->expiration);
		$today = new DateTime;

		return (object) [
			'total_formatted'=>number_format($total,2,',','.'),
			'total'=>$total,
			'expiration'=>$expiration->format('d/m/Y'),
			'is_expired'=>$expiration < $today
		];
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