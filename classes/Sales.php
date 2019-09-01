<?php 

class Sales {


	private 	$_dbprefix,
						$_lastid,
						$_overall=0;

	protected $_db,
						$_data;

	public 		$keywords='',
						$searchmixed=0,
						$arrfields=array(),
						$idclient=0,
						$iduser=0,
						$limit='',
						$period='',
						$ordernumber='',
						$range=false,
						$from='',
						$to='',
						$status=false;

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

	public function save_gift($arrfields=array()){
		if(!count($arrfields)) return false;
		$this->_db->insert('sales_gift',$arrfields);
		return true;
	}

	public function find_gift($hash=''){
		$this->_db->get('sales_gift',array('hash','=',$hash));
		
		if(!$this->_db->count()) return false;

		$output = $this->_db->first();
		$user = new User();		
		
		if(!$user->find($output->from_user)) return false;
		$output->from_user = $user->data();		
		
		if(!$user->find($output->to_user)) return false;
		$output->to_user = $user->data();

		$this->_data = $output;
		return $output;
		
	}

	public function find_temp($hash=''){
		$this->_db->get('sales_temp',array('hash','=',$hash));
		if($this->_db->count()){
			$this->_data = $this->_db->first();
			return true;
		}
		return false;
	}

	public function create_temp($array=array()){
		if(!$this->_db->insert('sales_temp',$array)) return false;
		return true;
	}

	public function delete_temp($hash=''){
		if($this->_db->delete('sales_temp',array('hash','=',$hash))){
			return true;
		}
		return false;
	}
	public function clean_temp(){
		$this->_db->query(
			"DELETE FROM {sales_temp}
			WHERE DATEDIFF(NOW(),added) > 10"
		);

		return true;
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
		$values = array();

		if($this->idclient){
			$where .= empty($where) ? "WHERE " : " AND ";
			$where .= "c.id=?";
			$values[] = $this->idclient;
		}
		if($this->iduser){
			$where .= empty($where) ? "WHERE " : " AND ";
			$where .= "(s.iduser=? OR sg.to_user=?)";
			$values[] = $this->iduser;
			$values[] = $this->iduser;
		}
		if(!empty($this->ordernumber)){
			$where .= empty($where) ? "WHERE " : " AND ";
			$where .= "s.merchant_order_id LIKE '%{$this->ordernumber}%' OR s.collection_id LIKE '%{$this->ordernumber}%'";
		}
		if($this->range){
			$where .= empty($where) ? "WHERE " : " AND ";
			$where .= "(s.added >= ? AND s.added <= ?)";
			$values[] = $this->from;
			$values[] = $this->to;
		}
		if($this->status){
			$where .= empty($where) ? "WHERE " : " AND ";
			$where .= "s.collection_status=?";
			$values[] = $this->status;
		}
		
		$limitby = 'LIMIT 0,100';
		if(!empty($this->limit)){
			$limitby = "LIMIT {$this->limit}";
		}
		$this->_db->query(
			"SELECT s.*, DATE_FORMAT(s.added, '%d/%m/%Y %H:%i:%s') fecha, 
			ss.name statusname, 
			p.title, p.subtitle, p.gallery, p.includes,
			c.name clientname, c.permalink, c.id clientid, c.mail clientemail,
			u.mail, u.image, CONCAT(u.name,' ',u.lastname) username, u.phone userphone,
			m.text, m.rate, DATE_FORMAT(m.added, '%d/%m/%Y %H:%i:%s') fechacomment, 
			vu.ispercent, vu.value, vu.idvoucher, vc.code, vu.idvoucher, 
			sg.id giftid, sg.to_user
			FROM {sales} s 
			LEFT JOIN {sales_status} ss ON ss.id=s.status 
			LEFT JOIN {sales_gift} sg ON sg.hash=s.hash
			LEFT JOIN {promos} p ON p.id=s.idpromo 
			LEFT JOIN {clients} c ON c.id=p.idclient 
			LEFT JOIN {users} u ON u.id=s.iduser
			LEFT JOIN {comments} m ON m.idsale=s.id AND m.iduser=s.iduser
			LEFT JOIN {vouchers_usage} vu ON vu.idsale=s.id
			LEFT JOIN {vouchers_codes} vc ON vc.id=vu.idcode
			{$where} 
			ORDER BY s.added DESC 
			{$limitby}",
			$values
		);

		if(!$this->_db->count()) return false;
		$this->_data = $this->_db->results();
		return true;
	}

