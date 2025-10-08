<?php

class GiftCardsGallery extends Core{

	protected 	$table='giftcards_gallery',
							$sizes=['small'=>'-t','big'=>'-n'],
							$folder='giftcards',
							$alias='gg';

	private 		$_filters;


	public function search_filters(){

		$this->_filters = parent::core_filters([
			'filters'=>[
				'id'=>"{$this->alias}.id=?",
				'exclude'=>"{$this->alias}.id!=?",
				'ids'=>"{$this->alias}.id IN ($)"
			],
			'search'=>[
				"{$this->alias}.name",
				"{$this->alias}.title"
			],
			'sort'=>[
				'default'=>"{$this->alias}.position ASC"
			]
		]);

	}


	public function get(){
		$query = $this->set_query("{$this->alias}.*");
		if(!$data = parent::core_get($query,$this->_filters->values)) return false;
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

	public function save(){

    
    if(!is_array(Input::get('images'))) return false;

    $gallery = [];
    $total = $this->get_total();
    foreach(Input::get('images') as $image){

      $total++;

      $gallery[] = [
        json_encode([
          'f'=>$image['filename'],
          'e'=>$image['extension']
        ]),
        $total,
        date('Y-m-d H:i:s')
      ];
    }


    $this->_db->insertmultiple($this->table,[
      'image','position','added'
    ],$gallery);
		
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

	public function reorder($arrids=[]){
		if(!is_array($arrids)) return false;
		if(!parent::core_reorder($arrids)) return false;
		return true;
	}



}