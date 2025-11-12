<?php 

class Plans {

	private $_db,
					$_data,
					$_dbprefix,
					$_lastid;

	public function __construct(){
		$this->_dbprefix = Env::get('DB_PREFIX');
		$this->_db = DB::getInstance();
	}

	public function save(){
		$sql = array('name'=>Input::get('Name'),'promos'=>Input::get('Promos'),'fee'=>Input::get('Fee'));
		if(!Input::get('ID')){			
			$this->_db->insert('clientplans',$sql);
			$this->_lastid = $this->_db->getLastId();
			return true;
		}else{
			$this->_db->update('clientplans',Input::get('ID'),$sql);
			$this->_lastid = Input::get('ID');
			return true;
		}
		return false;
	}

	public function getLastId(){
		return $this->_lastid;
	}

	public function delete(){
		if($this->find(Input::get('ID'))){
			if( $this->_db->delete('clientplans',array('id','=',Input::get('ID'))) ){
				$this->_db->query("UPDATE {$this->_dbprefix}clients SET idplan=0 WHERE idplan=?",array(Input::get('ID')));					
				return true;
			}
		}		
		return false;
	}

	public function get(){
		$this->_db->get('clientplans',array('id','!=',0));
		if($this->_db->count()){
			$this->_data = $this->_db->results();
			return true;
		}
		return false;
	}

	public function find($id=0){
		$this->_db->get('clientplans',array('id','=',$id));
		if($this->_db->count()){
			$this->_data = $this->_db->first();
			return true;
		}
		return false;
	}

	public function data(){
		return $this->_data;
	}
}