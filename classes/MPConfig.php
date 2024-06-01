<?php

require PATH.'/vendor/autoload.php';

class MPConfig extends Core{

	/*
	Mastercard	5031755734530604	123	11/25
	Visa	4509953566233704 123	11/25
	American Express	371180303257522 1234	11/25

	APRO	Pago aprobado	(DNI) 12345678
	OTHE	Rechazado por error general	(DNI) 12345678
	CONT	Pendiente de pago	-
	CALL	Rechazado con validación para autorizar	-
	FUND	Rechazado por importe insuficiente	-
	SECU	Rechazado por código de seguridad inválido	-
	EXPI	Rechazado debido a un problema de fecha de vencimiento	-
	FORM	Rechazado debido a un error de formulario
	*/



	/*
	COMPRADOR
	TESTUSER1954123393
	trhzv6Fbyb


	VENDEDOR
	TESTUSER145336738
	T2oLL5dzk2


	TEST USER MAIN
	{
    "id": 1734170887,
    "email": "test_user_1259780165@testuser.com",
    "nickname": "TESTUSER1259780165",
    "site_status": "active",
    "password": "PM5I7vfe1D"
	}

	TEST USER CLIENT
	{
    "id": 1734172367,
    "email": "test_user_1938095197@testuser.com",
    "nickname": "TESTUSER1938095197",
    "site_status": "active",
    "password": "69pqC3D0NP"
}

	*/


	public 	$arrfields=array(),
					$idclient=0,
					$redirect_uri=ROOT.'mp',


					//Producción
					$notification_url = ROOT.'ipn.php',
					$access_token='APP_USR-7300466898804487-070519-065286686bbe9e2c819c57c7094d11da__LD_LC__-263157583',
					$app_id='7300466898804487',
					$public_key='APP_USR-43830fea-2de3-4976-86ca-08ed0494b311',
					$secret_key='4Y7yVlsccQUmJM3ExQT59JioiKPK113K';



					//Test Localhost Bricks
					/*$notification_url = 'https://webhook.site/4ed11692-870d-461f-8d06-df3d439bad71',
					$access_token='TEST-389403748152273-070520-1890d82af8a41b80904fb788b903ccdd__LD_LB__-263157583',
					$public_key='TEST-16b8dfa7-44d1-4aba-9b04-d9a7c5cf53ab',
					$app_id='389403748152273',
					$secret_key='YVDiCxOKBqhOZ4Y6bdbWYd2PLTFan8rj';*/
					//Test Localhost RODO
					/*$notification_url = 'https://webhook.site/e4b8577b-3b88-4eb5-a1f1-516fca6a7486',
					$access_token='TEST-8912612574179921-062914-214e0db738393b77599faf197d6e1682__LB_LD__-89899659',
					$public_key='TEST-97e1dc09-512d-4118-9c27-631b539d5aa0',
					$app_id='8912612574179921',
					$secret_key='mDMvtgjLASrGDnSYDNxQkqSdj8SaXH46';*/


					//Test Demo
					//$notification_url = ROOT.'ipn.php',
					//$app_id='7030611358224519',
					//$secret_key='5ziaNn6vMrN4FR1xodfDgfqvJT4RnLVN',
					//$access_token='APP_USR-7030611358224519-050401-40a4130219ec8743f65509dc8a65f78d-417751838';
					///código de acceso a la cuenta:751838

					// Test SpaEstilo
					//$app_id='4678134710817612',
					//$secret_key='UOf6fadyymoUTHv93Hqstk2iLHDUtOdC',
					//$access_token='APP_USR-4678134710817612-053114-9f39c1925fe2b0c9e0aac0756a7c231a-417751838';


