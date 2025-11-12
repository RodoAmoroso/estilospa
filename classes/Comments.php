<?php 

class Comments {

	private $_db,
					$_data,
					$_dbprefix,
					$_lastid;

	public 	$iduser=0;

	public function __construct(){
		$this->_dbprefix = Env::get('DB_PREFIX');
		$this->_db = DB::getInstance();
	}

	public function delete($id=0){

	}

	public function deleteall(){
		if($this->_db->delete('comments',array('iduser','=',$this->iduser))){
			return true;
		}
		return false;
	}


}