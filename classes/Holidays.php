<?php 

class Holidays {

	private $_db;


	public 	$filters=array();



	public function __construct(){
		$this->_db = DB::getInstance();
	}



	public function save($id=null){

		$date = Dates::convert_datetime(Input::get('date'),'Y-m-d');
		
		$values = array(
			'date'=>$date,
			'name'=>Input::get('name')
		);
		

		if(!is_null($id)){
			if(!$this->_db->update('holidays',$id,$values)) return false;
			$lastid = $id;
		}else{
			if(!$this->_db->insert('holidays',$values)) return false;
			$lastid = $this->_db->getLastId();
			///$this->exclude_days($date);
		}
		return $lastid;
	}


	public function exclude_days($date=''){
		$Clients = new Clients();
		$Reservations = new Reservations();

		$Clients->get();
		$clients = $Clients->data();
		if($clients){
			foreach($clients as $client){
				$Reservations->exclude($client->id,$date);
			}
		}

		return true;

	}

	public function get(){

		$where = "";
		$values = array();

		if(!empty($this->filters)){
			foreach($this->filters as $key=>$filter){
				switch ($key) {
					case 'year':
						$where .= empty($where) ? "WHERE " : " AND ";
						$where .= "YEAR(h.date) = ?";
						$values[] = $filter;
						break;

				}
			}
		}

		$this->_db->query(
			"SELECT *
			FROM {holidays} h
			{$where}",
			$values
		);

		if(!$this->_db->count()) return false;

		return $this->_db->results();

	}


	public function delete($id=null){
		$this->_db->delete('holidays',array('id','=',$id));
		return true;
	}


}