<?php 

class Assoc {

	private $_db,
					$_data,
					$_dbprefix,
					$_lastid;

	public 	$idclient=0,
					$iduser=0,
					$arrusers=array();

	public function __construct(){
		$this->_dbprefix = Config::get('mysql/prefix');
		$this->_db = DB::getInstance();
	}

	public function client_user($mode=''){
		switch ($mode) {
			case 'save':
				if($this->iduser){
					$this->_db->query("SELECT id FROM {$this->_dbprefix}assoc_client_user WHERE iduser=?",array('iduser'=>$this->iduser));
					if($this->_db->count()){
						if($this->idclient){
							$this->_db->update('assoc_client_user',$this->_db->first()->id,array('idclient'=>$this->idclient,'iduser'=>$this->iduser));
						}else{
							$this->_db->delete('assoc_client_user',array('id','=',$this->_db->first()->id));
						}
					}else{
						$this->_db->insert('assoc_client_user',array('idclient'=>$this->idclient,'iduser'=>$this->iduser));
					}
				}else{
					$this->_db->delete('assoc_client_user',array('idclient','=',$this->idclient));
					if(is_array($this->arrusers) && count($this->arrusers)){
						foreach($this->arrusers as $user){
							$this->_db->insert('assoc_client_user',array('idclient'=>$this->idclient,'iduser'=>$user));
						}
					}
				}
				return true;
				break;

			case 'delete':
				if($this->_db->delete('assoc_client_user',array('iduser','=',$this->iduser))){
					return true;
				}
				return false;
				break;

			case 'get':
				$where = '';
				if($this->iduser){
					$where = "WHERE a.iduser=".$this->iduser;
				}
				if($this->idclient){
					$where = "WHERE a.idclient=".$this->idclient;
				}
				if($this->_db->query("SELECT u.name, u.lastname, u.mail, a.iduser, a.idclient, c.name clientname FROM {$this->_dbprefix}assoc_client_user a LEFT JOIN {$this->_dbprefix}users u ON u.id=a.iduser LEFT JOIN {$this->_dbprefix}clients c ON c.id=a.idclient {$where}")){					
					$this->_data = $this->_db->results();					
					return true;
				}
				return false;
				break;
				
			default:
				return false;
				break;
		}		
		return false;
	}


	public function data(){
		return $this->_data;
	}


}