	public function __construct($access_token=false){
		//$this->_dbprefix = Config::get('mysql/prefix');
		//$this->_db = DB::getInstance();

		///
		if(!$access_token) MercadoPago\SDK::setAccessToken($this->access_token);

		$this->Promos = new Promos;
		$this->Clients = new Clients;
		$this->Sales = new Sales;
		$this->Users = new Users;
		$this->MPErrors = new MPErrors;
		///$this->set_hash();
		parent::__construct();
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


		$this->_hash = hash('sha256', date('YmdHis').rand(1111,9999));
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


		//$mp = new MP($this->_data->access_token); // seller access_token

		//$mp = new MP('8912612574179921','mDMvtgjLASrGDnSYDNxQkqSdj8SaXH46'); // seller access_token
		//MercadoPago\SDK::setIntegratorId("dev_28f49a44e7ed11eab4a00242ac130004");

		$client_accesstoken = $this->get_access_token($CLIENTS->id);
		MercadoPago\SDK::setAccessToken($client_accesstoken);
		//MercadoPago\SDK::setClientSecret($this->secret_key);

		$promo_url = ROOT.'promo/'.$PROMO->permalink.'/'.$PROMO->id.'-'.Permalink($PROMO->title);


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

		$preference->notification_url = $this->notification_url.'?idclient='.$PROMO->idclient;

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
		//show_array($preference);



		/*try{
			$this->_mplink = $mp->create_preference($preference_data);
		}catch(MercadoPagoException $e){
			$this->_error = $e->getMessage();
			return false;
		}*/

		$Sales = new Sales();
		if(!$Sales->create_temp(array(
			'iduser'=>$USER->id,
			'idclient'=>$PROMO->idclient,
			'idpromo'=>$PROMO->id,
			'idcode'=>$idcode,
			'reservationid'=>Input::get('reservationid'),
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

		$this->_db->query("
			SELECT m.*, c.name, c.permalink
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

			if($response->status == 400){
				$Notifications->add_log('Se ha desvinculado la integración de MercadoPago de <a href="'.ROOT.'centros/'.$client->permalink.'" target="_blank">'.$client->name.'</a>','token');
				$this->_db->delete('mp',['id','=',$client->id]);
				return false;
			}

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
		if(!$mp_client = $this->get_mp_client($idclient)) return false;
		return $mp_client->access_token;
	}
	public function get_mp_client($idclient=0){
		$this->_db->get('mp',array('idclient','=',$idclient));
		if(!$this->_db->count()) return false;
		return $this->_db->first();
	}


	public function get_fees($fees=array()){
		if(!is_array($fees) || empty($fees)) return false;

		$output = new stdClass();
		foreach ($fees as $fee) {
			if($fee->type=='mercadopago_fee'){
				$output->mercadopago_fee = $fee->amount;
			}
			if($fee->type=='application_fee'){
				$output->application_fee = $fee->amount;
			}
		}
		return $output;
	}





	///new methods
	public function create_preference(){

		global $User;

		// Crear un objeto de preferencia
		$sale_temp = false;
		if(!$sale_temp = $this->Sales->find_temp(Cookie::get('sale_hash'))) return false;
		$this->_hash = $sale_temp->hash;


		if(!$this->Promos->find(Input::get('promoid'))) return false;
		$promo = $this->Promos->data();

		if(!$this->Clients->find($promo->idclient)) return false;
		$client = $this->Clients->data();


		if(!$user = $this->Users->find($User->data()->id)) return false;

		unset($user->pass);



		if($mp_client = $this->get_mp_client($promo->idclient)){
			///echo_json($mp_client);
			MercadoPago\SDK::setAccessToken($mp_client->access_token);
			$this->public_key = $mp_client->public_key;
		}
		MercadoPago\SDK::setIntegratorId("dev_28f49a44e7ed11eab4a00242ac130004");


		//$total_price = Input::get('subtotal','float')*Input::get('quantity','int');

		///echo_json($sale_temp);

		// Crear un elemento en la preferencia
		$item = new MercadoPago\Item();
		$item->id = $promo->id;
		$item->title = $promo->title." - ".$promo->clientname;
		$item->quantity = $sale_temp ? $sale_temp->quantity : 1;
		$item->currency_id = "ARS";
		$item->unit_price = $sale_temp ? $sale_temp->subtotal : $promo->price_w_discount;
		$item->category_id = 'services';

		$preference = new MercadoPago\Preference();
		$preference->items = array($item);
		// el $preference->purpose = 'wallet_purchase'; solo permite pagos registrados
		// para permitir pagos de guests, puede omitir esta propiedad
		//$preference->purpose = 'wallet_purchase';


		/// Crear vencimiento para el pago
		$now = new DateTimeImmutable;
		$preference->expires = true;
		$preference->expiration_date_from = $now->format('c');
		$preference->expiration_date_to = $now->modify('+1 hours')->format('c');

		if($mp_client){
			$preference->marketplace_fee = (float) $client->fee*($sale_temp ? $sale_temp->total : $promo->price_w_discount)/100;
		}


		//$preference->notification_url = ROOT.'ipn.php';
		$preference->notification_url = $this->notification_url.'?idclient='.$promo->idclient;
		$preference->external_reference = $this->_hash;
		$preference->back_urls = array(
			'success'=>ROOT.'pago-status/success/'.$this->_hash,
			'failure'=>ROOT.'pago-status/failure/'.$this->_hash,
			'pending'=>ROOT.'pago-status/pending/'.$this->_hash
		);
		$preference->auto_return = "approved";
		$preference->binary_mode = true;

		$preference->save();

		return [
			'id'=>$preference->id,
			'init_point'=>$preference->init_point,
			'marketplace_fee'=>$preference->marketplace_fee,
			'external_reference'=>$this->_hash,
			'promo'=>$promo,
			'user'=>$user,
			'sale'=>$sale_temp,
			'public_key'=>$this->public_key
		];

	}
	public function create_payment(){

		global $User;
		if(!$User->logged()) {
			$this->response = Responses::get_message('require_login');
			return false;
		}
		$userdata = $User->data();

		//echo_json(Input::get_all());


		if(!$this->Promos->find(Input::get('promoid'))){
			$this->response = 'No se ha encontrado la experiencia.';
			return false;
		}
		$promo = $this->Promos->data();

		if(!$this->Clients->find($promo->idclient)) {
			$this->response = 'No se ha encontrado el centro.';
			return false;
		}
		$client = $this->Clients->data();

		$hash = Input::get('preference')['external_reference'];
		if(!$this->Sales->find_temp($hash)) {
			$this->response = 'No se ha podido procesar el pago. Intenta nuevamente.';
			return false;
		}
		$sale_temp = $this->Sales->data();



		if($client_access_token = $this->get_access_token($promo->idclient)){
			MercadoPago\SDK::setAccessToken($client_access_token);
		}
		MercadoPago\SDK::setIntegratorId("dev_28f49a44e7ed11eab4a00242ac130004");



		$payment = new MercadoPago\Payment();
		$payment->transaction_amount = (float) Input::get('formData')['transaction_amount'];
		$payment->token = Input::get('formData')['token'];
		$payment->installments = Input::get('formData')['installments'] ? (int) Input::get('formData')['installments'] : 1;
		$payment->payment_method_id = Input::get('formData')['payment_method_id'];
		$payment->issuer_id = Input::get('formData')['issuer_id'];

		$payment->external_reference = $hash;
		$payment->description = $promo->title." - ".$promo->clientname;

		$payer = new MercadoPago\Payer();
		$payer->email = Input::get('formData')['payer']['email'];
		$payer->identification = array(
		  "type" => Input::get('formData')['payer']['identification']['type'],
		  "number" => Input::get('formData')['payer']['identification']['number']
		);

		$payment->payer = $payer;

		if($client_access_token){
			$payment->application_fee = (float) $client->fee*($sale_temp ? $sale_temp->total : $promo->price_w_discount)/100;
		}


		///echo_json(Input::get_all());

		try {
			$payment->save();
		} catch (Exception $e) {
			$this->response = '<h4>No pudimos procesar el pago. Recarga la página e intenta nuevamente.</h4>';
			$this->MPErrors->save([
				'error'=>json_encode(['message'=>$e->getMessage()]),
				'sale'=>json_encode($sale_temp),
				'input'=>json_encode(Input::get_all())
			]);
			return false;
		}

		if($payment->Error()){
			$this->response = '<h4>No pudimos procesar el pago. Recarga la página e intenta nuevamente.</h4>';
			$this->response .= 'Ref: '.$payment->Error()->message;
			///echo_json($payment->Error());
			$this->MPErrors->save([
				'error'=>json_encode($payment->Error()),
				'sale'=>json_encode($sale_temp),
				'input'=>json_encode(Input::get_all())
			]);

			//renew token
			if($payment->Error()->message == 'Unauthorized use of live credentials'){
				///$this->renewtoken($client->id);
				///DESVINCULO
				$Notifications->add_log('Se ha desvinculado la integración de MercadoPago de <a href="'.ROOT.'centros/'.$client->permalink.'" target="_blank">'.$client->name.'</a>','token');
				$this->_db->delete('mp',['id','=',$client->id]);
				$Mailing = new Mailing;
				$Mailing->unlink_mp($client);

			}
			return false;
		}

		///echo_json($payment->status);

		if($payment->status!='approved' && $payment->status!='in_process'){
			$this->response = '<h4>No pudimos procesar el pago. Recarga la página e intenta nuevamente.</h4>';
			$this->response .= '<p><b>'.$payment->status.'</b> '.$payment->Error().'</p>';
			$this->MPErrors->save([
				'error'=>json_encode(['status'=>$payment->status]),
				'sale'=>json_encode($sale_temp),
				'input'=>json_encode(Input::get_all())
			]);
			return false;
		}
		if($payment->status=='in_process'){
			return [
				'status'=>$payment->status,
				'status_detail'=>$payment->status_detail,
				'id'=>$payment->id,
				'url_thanks'=>ROOT.'pago-status/pending/'.$hash
			];
		}


		// APPROVED

		$output = [
			'status'=>$payment->status,
			'status_detail'=>$payment->status_detail,
			'id'=>$payment->id,
			'hash'=>$payment->external_reference,
			'url_thanks'=>ROOT.'pago-status/success/'.$hash
		];

		$this->Sales->process_sale((object) [
			'payment'=>$payment,
			'promo'=>$promo,
			'user'=>$userdata,
			'client'=>$client,
			'sale_temp'=>$sale_temp
		]);
		return $output;

	}
	public function get_response(){
		return $this->response;
	}




}