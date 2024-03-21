<?php

class Sales {


	private		$_dbprefix,
						$_lastid,
						$_overall=0;

	protected	$_db,
						$_data;

	public		$keywords='',
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
						$status=false,
						$sale_voucher;

	public function __construct(){
		$this->_dbprefix = Config::get('mysql/prefix');
		$this->_db = DB::getInstance();
	}

	public function check($idc=0){
		//$this->_db->query("SELECT id FROM {$this->_dbprefix}sales WHERE merchant_order_id=?",array($merchant_order_id));
		$this->_db->query("SELECT id, collection_status FROM {sales} WHERE collection_id=?",array($idc));
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



	/// TEMP
	public function find_temp($hash=''){
		if(!$hash) return false;
		$this->_db->get('sales_temp',array('hash','=',$hash));
		if(!$this->_db->count()) return false;
		$this->_data = $this->_db->first();
		$this->_data->subtotal = (float) $this->_data->price;

		$this->_data->voucher = false;

		if($this->_data->idcode){
			$Vouchers = new Vouchers;
			$Vouchers->findcode($this->_data->idcode);
			if($this->_data->voucher = $Vouchers->data()){
				$this->_data->subtotal = $this->_data->price-($this->_data->voucher->ispercent ? $this->_data->price*$this->_data->voucher->value/100 : $this->_data->voucher->value);
			}else{
				$this->_data->idcode = null;
			}

		}

		$this->_data->total = (float) $this->_data->subtotal*$this->_data->quantity;
		return $this->_data;
	}
	public function create_temp($array=[]){
		if(!$this->_db->insert('sales_temp',$array)) return false;
		return $this->_db->getLastId();
	}
	public function delete_temp($hash=''){
		if(!$this->_db->delete('sales_temp',['hash','=',$hash])) return false;
		return true;
	}
	public function clean_temp(){
		$this->_db->query(
			"DELETE FROM {sales_temp}
			WHERE DATEDIFF(NOW(),added) > 10"
		);

		return true;
	}
	public function update_temp($id,$values=[]){
		if(!$id) return false;
		if(!$this->_db->update('sales_temp',$id,$values)) return false;
		return true;
	}
	public function init_temp($promo){
		if(!$promo) return false;

		$hash = Cookie::get('sale_hash');

		if($sale_temp = $this->find_temp($hash)){

			$sales_values = [
				'idpromo'=>$promo->id,
				'iduser'=>$promo->user->id,
				'idclient'=>$promo->client->id,
				//'quantity'=>1,
				'price'=>$promo->price_w_discount,
				'modified'=>date('Y-m-d H:i:s'),
			];
			if(!$promo->has_voucher){
				$sales_values['idcode'] = null;
			}
			if($promo->id != $sale_temp->idpromo){
				$sales_values['idcode'] = null;
				$sales_values['quantity'] = 1;
			}
			$this->update_temp($sale_temp->id,$sales_values);


			return $this->find_temp($sale_temp->hash);

		}else{

			$hash = set_hash();
			Cookie::put('sale_hash',$hash);
			$sale_temp = [
				'iduser'=>$promo->user->id,
				'idclient'=>$promo->client->id,
				'idpromo'=>$promo->id,
				'quantity'=>1,
				'price'=>$promo->price_w_discount,
				'hash'=>$hash,
				'added'=>date('Y-m-d H:i:s')
			];
			$this->create_temp($sale_temp);

		}
		return false;
	}
	/// TEMP


	public function process_sale($data){
		//echo_json($data);

		$sale_values = [
			'iduser'=>$data->user->id,
			'idclient'=>$data->client->id,
			'idpromo'=>$data->promo->id,

			'collection_id'=>$data->payment->id,
			'collection_status'=>$data->payment->status,
			'preference_id'=>'',
			'external_reference'=>$data->payment->external_reference,
			'payment_type'=>$data->payment->payment_type_id,
			//'merchant_order_id'=>$merchant_order_id,

			'price'=>$data->sale_temp->price, //unit price
			//'application_fee'=>isset($fees) ? $fees->application_fee : 0,
			//'mercadopago_fee'=>isset($fees) ? $fees->mercadopago_fee : 0,

			'added'=>date('Y-m-d H:i:s'),
			'quantity'=>$data->sale_temp->quantity,
			'hash'=>$data->sale_temp->hash
		];
		if($data->payment->fee_details){
			foreach ($data->payment->fee_details as $fee) {
				if($fee->type=='mercadopago_fee') $sale_values['mercadopago_fee'] = $fee->amount;
				if($fee->type=='application_fee') $sale_values['application_fee'] = $fee->amount;
			}
		}

		$this->save($sale_values);

		$saleid = $this->getLastId();

		///Reservations ???

		if($data->sale_temp->voucher){
			$sqlvoucher = [
				'idvoucher'=>$data->sale_temp->voucher->idvoucher,
				'idcode'=>$data->sale_temp->voucher->id,
				'iduser'=>$data->user->id,
				'idsale'=>$saleid,
				'ispercent'=>$data->sale_temp->voucher->ispercent,
				'value'=>$data->sale_temp->voucher->value,
				'added'=>date('Y-m-d H:i:s')
			];
			$this->_db->insert('vouchers_usage',$sqlvoucher);
		}

		$Promos = new Promos;
		$Promos->take_amount($data->promo->id,$data->sale_temp->quantity);


		//Mailing
		$this->find($saleid);
		$sale = $this->data();
		$sale->promolink = $data->promo->url;

		$Stores = new Stores;
		$Stores->get($data->client->id);
		$sale->stores = '<ul style="padding:0 16px">';
		if($Stores->data()){
			foreach($Stores->data() as $store){
				$sale->stores .= '<li>'.$store->address.', '.$store->city.' - '.$store->name.' '.(!empty($store->phones) ? ' - Tel: '.$store->phones : '' ).(!empty($store->whatsapp) ? ' - Celular: '.$store->whatsapp : '' ).'</li>';
			}
		}
		$sale->stores .= '</ul>';

		$sale->gift = null;
		$sale->image = $data->promo->image;
		$sale->price = $sale->total;

		$Mailing = new Mailing;
		$Mailing->sales_success_user($sale);
		$Mailing->sales_success_client($sale);
		$this->notified($sale->id,1);

		$this->delete_temp($data->sale_temp->hash);

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

	public function get($id=null){
		$this->_data = null;

		$where = "";
		$values = array();


		if(!is_null($id)){
			$field = is_numeric($id) ? 'id' : 'hash';
			$where = "WHERE s.{$field}=?";
			$values[] = $id;
		}

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
			$where .= "(s.merchant_order_id LIKE '%{$this->ordernumber}%' OR s.collection_id LIKE '%{$this->ordernumber}%')";
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

		if($this->keywords){
			$where .= empty($where) ? "WHERE " : " AND ";
			$where .= "(u.name LIKE '%{$this->keywords}%' OR u.lastname LIKE '%{$this->keywords}%' OR u.mail LIKE '%{$this->keywords}%')";
		}
		if($this->sale_voucher){
			$where .= empty($where) ? "WHERE " : " AND ";
			$arr_voucher = explode('-',$this->sale_voucher);
			$where .= "s.merchant_order_id LIKE '%{$arr_voucher[0]}%'";
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
			u.mail useremail, u.image, CONCAT(u.name,' ',u.lastname) username, u.phone userphone,
			m.text, m.rate, DATE_FORMAT(m.added, '%d/%m/%Y %H:%i:%s') fechacomment,
			vu.idvoucher voucher_id, vu.ispercent voucher_percent, vu.value voucher_value, vc.code voucher_code,
			sg.id giftid, sg.to_user,
			rs.reservationid
			FROM {sales} s
			LEFT JOIN {sales_status} ss ON ss.id=s.status
			LEFT JOIN {sales_gift} sg ON sg.hash=s.hash
			LEFT JOIN {promos} p ON p.id=s.idpromo
			LEFT JOIN {clients} c ON c.id=p.idclient
			LEFT JOIN {users} u ON u.id=s.iduser
			LEFT JOIN {comments} m ON m.idsale=s.id AND m.iduser=s.iduser
			LEFT JOIN {vouchers_usage} vu ON vu.idsale=s.id
			LEFT JOIN {vouchers_codes} vc ON vc.id=vu.idcode
			LEFT JOIN {reservations_sales} rs ON rs.saleid=s.id
			{$where}
			GROUP BY collection_id
			ORDER BY s.added DESC
			{$limitby}",
			$values
		);
		if(!$this->_db->count()) return false;
		$this->_data = $this->_db->results();
		foreach($this->_data as $key=>$row){
			$this->_data[$key]->sales_vouchers = $this->get_vouchers($row->id);


			$this->_data[$key]->discountvoucher = 0;
			$this->_data[$key]->vouchertext = '';
			if(!is_null($row->voucher_id)){
				$this->_data[$key]->vouchertext = ' - Usó Código: '.$row->voucher_code;
				if($row->voucher_percent==1){
					$this->_data[$key]->discountvoucher = $row->voucher_value*$row->price/100;
					$this->_data[$key]->vouchertext .= ' ('.$row->voucher_value.'% Off)';
				}else{
					$this->_data[$key]->discountvoucher = $row->voucher_value;
					$this->_data[$key]->vouchertext .= ' (-$'.$row->voucher_value.')';
				}
			}
			$this->_data[$key]->total = ($row->price-$this->_data[$key]->discountvoucher)*$row->quantity;



		}
		return true;
	}

	public function find($id=''){
		if(empty($id)) return false;

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


	public function evolution($clientid=0,$interval=6){

		$where = "WHERE s.added>=DATE_SUB(NOW(),INTERVAL {$interval} MONTH)";
		$values = array();
		if($clientid){
			$where .= " AND idclient=?";
			$values[] = $clientid;
		}
		$this->_db->query(
			"SELECT SUM(s.quantity) quantity, SUM(s.application_fee) suma, ROUND(SUM(s.price)-SUM(s.application_fee)-SUM(s.mercadopago_fee),2) neto_client, EXTRACT(YEAR FROM s.added) year, EXTRACT(MONTH FROM s.added) month
			FROM {sales} s
			$where
			GROUP BY EXTRACT(YEAR_MONTH FROM s.added)",
			$values
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


	public function notified($saleid=0,$status=1){
		$this->_db->update('sales',$saleid,array('notified'=>$status));
		return true;
	}


	public function get_comment($saleid=0,$userid=0){
		$this->_db->query("SELECT * FROM {comments} WHERE idsale=? AND iduser=?",array($saleid,$userid));
		if(!$this->_db->count()) return false;

		return $this->_db->first();
	}


	public function get_reservation_sale($saleid=0){
		$this->_db->get('reservations_sales',array('saleid','=',$saleid));
		if(!$this->_db->count()) return false;
		return $this->_db->first();
	}



	public function save_voucher(){
		$values = [
			'saleid'=>Input::get('saleid'),
			'downloads'=>0,
			'gift'=>Input::get('gift')
		];

		//$values['id'] = empty(Input::get('id')) ? null : Input::get('id');

		$values['to_user'] = Input::get('gift') ? Input::get('to_user') : null;
		$values['message'] = Input::get('gift') ? Input::get('message') : null;
		$values['image'] = Input::get('gift') && !empty(Input::get('image')) ? json_encode(Input::get('image')) : null;

		if(empty(Input::get('id'))){
			$this->_db->insert('sales_vouchers',$values);
			$voucherid = $this->_db->getLastId();
		}else{
			$voucherid = Input::get('id');
			$this->_db->update('sales_vouchers',$voucherid,$values);
		}
		return $voucherid;
	}


	public function get_vouchers($saleid=0){
		$this->_db->get('sales_vouchers',array('saleid','=',$saleid));
		if(!$this->_db->count()) return false;
		return $this->_db->results();
	}

	public function find_voucher($voucherid=0){

		if(!$voucherid) return false;

		$this->_db->query("
			SELECT
				sv.*,
				s.iduser
			FROM {sales_vouchers} sv
			LEFT JOIN {sales} s ON s.id=sv.saleid
			WHERE sv.id=?",
			[$voucherid]
		);
		if(!$this->_db->count()) return false;

		$output = $this->_db->first();

		if(!is_null($output->image)){
			$image = new stdClass();
			$img = json_decode($output->image);
			$image->path = IMG.'gift/'.$img->f.'.'.$img->e;
			$image->f = $img->f;
			$image->e = $img->e;
			$image->url = ROOT.'img/gift/'.$img->f.'.'.$img->e;
			$output->image = $image;
		}
		return $this->_db->first();
	}


	public function getquery(){
		return $this->_db->getquery()->queryString;
	}


}