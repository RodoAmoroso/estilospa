<?php 

class Reservations {

	private $_db,
					$_data,
					$_dbprefix,
					$_lastid;

	public 	$keywords='',
					$idcategory=0,
					$limit='',
					$exclude=0,
					$from='',
					$to='';

	public function __construct(){
		$this->_dbprefix = Config::get('mysql/prefix');
		$this->_db = DB::getInstance();
	}

	public function get($idclient=null,$iduser=null){
		
		$where = "";
		$values = array();
		if(!is_null($idclient)){
			$where = "WHERE c.id=?";
			$values[] = $idclient;
		}

		if(!is_null($iduser)){
			$where .= empty($where) ? "WHERE " : " AND ";
			$where .= "r.userid=?";
			$values[] = $iduser;
		}

		if(!empty($this->from) && !empty($this->to)){
			$where .= empty($where) ? "WHERE " : " AND ";
			$where .= "(r.book_date BETWEEN ? AND ?)";
			$values[] = $this->from;
			$values[] = $this->to;
		}

		$this->_db->query("
			SELECT r.*, DATE_FORMAT(r.book_date,'%d/%m/%Y %H:%i') fecha, c.name client_name, c.permalink, CONCAT(u.name,' ',u.lastname) user_name, u.mail user_email, u.phone user_phone, p.title, p.subtitle, p.gallery, (p.price-(p.price*p.discount/100)) price
			FROM {reservations} r 
			LEFT JOIN {promos} p ON p.id=r.promoid
			LEFT JOIN {users} u ON u.id=r.userid
			LEFT JOIN {clients} c ON c.id=p.idclient
			{$where}",
			$values
		);

		if(!$this->_db->count()) return false;		
		return $this->_db->results();
	}

	public function find($reservationid=0){
		$this->_db->get('reservations',array('id','=',$reservationid));
		if(!$this->_db->count()) return false;

		$output = $this->_db->first();

		$User = new User($output->userid);
		if(is_null($User->data())) return false;
		$output->user = $User->data();
		
		$Promos = new Promos();
		if(!$Promos->find($output->promoid)) return false;
		$output->promo = $Promos->data();

		$Clients = new Clients();
		if(!$Clients->find($output->promo->idclient)) return false;
		$output->client = $Clients->data();
		
		return $output;
	}


	public function confirm($id=0){
		$this->_db->update('reservations',$id,array('status'=>1));
		return true;
	}
	public function delete($id=0){
		$this->_db->delete('reservations',array('id','=',$id));
		return true;
	}

	public function change_date($id=0,$date=''){
		$this->_db->update('reservations',$id,array(
			'status'=>2,
			'book_date'=>str_replace('T',' ',$date)
		));
		return true;
	}

	public function add($array=array()){
		if(empty($array)) return false;
		if(!$this->_db->insert('reservations',$array)) return false;
		return $this->_db->getLastId();
	}

	public function taken_days($clientid=0,$date=''){
		$this->_db->query("
			SELECT r.*, MINUTE(r.book_date) minutos, HOUR(r.book_date) hora
			FROM {reservations} r 
			LEFT JOIN {promos} p ON p.id=r.promoid
			LEFT JOIN {clients} c ON c.id=p.idclient
			WHERE c.id=? AND DATE(r.book_date)=?",
			array($clientid,$date)
		);

		if(!$this->_db->count()) return false;		
		return $this->_db->results();
	}


}