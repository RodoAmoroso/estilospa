<?php 

class User {
	private $_db,
					$_data,
					$_sessionName,
					$_cookieName,
					$_logged,
					$_dbprefix,
					$_lastid;

	public 	$keywords='',
					$searchmixed=0;

	public function __construct($user=null){
		$this->_dbprefix = Config::get('mysql/prefix');
		$this->_db = DB::getInstance();

		$this->_sessionName = Config::get('session/session_name');
		$this->_cookieName = Config::get('cookie/cookie_name');

		if(!$user){
			if(Session::exists($this->_sessionName)){
				$user = Session::get($this->_sessionName);
				if($this->find($user)){
					$this->_logged = true;
				}
			}
		}else{
			if($this->find($user)){
				$this->_logged = true;
			}
		}
	}

	public function create($fields=array()){
		$this->_db->insert('users',$fields);
		return $this->_db->getLastId();
		/*if(!$this->_db->insert('users',$fields)){
			throw new Exception($this->_db->error());
		}else{
			return $this->_db->getLastId();
		}*/
	}	

	public function update($userid=0,$array=array()){
		if(!$this->_db->update('users',$userid,$array)) return false;
		return true;
	}

	public function find($user=null){		
		if($user){
			$field = is_numeric($user) ? 'u.id' : 'u.mail';
			//$this->_db->get('users',array($field,'=',$user));
			$this->_db->query("SELECT u.*, a.idclient, c.idplan, c.added clientadded, p.fee, p.name planname, p.promos cantpromos
				FROM {$this->_dbprefix}users u 
				LEFT JOIN {$this->_dbprefix}assoc_client_user a ON a.iduser=u.id 
				LEFT JOIN {$this->_dbprefix}clients c ON c.id=a.idclient 
				LEFT JOIN {$this->_dbprefix}clientplans p ON p.id=c.idplan 
				WHERE {$field} = ?",
				array($user)
			);
			if($this->_db->count()){
				$this->_data = $this->_db->first();
				return true;
			}
		}
		return false;
	}		

	public function login($user=null,$pass=null){

		if(!$user && !$pass && $this->exists()){
			Session::put($this->_sessionName, $this->data()->id);
		}else{		
			$user = $this->find($user);
			if($user){
				if(password_verify($pass,$this->data()->pass)){
					Session::put($this->_sessionName,$this->data()->id);	
					$hash = hash('sha256', uniqid());
					$hashCheck = $this->_db->get('sessions',array('iduser','=',$this->data()->id));				
					if(!$hashCheck->count()){
						$this->_db->insert('sessions',array(
							'iduser'=>$this->data()->id,
							'hash'=>$hash
						));
					}else{
						$hash = $hashCheck->first()->hash;
					}
					Cookie::put($this->_cookieName, $hash, Config::get('cookie/cookie_expire'));
					$this->_logged = true;
					return true;
				}
			}
		}
		return false;
	}

	public function exists(){
		return !empty($this->_data) ? true : false;
	}

	public function logout(){
		$this->_db->delete('sessions', array('iduser', '=', $this->data()->id));
		Session::delete($this->_sessionName);
		Cookie::delete($this->_cookieName);
	}

	public function data(){
		return $this->_data;
	}

	public function logged(){
		return $this->_logged;
	}

	public function isActive($user=null){
		$field = is_numeric($user) ? 'id' : 'mail';
		$this->_db->get('users',array($field,'=',$user));
		if($this->_db->count()){
			if($this->_db->first()->active){
				return true;
			}
		}
		return false;
	}

	public function activate($userid=0,$hash=''){
		$this->_db->query("
			SELECT id 
			FROM {users} 
			WHERE id=? AND hash=?",
			array($userid,$hash)
		);
		if(!$this->_db->count()) return false;

		$this->_db->update('users',$userid,array('active'=>1));

		return true;
	}

	public function update_hash($userid=null){
		$hash = hash('sha256', uniqid());
		if(!$this->_db->update('users',$userid,array('hash'=>$hash))) return false;
		return $hash;
	}

	public function check_hash($userid=0,$hash=''){
		$this->_db->query("
			SELECT id 
			FROM {users} 
			WHERE id=? AND hash=?",
			array($userid,$hash)
		);
		if(!$this->_db->count()) return false;
		return true;
	}

	public function reset_password($userid=0,$password=''){
		$newhash = hash('sha256', uniqid());
		if(!$this->_db->update('users',$userid,
			array(
				'hash'=>$newhash,
				'pass'=>password_hash($password,PASSWORD_DEFAULT)
			)
		)) return false;

		return true;
	}

	public function getLastId(){
		return $this->_lastid;
	}

}