	public function find($id=false){
		///$where = "WHERE s.collection_status = 'approved'";
		/*$where = "";
		$values = array();
		
		if($id){
			$where .= empty($where) ? "WHERE " : " AND ";
			$field = is_numeric($id) ? 'id' : 'hash';
			$where .= "s.{$field}=?";
			$values[] = $id;
		}
		if($this->iduser){
			$where .= empty($where) ? "WHERE " : " AND ";
			$where .= "s.iduser=? OR sg.";
			$values[] = $this->iduser;
		}
		$this->_db->query(
		"SELECT s.*, DATE_FORMAT(s.added, '%d/%m/%Y %H:%i:%s') fecha, ss.name statusname, 
		p.title, p.description, p.subtitle, p.gallery, p.includes, 
		c.name clientname, c.permalink, c.id clientid, c.mail clientemail, 
		u.mail useremail, CONCAT(u.name,' ',u.lastname) username, u.phone userphone, 
		m.text, m.rate, v.idvoucher voucher_id, v.ispercent voucher_percent, v.value voucher_value
			FROM spa_sales s 
			LEFT JOIN {sales_status} ss ON ss.id=s.status 
			LEFT JOIN {promos} p ON p.id=s.idpromo 
			LEFT JOIN {clients} c ON c.id=p.idclient 
			LEFT JOIN {users} u ON u.id=s.iduser
			LEFT JOIN {comments} m ON m.idsale=s.id AND m.iduser=s.iduser
			LEFT JOIN {vouchers_usage} v ON v.idsale=s.id
			{$where}",
			$values
		);
		
		if(!$this->_db->count()) return false;*/
		if(!$this->get($id)) return false;
		$this->_data = $this->_data[0];
		return true;
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

		$this->_db->query(
			"SELECT 
				SUM(s.price*s.quantity) - IF(vu.id != '', IF(vu.ispercent=1, SUM(vu.value*s.price/100), SUM(vu.value)), 0) overall,
				SUM(s.quantity) quantity,
				SUM(s.application_fee) neto,
				SUM(s.mercadopago_fee) mp_fee
			FROM {sales} s 
			LEFT JOIN {promos} p ON p.id=s.idpromo 
			LEFT JOIN {clients} c ON c.id=p.idclient
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


	public function evolution(){
		$this->_db->query(
			"SELECT SUM(s.quantity) quantity, SUM(s.application_fee) suma, EXTRACT(YEAR FROM s.added) year, EXTRACT(MONTH FROM s.added) month
			FROM {sales} s 
			WHERE s.added>=DATE_SUB(NOW(),INTERVAL 6 MONTH)
			GROUP BY EXTRACT(YEAR_MONTH FROM s.added)"
		);
		if(!$this->_db->count()) return false;

		return $this->_db->results();
	}


	public function get_latest(){

		$this->_db->query(
			"SELECT s1.* 
			FROM {sales} s1
			INNER JOIN {promos} p ON p.id=s1.idpromo
			INNER JOIN 
				(
					SELECT MAX(added) recent, iduser
					FROM {sales} 
					WHERE DATEDIFF(NOW(),added) = 16
					GROUP BY iduser
				) s2 
				ON s2.iduser=s1.iduser AND s2.recent=s1.added
			WHERE s1.idpromo != 0
				AND p.start<=NOW() 
				AND p.finish>=NOW()
			LIMIT 0,100"
		);
		if(!$this->_db->count()) return false;

		return $this->_db->results();
	}


	public function notified($saleid=0){
		$this->_db->update('sales',$saleid,array('notified'=>1));
		return true;
	}


	public function get_comment($saleid=0,$userid=0){
		$this->_db->query("SELECT * FROM {comments} WHERE idsale=? AND iduser=?",array($saleid,$userid));
		if(!$this->_db->count()) return false;

		return $this->_db->first();
	}


}