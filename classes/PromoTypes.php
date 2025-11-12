<?php

class PromoTypes {

	private $_db,
					$_data,
					$_dbprefix,
					$_lastid;

	public 	$keywords='',
					$searchmixed=0;

	public function __construct(){
		$this->_dbprefix = Env::get('DB_PREFIX');
		$this->_db = DB::getInstance();
	}

	public function find($id=0){
		$this->_db->get('promotypes',array('id','=',$id));
		if($this->_db->count()){
			$this->_data = $this->_db->first();
			return true;
		}
		return false;
	}

	public function get(){
		$search_main = BuildSearch($this->keywords,$this->searchmixed,array('name'));
		$search = empty($search_main) ? "" : "WHERE".$search_main;
		$this->_db->query("SELECT * FROM {$this->_dbprefix}promotypes {$search}");
		if($this->_db->count()){
			$this->_data = $this->_db->results();
			return true;
		}
		return false;
	}

	public function save(){
		$sql = array('name'=>Input::get('Name'));
		if(!Input::get('ID')){
			$this->_db->insert('promotypes',$sql);
			$this->_lastid = $this->_db->getLastId();
			return true;
		}else{
			$this->_db->update('promotypes',Input::get('ID'),$sql);
			$this->_lastid = Input::get('ID');
			return true;
		}
		return false;
	}

	public function delete(){
		if( $this->_db->delete('promotypes',array('id','=',Input::get('ID'))) ){
			return true;
		}
		return false;
	}

	public function getLastId(){
		return $this->_lastid;
	}

	public function data(){
		return $this->_data;
	}

}
