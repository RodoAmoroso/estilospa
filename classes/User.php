<?php

class User {
	private $_db,
					$_data,
					$_sessionName,
					$_cookieName,
					$_logged=false,
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
			if($hash = Session::get($this->_sessionName)){
				if($user = $this->find_by_session($hash)){
					$this->login();
					return true;
				}
			}
			if($hash = Cookie::get($this->_cookieName)){
				if($user = $this->find_by_session($hash)){
					$this->login();
					return true;
				}
			}
		}else{
			if($this->find($user)){
				$this->login();
				return true;
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
	public function find_by_session($hash=''){
		$this->_db->get('sessions',['hash','=',$hash]);
		if(!$this->_db->count()) return false;
		$this->find($this->_db->first()->iduser);
		return $this->_db->first();
	}

	public function find($user=null,$hash=false){
		$this->_data = null;

		if(!$user) return false;

		$field = is_numeric($user) ? 'u.id' : 'u.mail';
		if($hash) $field = 'u.hash';

		$this->_db->query("SELECT 
			u.*, CONCAT(u.name,' ',u.lastname) fullname, 
			a.idclient, 
			c.idplan, c.added clientadded, c.name client_name, c.permalink client_permalink,
			p.fee, p.name planname, p.promos cantpromos 
		FROM {users} u
		LEFT JOIN {assoc_client_user} a ON a.iduser=u.id
		LEFT JOIN {clients} c ON c.id=a.idclient
		LEFT JOIN {clientplans} p ON p.id=c.idplan
		WHERE {$field} = ?",
		[$user]
		);

		if(!$this->_db->count()) return false;

		$this->_data = $this->_db->first();
		$img = is_null($this->_data->image) ? null : json_decode($this->_data->image);
		//show_array($img);
		
		$this->_data->image_url_big = View::img('users',is_null($img) ? 'user-default.png' : $img->photoname.'-o.'.$img->extension);
		$this->_data->image_url_small = View::img('users',is_null($img) ? 'user-default.png' : $img->photoname.'-t.'.$img->extension);

		return true;
	}

	public function get_session($userid=0){
		$hashCheck = $this->_db->get('sessions',array('iduser','=',$userid));
		$hash = hash('sha256', uniqid());
		if(!$hashCheck->count()){
			$this->_db->insert('sessions',array(
				'iduser'=>$userid,
				'hash'=>$hash
			));
		}else{
			$hash = $hashCheck->first()->hash;
			/*$this->_db->update('sessions',$hashCheck->first()->id,array(
				'hash'=>$hash
			));*/
		}
		return $hash;
	}
	public function set_session(){

		
		$hash = hash('sha256',date('Y-m-d H:i:s').rand(1111,9999));
		if(!$this->data()) return $hash;

		$hashCheck = $this->_db->get('sessions',array('iduser','=',$this->data()->id));

		if(!$hashCheck->count()){
			$this->_db->insert('sessions',array(
				'iduser'=>$this->data()->id,
				'hash'=>$hash
			));
		}else{
			$hash = $hashCheck->first()->hash;
		}

		return $hash;

	}

	public function login($user=null,$pass=null){

		if(!$user && !$pass && $this->exists()){
			$hash = $this->set_session();
			Session::put($this->_sessionName, $hash);
			Cookie::put($this->_cookieName, $hash);
			$this->_logged = true;
			return true;
		}
		
		
		if(!$user = $this->find($user)) return false;
		if(!password_verify($pass,$this->data()->pass)) return false;
		
		$hash = $this->set_session();
		
		Session::put($this->_sessionName,$hash);
		Cookie::put($this->_cookieName,$hash);

		$this->update($this->data()->id,[
			'logged'=>date('Y-m-d H:i:s')
		]);
		$this->_logged = true;
		
		return true;
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