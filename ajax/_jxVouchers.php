<?php 

require_once '../config.php';
$Vouchers = new Vouchers();

ini_set('max_input_vars',5000);

if(!Input::exists()) die(json_encode(array('Status'=>'fail')));

switch (Input::get('Mode')) {

	case 'get':
		$Vouchers->status = Input::get('Status');		
		$Vouchers->idpromo = Input::get('IDP');
		$Vouchers->keywords = Input::get('Keywords');
		$Vouchers->get();
		echo json_encode(array('Status'=>'ok','Results'=>$Vouchers->data()));
		break;

	case 'find':
		$Vouchers->find(Input::get('ID'));
		$vouchers = $Vouchers->data();
		$Vouchers->getcodes(Input::get('ID'));
		$codes = $Vouchers->data();
		$Vouchers->getpromos(Input::get('ID'));
		$promos = $Vouchers->data();
		echo json_encode(array('Status'=>'ok','Result'=>$vouchers,'Codes'=>$codes,'Promos'=>$promos));
		break;

	case 'save':
		if(!$User->logged() && $User->data()->idtype != 1) die(json_encode(array('Status'=>'restricted')));
		if(!Input::check(array('Name','Value','Codes'))) die(json_encode(array('Status'=>'input')));
		
		$arrcodes = explode(',',Input::get('Codes'));
		if(count($arrcodes)==1 && !Input::get('ID')){
			if($Vouchers->findcode($arrcodes[0])){
				die(json_encode(array('Status'=>'code')));
			}
		}

		if(!$Vouchers->save()){
			die(json_encode(array('Status'=>'fail')));
		}
		echo json_encode(array('Status'=>'ok'));
		break;

	case 'delete':
		if(!$User->logged() && $User->data()->idtype != 1) die(json_encode(array('Status'=>'restricted')));
		if(!$Vouchers->delete(Input::get('ID'))) die(json_encode(array('Status'=>'fail')));
		echo json_encode(array('Status'=>'ok'));
		break;

	case 'validate':
		if(!$User->logged()) die(json_encode(array('Status'=>'restricted')));

		if(!$Vouchers->validate( Input::get('IDP'),Input::get('Code'), $User->data()->id )){
			die( json_encode(array( 'Status'=>'fail', 'Message'=>$Vouchers->errors() )) );
		}
		echo json_encode(array('Status'=>'ok', 'Result'=>$Vouchers->data()));
		break;

	case 'free':

		if(!$User->logged()) die(json_encode(array('Status'=>'restricted')));

		$iduser = $User->data()->id;
		$code = Input::get('Code');
		$idpromo = Input::get('IDP');
		$collection_id = rand(1111111111,3333333333);
		$merchant_order_id = rand(111111111,555555555);

		$_sales = new Sales();
		$_sales->save(array(
			'iduser'=>$iduser,
			'idpromo'=>$idpromo,
			'collection_id'=>$collection_id,
			'collection_status'=>'approved',
			'preference_id'=>'',
			'external_reference'=>'',
			'payment_type'=>'ticket',
			'merchant_order_id'=>$merchant_order_id,	
			'price'=>0,
			'added'=>date('Y-m-d H:i:s'),
			'quantity'=>1,
			'hash'=>hash('sha256', uniqid())
		));

		if(!$Vouchers->findcode($code)) die(json_encode(array('Status'=>'fail','voucher'=>$code)));
		$idvoucher = $Vouchers->data()->idvoucher;
		$idcode = $Vouchers->data()->id;
		if($Vouchers->find($idvoucher)){
			$sqlvoucher = array(
				'idvoucher'=>$idvoucher,
				'idcode'=>$idcode,
				'iduser'=>$iduser,
				'idsale'=>$_sales->getLastId(),
				'ispercent'=>$Vouchers->data()->ispercent,
				'value'=>$Vouchers->data()->value,
				'added'=>date('Y-m-d H:i:s')
			);
			$Vouchers->usage($sqlvoucher);
		}
		
		
		$_PROMOS = new Promos();
		if(!$_PROMOS->find(intval($idpromo))) die(json_encode(array('Status'=>'fail')));
		$_PROMOS->take_amount($idpromo,1);
		
		$_CLIENTS = new Clients();
		$_CLIENTS->find($_PROMOS->data()->idclient);
		
		$_STORES = new Stores();
		$_STORES->get($_CLIENTS->data()->id);
		$stores = '';
		if($_STORES->data()){
			foreach($_STORES->data() as $store){
				$stores .= '- '.$store->address.', '.$store->city.' - '.$store->name.' '.(!empty($store->phones) ? '(Tel: '.$store->phones.')' : '' ).'<br />';
			}
		}


		////////////////// USER //////////////////////////////
		$MailBodyUser  = '<h2>¡Hola '.$User->data()->name.'!</h2>
		<h3>Gracias por tu compra en EstiloSPA.com!!!</a></h3>
		<p>A continuación te detallamos tu compra:</p>
		<br />
		<h4>'.$_PROMOS->data()->title.'</h4>
		<p>'.$_PROMOS->data()->description.'</p>
		<p>'.$_PROMOS->data()->includes.'</p>
		<hr>
		<p><b>Nro de Comprobante: '.$collection_id.'</b></p>
		<p>Voucher: '.$code.'</p>
		<p><b>Total: $ 0,00.-</b></p>
		<hr>
		<h4>Datos del Centro:</h4>
		<p>
			<a href="'.ROOT.'centros/'.$_CLIENTS->data()->permalink.'">'.$_CLIENTS->data()->name.'</a><br />
			<small>Email: '.$_CLIENTS->data()->mail.'</small>
		</p>
		<h5>Dirección(es):</h5>
		'.$stores.'
		<br />
		<p>Recuerda comunicarte con el centro para poder confirmar tu compra y reservar el día y el horario del turno.</p>
		<hr>
		<p>
			Gracias.<br />
			El equipo de EstiloSPA.com
		</p>';
		////////////////// CLIENT ////////////////////////////
		$MailBodyClient  = '<h2>¡Hola '.$_CLIENTS->data()->name.'!</h2>
		<h3>Nueva venta en EstiloSPA.com!!!</a></h3><br />
		<h4>Datos del comprador:</h4>
		<ul>
			<li>Nombre completo: '.$User->data()->name.'</li>
			<li>E-mail: '.$User->data()->mail.'</li>
			<li>Teléfono: '.(empty($User->data()->phone) ? 'no indicó ninguno' : $User->data()->phone).'</li>
		</ul>
		<hr>
		<h4>Datos de la promo:</h4>
		<p><a href="'.ROOT.'promo/'.$_CLIENTS->data()->permalink.'/'.$idpromo.'-'.Permalink($_PROMOS->data()->title).'">'.$_PROMOS->data()->title.'</a></p>
		<p>'.$_PROMOS->data()->description.'</p>
		<hr>
		<p><b>Nro de Comprobante: '.$collection_id.'</b></p>
		<p>Voucher: '.$code.'</p>
		<p><b>Total: $ 0,00.-</b></p>
		<br /><br />
		<hr>
		<p>
			Gracias.<br />
			El equipo de EstiloSPA.com
		</p>';
		/////////////////////////////////////////////////////////
		require 'templates-mail.php';
		require 'phpmailer/PHPMailerAutoload.php';

		$mailer->Subject = 'Detalles de compra de '.$_PROMOS->data()->title;	
		$mailer->Body = $MailHead.$MailBodyUser.$MailFoot;
		$mailer->addAddress($User->data()->mail, $User->data()->name);
		//$mailer->addAddress('rodosoft@gmail.com','Rodo');
		if(!$mailer->send()) die(json_encode(array('Status'=>'fail')));

		$mailer->ClearAllRecipients();
		$mailer->Subject = 'Nueva venta en EstiloSPA - Nro: '.$collection_id;
		$mailer->Body = $MailHead.$MailBodyClient.$MailFoot;
		$mailer->addAddress($_CLIENTS->data()->mail, $_CLIENTS->data()->name);
		$mailer->addBCC('estilospa.com@gmail.com');
		//$mailer->addAddress('rodosoft@hotmail.com', 'Rodo');
		if(!$mailer->send()) die(json_encode(array('Status'=>'fail')));
		echo json_encode(array('Status'=>'ok'));
		break;

	default:
		echo json_encode(array('Status'=>'fail'));
		break;
}