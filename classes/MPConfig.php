<?php 

class MPConfig {

	private $_db,
					$_data,
					$_dbprefix,
					$_lastid,
					$_mplink,
					$_hash,
					$_error;

	public 	$arrfields=array(),
					$idclient=0;

	public function __construct(){
		$this->_dbprefix = Config::get('mysql/prefix');
		$this->_db = DB::getInstance();
	}

	public function find($idclient=0){
		$this->_db->get('mp',array('idclient','=',$idclient));
		if($this->_db->count()){
			$this->_data = $this->_db->first();
			return true;
		}
		return false;
	}

	public function get(){
		$this->_db->query(
			"SELECT m.id, m.idclient, m.userid, m.access_token, m.public_key, m.refresh_token, DATE_FORMAT(m.added,'%d/%m/%Y %H:%i') creado, FROM_UNIXTIME(UNIX_TIMESTAMP(m.added)+m.expires_in,'%d/%m/%Y %H:%i') expira, c.name, c.permalink
			FROM {$this->_dbprefix}mp m
			LEFT JOIN {$this->_dbprefix}clients c ON c.id=m.idclient
			ORDER BY m.added ASC"
		);
		if(!$this->_db->count()) return false;

		return $this->_db->results();
	}

	public function save(){
		if(count($this->arrfields)){
			$sql = array(
			'idclient'=>$this->idclient,
			'userid'=>$this->arrfields['user_id'],
			'access_token'=>$this->arrfields['access_token'],
			'public_key'=>$this->arrfields['public_key'],
			'refresh_token'=>$this->arrfields['refresh_token'],
			'expires_in'=>$this->arrfields['expires_in'],
			'added'=>date('Y-m-d H:i:s')
			);
			$this->_db->insert('mp',$sql);
			return true;
		}
		return false;
	}

	public function getmplink($PROMO='',$USER='',$CLIENTS='',$quantity=1,$idcode=0){
		if(empty($PROMO) || empty($USER) || empty($CLIENTS)){
			return false;
		}
		$this->_hash = hash('sha256', uniqid());
		$promoprice = $PROMO->price-($PROMO->price*$PROMO->discount/100);
		$discountvoucher = 0;
		$img = json_decode($PROMO->gallery);
		if($idcode){
			$_VOUCHERS = new Vouchers();
			$_VOUCHERS->findcode($idcode);
			if(!$_VOUCHERS->data()){
				return false;
			}
			if($_VOUCHERS->validate( $PROMO->id, $_VOUCHERS->data()->code, $USER->id )){
				if($_VOUCHERS->data()->ispercent){
					$discountvoucher = ($promoprice*$_VOUCHERS->data()->value/100);
				}else{
					$discountvoucher = $_VOUCHERS->data()->value;
				}
			}
		}
		require PATH.'/mercadopago/mercadopago.php';
		$mp = new MP($this->_data->access_token); // seller access_token
		//$mp = new MP('8912612574179921','mDMvtgjLASrGDnSYDNxQkqSdj8SaXH46'); // seller access_token

		$promo_url = ROOTPATH.'promo/'.$PROMO->permalink.'/'.$PROMO->id.'-'.Permalink($PROMO->title);
		
		$preference_data = array(
			"items" => array(
				array(
					"title" => $PROMO->title,
					"description" => $PROMO->description,
					"quantity" => $quantity,
					"unit_price" => ($promoprice-$discountvoucher),
					"currency_id" => "ARS",
					"picture_url" => "https://www.mercadopago.com/org-img/MP3/home/logomp3.gif",
					//"picture_url" => ROOTPATH.'img/promos/'.$img[0]->photoname.'-t.'.$img[0]->extension,
					"category_id" => "services"
				)
			),
			"marketplace_fee" => floatval( $CLIENTS->fee*(($promoprice-$discountvoucher)*$quantity)/100 ),
			"payer"=>array(
				"email"=>$USER->mail,
				"name"=>$USER->name,
				"surname"=>$USER->lastname
			),
			"back_urls"=>array(
				"success"=>ROOTPATH.'pago-status.php?status=success&hash='.$this->_hash.'&promourl='.$promo_url.'&amount='.(($promoprice-$discountvoucher)*$quantity),
				"failure"=>ROOTPATH.'pago-status.php?status=failure&hash='.$this->_hash.'&promourl='.$promo_url.'&amount='.(($promoprice-$discountvoucher)*$quantity),
				"pending"=>ROOTPATH.'pago-status.php?status=pending&hash='.$this->_hash.'&promourl='.$promo_url.'&amount='.(($promoprice-$discountvoucher)*$quantity)
			),
			"payment_methods"=>array(
				"excluded_payment_methods"=>array(),
				"excluded_payment_types"=>array(array("id"=>"ticket"),array("id"=>"atm")),
				//"excluded_payment_types"=>array(array("id"=>"atm")),
				"installments"=>null
			),
			"notification_url"=> ROOTPATH."ipn.php",
			"external_reference"=> $this->_hash,
		);
		try{
			$this->_mplink = $mp->create_preference($preference_data);
		}catch(MercadoPagoException $e){
			$this->_error = $e->getMessage();
			return false;
		}
		$this->_db->insert('salestemp',array(
			'iduser'=>$USER->id,
			'idpromo'=>$PROMO->id,
			'idcode'=>$idcode,
			'quantity'=>$quantity,
			'price'=>$PROMO->price-($PROMO->price*$PROMO->discount/100),
			'hash'=>$this->_hash,
			'added'=>date('Y-m-d H:i:s')
		));
		return true;
	}

	public function error(){
		return $this->_error;
	}

	public function mplink(){
		return $this->_mplink;
	}

	public function hash(){
		return $this->_hash;
	}

	public function unlink($idclient){
		if($this->_db->delete('mp',array('idclient','=',$idclient))){
			return true;
		}
		return false;
	}

	public function data(){
		return $this->_data;
	}

	public function renewtoken($idclient=0){
		
		$where = "WHERE UNIX_TIMESTAMP(m.added)+m.expires_in-(60*60*24*7)<=UNIX_TIMESTAMP(NOW())";
		if($idclient) $where = "WHERE m.idclient={$idclient}";

		$this->_db->query(
			"SELECT m.id, m.idclient, m.userid, m.access_token, m.public_key, m.refresh_token, m.added, c.name
			FROM {$this->_dbprefix}mp m
			LEFT JOIN {$this->_dbprefix}clients c ON c.id=m.idclient
			{$where}
			");
		if(!$this->_db->count()) return false;

		require PATH.'/mercadopago/mercadopago.php';
		foreach($this->_db->results() as $client){
			$mp = new MP($client->access_token);
			$request = array(
				"uri" => "/oauth/token",
				"data" => array(
					"client_secret" => $mp->get_access_token(),
					"grant_type" => "refresh_token",
					"refresh_token" => $client->refresh_token
				),
				"headers" => array(
					"content-type" => "application/x-www-form-urlencoded"
				),
				"authenticate" => false
			);
			try {
				$response = $mp->post($request);
				if($response['status'] == 200){
					$this->_db->update('mp',$client->id,array(
						'access_token'=>$response['response']['access_token'],
						'public_key'=>$response['response']['public_key'],
						'refresh_token'=>$response['response']['refresh_token'],
						'expires_in'=>$response['response']['expires_in'],
						'added'=>date('Y-m-d H:i:s')
					));
				}
			} catch (Exception $e) {
				$this->_db->delete('mp',array('id','=',$client->id));
			}
		}

		return true;

	}


}