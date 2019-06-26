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
					$idclient=0,
					
					$app_id='7300466898804487',					
					$redirect_uri=ROOT.'mp',
					$secret_key='4Y7yVlsccQUmJM3ExQT59JioiKPK113K',
					$access_token='APP_USR-7300466898804487-070519-065286686bbe9e2c819c57c7094d11da__LD_LC__-263157583';


					//Test Localhost
					//$redirect_uri=ROOT.'mp',
					//$app_id='7030611358224519',
					//$secret_key='5ziaNn6vMrN4FR1xodfDgfqvJT4RnLVN',
					//$access_token='APP_USR-7030611358224519-050401-40a4130219ec8743f65509dc8a65f78d-417751838';

					// Test SpaEstilo
					//$redirect_uri=ROOT.'mp',
					//$app_id='4678134710817612',
					//$secret_key='UOf6fadyymoUTHv93Hqstk2iLHDUtOdC',
					//$access_token='APP_USR-4678134710817612-053114-9f39c1925fe2b0c9e0aac0756a7c231a-417751838';


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
			"SELECT m.*, DATE_FORMAT(m.added,'%d/%m/%Y %H:%i') creado, FROM_UNIXTIME(UNIX_TIMESTAMP(m.added)+m.expires_in,'%d/%m/%Y %H:%i') expira, c.name, c.permalink
			FROM {mp} m
			LEFT JOIN {clients} c ON c.id=m.idclient
			ORDER BY m.added ASC"
		);
		if(!$this->_db->count()) return false;

		return $this->_db->results();
	}

	public function save($arrfields=array()){
		if(!is_array($arrfields)) return false;

		/*if(count($this->arrfields)){
			$sql = array(
			'idclient'=>$this->idclient,
			'userid'=>$this->arrfields['user_id'],
			'access_token'=>$this->arrfields['access_token'],
			'public_key'=>$this->arrfields['public_key'],
			'refresh_token'=>$this->arrfields['refresh_token'],
			'expires_in'=>$this->arrfields['expires_in'],
			'added'=>date('Y-m-d H:i:s')
			);
			return true;
		}
		return false;*/
		$this->_db->insert('mp',$arrfields);
		return true;
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
		require PATH.'/vendor/autoload.php';
		
		//$mp = new MP($this->_data->access_token); // seller access_token
		
		//$mp = new MP('8912612574179921','mDMvtgjLASrGDnSYDNxQkqSdj8SaXH46'); // seller access_token

		$client_accesstoken = $this->get_access_token($CLIENTS->id);
		MercadoPago\SDK::setAccessToken($client_accesstoken);
		//MercadoPago\SDK::setClientSecret($this->secret_key);

		$promo_url = ROOT.'promo/'.$PROMO->permalink.'/'.$PROMO->id.'-'.Permalink($PROMO->title);
		
		/*$preference_data = array(
			"items" => array(
				array(
					"title" => $PROMO->title.' - '.$CLIENTS->name,
					"description"=>$PROMO->subtitle,
					"quantity" => $quantity,
					"unit_price" => ($promoprice-$discountvoucher),
					"currency_id" => "ARS",
					//"picture_url" => "https://www.mercadopago.com/org-img/MP3/home/logomp3.gif",
					"picture_url" => ROOT.'img/promos/'.$img[0]->photoname.'-t.'.$img[0]->extension,
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
				"success"=>ROOT.'pago-status/success?hash='.$this->_hash.'&promourl='.$promo_url.'&amount='.(($promoprice-$discountvoucher)*$quantity),
				"failure"=>ROOT.'pago-status/failure?hash='.$this->_hash.'&promourl='.$promo_url.'&amount='.(($promoprice-$discountvoucher)*$quantity),
				"pending"=>ROOT.'pago-status/pending?hash='.$this->_hash.'&promourl='.$promo_url.'&amount='.(($promoprice-$discountvoucher)*$quantity)
			),
			"payment_methods"=>array(
				"excluded_payment_methods"=>array(),
				"excluded_payment_types"=>array(array("id"=>"ticket"),array("id"=>"atm")),
				//"excluded_payment_types"=>array(array("id"=>"atm")),
				"installments"=>null
			),
			//"notification_url"=> ROOT."ipn.php",
			"notification_url"=> "https://www.estilospa.com/test-ipn.php?idclient=".$PROMO->idclient,
			"external_reference"=> $this->_hash,
		);*/

		$preference = new MercadoPago\Preference();

		$item = new MercadoPago\Item();
		$item->title = $PROMO->title.' - '.$CLIENTS->name;
		$item->description = $PROMO->subtitle;
		$item->quantity = $quantity;
		$item->currency_id = "ARS";
		$item->unit_price = $promoprice-$discountvoucher;
		$item->picture_url = ROOT.'img/promos/'.$img[0]->photoname.'-t.'.$img[0]->extension;
		$item->category_id = 'services';

		$payer = new MercadoPago\Payer();
		$payer->email = $USER->mail;
		$payer->name = $USER->name;
		$payer->surname = $USER->lastname;


		$preference->items = array($item);
		$preference->payer = $payer;
		$preference->marketplace_fee = floatval( $CLIENTS->fee*(($promoprice-$discountvoucher)*$quantity)/100 );
		$preference->notification_url = "https://www.estilospa.com/test-ipn.php?idclient=".$PROMO->idclient;
		///$preference->notification_url = ROOT.'ipn.php?idclient='.$PROMO->idclient;
		$preference->external_reference = $this->_hash;

		$preference->back_urls = array(
			'success'=>ROOT.'pago-status/success?hash='.$this->_hash.'&promourl='.$promo_url.'&amount='.(($promoprice-$discountvoucher)*$quantity),
			'failure'=>ROOT.'pago-status/failure?hash='.$this->_hash.'&promourl='.$promo_url.'&amount='.(($promoprice-$discountvoucher)*$quantity),
			'pending'=>ROOT.'pago-status/pending?hash='.$this->_hash.'&promourl='.$promo_url.'&amount='.(($promoprice-$discountvoucher)*$quantity)
		);

		$preference->payment_methods = array(
			'excluded_payment_methods'=>array(),
			'excluded_payment_types'=>array(
				array("id"=>"ticket"),
				array("id"=>"atm")
			),
			'installments'=>null
		);

		$preference->save();

		$this->_mplink = $preference;
		


		/*try{
			$this->_mplink = $mp->create_preference($preference_data);
		}catch(MercadoPagoException $e){
			$this->_error = $e->getMessage();
			return false;
		}*/

		$Sales = new Sales();
		if(!$Sales->createtemp(array(
			'iduser'=>$USER->id,
			'idclient'=>$PROMO->idclient,
			'idpromo'=>$PROMO->id,
			'idcode'=>$idcode,
			'quantity'=>$quantity,
			'price'=>$PROMO->price-($PROMO->price*$PROMO->discount/100),
			'hash'=>$this->_hash,
			'added'=>date('Y-m-d H:i:s')
		))) return false;
			
		return $preference;
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

		$Notifications = new Notifications();

		$this->_db->query(
			"SELECT m.*, c.name, c.permalink
			FROM {mp} m
			LEFT JOIN {clients} c ON c.id=m.idclient
			{$where}
			");
		if(!$this->_db->count()) return false;


		foreach($this->_db->results() as $client){
			$request = array(
				"client_secret" => $this->secret_key,
				"client_id" => $this->app_id,
				"grant_type" => "refresh_token",
				"refresh_token" => $client->refresh_token
			);

			$response = curl_post('https://api.mercadopago.com/oauth/token',$request);
			$json = json_decode($response->response);
			if($response->status == 400) return false;

			$this->_db->update('mp',$client->id,array(
				'access_token'=>$json->access_token,
				'public_key'=>$json->public_key,
				'refresh_token'=>$json->refresh_token,
				'expires_in'=>$json->expires_in,
				'added'=>date('Y-m-d H:i:s')
			));

			$Notifications->add_log('Token de integración de MercadoPago renovado para <a href="'.ROOT.'centros/'.$client->permalink.'" target="_blank">'.$client->name.'</a>','token');

		}

		/*require PATH.'/mercadopago/mercadopago.php';
		foreach($this->_db->results() as $client){
			$mp = new MP($client->access_token);
			$request = array(
				"uri" => "/oauth/token",
				"data" => array(
					"client_secret" => $this->secret_key,
					"client_id" => $this->app_id,
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
		}*/

		return true;

	}



	public function get_access_token($idclient=0){
		$this->_db->get('mp',array('idclient','=',$idclient));
		if(!$this->_db->count()) return false;
		return $this->_db->first()->access_token;
	}


}