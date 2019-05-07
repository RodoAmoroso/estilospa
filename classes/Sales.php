<?php 

class Sales {


	private $_db,
					$_data,
					$_dbprefix,
					$_lastid,
					$_overall=0;

	public 	$keywords='',
					$searchmixed=0,
					$arrfields=array(),
					$idclient=0,
					$iduser=0,
					$limit='',
					$period='',
					$ordernumber='',
					$range=false,
					$from='',
					$to='';

	public function __construct(){
		$this->_dbprefix = Config::get('mysql/prefix');
		$this->_db = DB::getInstance();
	}

	public function check($idc=0){
		//$this->_db->query("SELECT id FROM {$this->_dbprefix}sales WHERE merchant_order_id=?",array($merchant_order_id));
		$this->_db->query("SELECT id FROM {sales} WHERE collection_id=?",array($idc));
		if(!$this->_db->count()) return false;
		$this->_data = $this->_db->first();
		return true;
	}

	public function update($idsale=0,$sql=array()){
		if(!$this->_db->update('sales',$idsale,$sql)) return false;
		return true;
	}

	public function save($arrfields=array()){
		if(count($arrfields)){
			$this->_db->insert('sales',$arrfields);
			$this->_lastid = $this->_db->getLastId();
			return true;
		}
		return false;
	}

	public function getLastId(){
		return $this->_lastid;
	}

	public function savegift($arrfields=array()){
		if(count($arrfields)){
			$this->_db->insert('gift',$arrfields);
			return true;
		}
		return false;
	}

	public function findgift($hash=''){
		$this->_db->get('gift',array('hash','=',$hash));
		if($this->_db->count()){
			$this->_data = $this->_db->first();
			return true;
		}
		return false;
	}

	public function findtemp($hash=''){
		$this->_db->get('salestemp',array('hash','=',$hash));
		if($this->_db->count()){
			$this->_data = $this->_db->first();
			return true;
		}
		return false;
	}

	public function createtemp($array=array()){
		$this->_db->insert('salestemp',$array);
		return true;
	}

	public function deletetemp($hash=''){
		if($this->_db->delete('salestemp',array('hash','=',$hash))){
			return true;
		}
		return false;
	}

	public function qualify(){
		$sql = array(
			'iduser'=>$this->iduser,
			'idsale'=>Input::get('idsale'),
			'text'=>Input::get('comment'),
			'rate'=>Input::get('rate'),
			'added'=>date('Y-m-d H:i:s')
		);
		if(!$this->_db->insert('comments',$sql)) return false;
		return true;
	}

	public function checkqualify($idsale=0){
		$this->_db->get('comments',array('idsale','=',$idsale));
		if($this->_db->count()) return false;
		return true;
	}

	public function setStatus(){
		if($this->_db->update('sales',Input::get('ID'),array('status'=>Input::get('Status')))){
			return true;
		}
		return false;
	}

