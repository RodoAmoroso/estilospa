<?php 

class UserAdmin {
	private $_db,
					$_data,
					$_dbprefix,
					$_lastid;

	public 	$limit='0,150',
					$keywords='',
					$searchmixed=0,
					$type=0,
					$idtype=0,
					$idclient=0;

	public function __construct(){
		$this->_dbprefix = Config::get('mysql/prefix');
		$this->_db = DB::getInstance();
	}

	public function save(){
		$sql = array(
			'name'=>Input::get('Name'),
			'lastname'=>Input::get('Lastname'),
			'birth'=>Input::get('Birth'),
			'mail'=>Input::get('Mail'),
			'image'=>empty(Input::get('IMG')) ? '' : json_encode(Input::get('IMG')),
			'address'=>Input::get('Address'),
			'addressobs'=>Input::get('AddressObs'),
			'zipcode'=>Input::get('Zip'),
			'idtype'=>Input::get('IDType'),
			'city'=>Input::get('City'),
			'idprovince'=>Input::get('IDProvince'),
			'phone'=>Input::get('Phone'),
			'active'=>Input::get('Active'),
			'dni'=>Input::get('DNI')
		);
		if(Input::get('ID')):
			if(!empty(Input::get('Pass'))){
				$sql['pass'] = password_hash(Input::get('Pass'),PASSWORD_DEFAULT);
			}
			$this->_db->update('users',Input::get('ID'),$sql);
			$this->_lastid = Input::get('ID');
			return true;
		else:
			$sql['created'] = date('Y-m-d H:i:s');
			$sql['logged'] = date('Y-m-d H:i:s');
			$sql['newsletter'] = 1;
			$sql['pass'] = password_hash(Input::get('Pass'),PASSWORD_DEFAULT);
			$sql['hash'] = hash('sha256', uniqid());
			$this->_db->insert('users',$sql);
			$this->_lastid = $this->_db->getLastId();
			return true;
		endif;
		return false;
	}

	public function delete($iduser=0){
		if($this->find($iduser)){
			if($this->_data->blocked){
				die(json_encode(array('Status'=>'blocked')));
			}
			if(!empty($this->_data->image)){
				$img = json_decode($this->_data->image);
				$th = PATH.'\img\users\\'.$img->photoname.'-t.'.$img->extension;
				$bg = PATH.'\img\users\\'.$img->photoname.'-o.'.$img->extension;
				if(file_exists($th)) unlink($th);
				if(file_exists($bg)) unlink($bg);
			}
			$assoc = new Assoc();
			$assoc->iduser = $iduser;
			$assoc->client_user('delete');

			$favs = new Favs();
			$favs->iduser = $iduser;
			$favs->deleteall($iduser);

			$comments = new Comments();
			$comments->iduser = $iduser;
			$comments->deleteall();
			
			if($this->_db->delete('users',array('id','=',$iduser))){
				return true;
			}
			return false;
		}
		return false;
	}

	public function find($user=null){
		if($user){
			$field = is_numeric($user) ? 'id' : 'mail';
			$this->_db->get('users',array($field,'=',$user));
			if($this->_db->count()){
				$this->_data = $this->_db->first();
				return true;
			}
		}
		return false;
	}

	public function get(){
		$search_main = BuildSearch($this->keywords,$this->searchmixed,array('u.name','u.lastname','u.mail'));
		$search = empty($search_main) ? "" : "WHERE".$search_main;
		if($this->idtype){
			if(empty($search)){$search = "WHERE";}else{$search .= " AND";}
			$search .= " u.idtype=".$this->idtype;
		}
		$limit = '';
		if($this->limit){
			$limit = "LIMIT ".$this->limit;
		}
		if($this->type){
			$search .= empty($search) ? "WHERE " : " AND ";
			$search .= "u.idtype={$this->type}";
		}
		$this->_db->query(
			"SELECT u.id, u.name, u.lastname, u.mail, a.idclient 
			FROM {$this->_dbprefix}users u 
			LEFT JOIN {$this->_dbprefix}assoc_client_user a ON a.iduser=u.id 
			{$search} 
			{$limit}"
		);
		if($this->_db->count()){
			$this->_data = $this->_db->results();
			return true;
		}
		return false;
	}

	public function data(){
		return $this->_data;
	}

	public function getLastId(){
		return $this->_lastid;
	}


}