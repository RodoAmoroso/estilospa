<?php

class Features {

	private $_db,
					$_data,
					$_dbprefix;

	public function __construct(){
		$this->_dbprefix = Config::get('mysql/prefix');
		$this->_db = DB::getInstance();
	}

	public function get($idclient){
		$this->_db->get('features',array('idclient','=',$idclient));
		if($this->_db->count()){
			$this->_data = $this->_db->results();			
			return true;
		}
		return false;
	}

	public function save($idclient=0,$features=array()){
		$this->delete($idclient);
		if(count($features)){
			foreach ($features as $k=>$feature) {
				$this->_db->insert('features',
					array(
					'idclient'=>$idclient,
					'title'=>$feature['title'],
					'description'=>$feature['description'],
					'position'=>($k+1)
					)
				);
			}
		}
		return true;
	}

	public function delete($idclient=0){
		if( $this->_db->delete('features',array('idclient','=',$idclient)) ){
			return true;
		}
		return false;
	}

	public function data(){
		return $this->_data;
	}

}