	public function get(){
		$where = "";
		if($this->idclient){
			$where .= empty($where) ? "WHERE " : " AND ";
			$where .= "c.id=".$this->idclient;
		}
		if($this->iduser){
			$where .= empty($where) ? "WHERE " : " AND ";
			$where .= "s.iduser=".$this->iduser;
		}
		if(!empty($this->ordernumber)){
			$where .= empty($where) ? "WHERE " : " AND ";
			$where .= "s.merchant_order_id LIKE '%{$this->ordernumber}%' OR s.collection_id LIKE '%{$this->ordernumber}%'";
		}
		if($this->range){
			$where .= empty($where) ? "WHERE " : " AND ";
			$where .= "(s.added >= '{$this->from}' AND s.added <= '{$this->to}')";
			//echo $this->to;
		}
		
		$limitby = 'LIMIT 0,100';
		if(!empty($this->limit)){
			$limitby = "LIMIT {$this->limit}";
		}
		$this->_db->query("SELECT s.*, DATE_FORMAT(s.added, '%d/%m/%Y %H:%i:%s') fecha, ss.name statusname, p.title, p.gallery, c.name clientname, c.permalink, CONCAT(u.name,' ',u.lastname) username, u.mail, u.image, m.text, m.rate, DATE_FORMAT(m.added, '%d/%m/%Y %H:%i:%s') fechacomment, vu.ispercent, vu.value, vu.idvoucher, vc.code, vu.idvoucher
			FROM {$this->_dbprefix}sales s 
			LEFT JOIN {salesstatus} ss ON ss.id=s.status 
			LEFT JOIN {promos} p ON p.id=s.idpromo 
			LEFT JOIN {clients} c ON c.id=p.idclient 
			LEFT JOIN {users} u ON u.id=s.iduser
			LEFT JOIN {comments} m ON m.idsale=s.id AND m.iduser=s.iduser
			LEFT JOIN {vouchers_usage} vu ON vu.idsale=s.id
			LEFT JOIN {vouchers_codes} vc ON vc.id=vu.idcode
			{$where} 
			ORDER BY s.added DESC 
			{$limitby}");

		if(!$this->_db->count()) return false;
		$this->_data = $this->_db->results();
		return true;
	}

	public function find($id=0){
		///$where = "WHERE s.collection_status = 'approved'";
		$where = "";
		if($id){
			$where .= empty($where) ? "WHERE " : " AND ";	
			$where .= "s.id=".$id;
		}
		if($this->iduser){
			$where .= empty($where) ? "WHERE " : " AND ";
			$where .= "s.iduser=".$this->iduser;
		}
		$this->_db->query("SELECT s.*, DATE_FORMAT(s.added, '%d/%m/%Y %H:%i:%s') fecha, ss.name statusname, p.title, p.description, p.subtitle, p.gallery, p.includes, c.name clientname, c.permalink, c.id clientid, c.mail clientemail, u.mail useremail, CONCAT(u.name,' ',u.lastname) username, u.phone userphone, m.text, m.rate, v.idvoucher voucher_id, v.ispercent voucher_percent, v.value voucher_value
			FROM spa_sales s 
			LEFT JOIN {salesstatus} ss ON ss.id=s.status 
			LEFT JOIN {promos} p ON p.id=s.idpromo 
			LEFT JOIN {clients} c ON c.id=p.idclient 
			LEFT JOIN {users} u ON u.id=s.iduser
			LEFT JOIN {comments} m ON m.idsale=s.id AND m.iduser=s.iduser
			LEFT JOIN {vouchers_usage} v ON v.idsale=s.id
			{$where}");
		if($this->_db->count()){
			$this->_data = $this->_db->first();
			return true;
		}
		return false;
	}

	public function getOverall(){
		$where = "WHERE s.collection_status = 'approved'";
		if($this->idclient){
			$where .= empty($where) ? "WHERE " : " AND ";
			$where .= "c.id=".$this->idclient;
		}
		if(!empty($this->period)){
			switch($this->period){
				case 'thismonth':
					$where .= empty($where) ? "WHERE " : " AND ";
					$where .= "MONTH(s.added)=MONTH(NOW()) AND YEAR(s.added)=YEAR(NOW())";
					break;
				case 'lastmonth':
					$where .= empty($where) ? "WHERE " : " AND ";
					$where .= "MONTH(s.added)=MONTH(DATE_SUB(NOW(),INTERVAL 1 MONTH)) AND YEAR(s.added)=YEAR(DATE_SUB(NOW(),INTERVAL 1 MONTH))";
					break;
			}
		}

		$this->_db->query("SELECT SUM((s.price)*s.quantity) - IF(vu.id != '', IF(vu.ispercent=1, SUM(vu.value*s.price/100), SUM(vu.value)), 0) overall, SUM(s.quantity) quantity, SUM(cp.fee* ((s.price*s.quantity)- IF(vu.id != '', IF(vu.ispercent=1, (vu.value*s.price/100), (vu.value)), 0)) /100) neto
			FROM {$this->_dbprefix}sales s 
			LEFT JOIN {promos} p ON p.id=s.idpromo 
			LEFT JOIN {clients} c ON c.id=p.idclient
			LEFT JOIN {clientplans} cp ON cp.id=c.idplan 
			LEFT JOIN {vouchers_usage} vu ON vu.idsale=s.id
			{$where}");
		if($this->_db->count()){
			$this->_overall = $this->_db->first();
			return true;			
		}
		return false;
	}

	public function data(){
		return $this->_data;
	}

	public function overall(){
		return $this->_overall;
	}
}