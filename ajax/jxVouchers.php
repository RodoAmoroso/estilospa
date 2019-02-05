<?php 

require_once '../config.php';
$_VOUCHERS = new Vouchers();

ini_set('max_input_vars',5000);

if(!Input::exists()) die(json_encode(array('Status'=>'fail')));

switch (Input::get('Mode')) {

	case 'get':
		$_VOUCHERS->status = Input::get('Status');		
		$_VOUCHERS->idpromo = Input::get('IDP');
		$_VOUCHERS->keywords = Input::get('Keywords');
		$_VOUCHERS->get();
		echo json_encode(array('Status'=>'ok','Results'=>$_VOUCHERS->data()));
		break;

	case 'find':
		$_VOUCHERS->find(Input::get('ID'));
		$vouchers = $_VOUCHERS->data();
		$_VOUCHERS->getcodes(Input::get('ID'));
		$codes = $_VOUCHERS->data();
		$_VOUCHERS->getpromos(Input::get('ID'));
		$promos = $_VOUCHERS->data();
		echo json_encode(array('Status'=>'ok','Result'=>$vouchers,'Codes'=>$codes,'Promos'=>$promos));
		break;

	case 'save':
		if(!$_USER->logged() && $_USER->data()->idtype != 1) die(json_encode(array('Status'=>'restricted')));
		if(!Input::check(array('Name','Value','Codes'))) die(json_encode(array('Status'=>'input')));
		
		$arrcodes = explode(',',Input::get('Codes'));
		if(count($arrcodes)==1 && !Input::get('ID')){
			if($_VOUCHERS->findcode($arrcodes[0])){
				die(json_encode(array('Status'=>'code')));
			}
		}

		if(!$_VOUCHERS->save()){
			die(json_encode(array('Status'=>'fail')));
		}
		echo json_encode(array('Status'=>'ok'));
		break;

	case 'delete':
		if(!$_USER->logged() && $_USER->data()->idtype != 1) die(json_encode(array('Status'=>'restricted')));
		if(!$_VOUCHERS->delete(Input::get('ID'))) die(json_encode(array('Status'=>'fail')));
		echo json_encode(array('Status'=>'ok'));
		break;

	case 'validate':
		if(!$_USER->logged()) die(json_encode(array('Status'=>'restricted')));

		if(!$_VOUCHERS->validate( Input::get('IDP'),Input::get('Code'), $_USER->data()->id )){
			die( json_encode(array( 'Status'=>'fail', 'Message'=>$_VOUCHERS->errors() )) );
		}
		echo json_encode(array('Status'=>'ok', 'Result'=>$_VOUCHERS->data()));
		break;

	case 'free':

		if(!$_USER->logged()) die(json_encode(array('Status'=>'restricted')));

		$iduser = $_USER->data()->id;
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

		if(!$_VOUCHERS->findcode($code)) die(json_encode(array('Status'=>'fail','voucher'=>$code)));
		$idvoucher = $_VOUCHERS->data()->idvoucher;
		$idcode = $_VOUCHERS->data()->id;
		if($_VOUCHERS->find($idvoucher)){
			$sqlvoucher = array(
				'idvoucher'=>$idvoucher,
				'idcode'=>$idcode,
				'iduser'=>$iduser,
				'idsale'=>$_sales->getLastId(),
				'ispercent'=>$_VOUCHERS->data()->ispercent,
				'value'=>$_VOUCHERS->data()->value,
				'added'=>date('Y-m-d H:i:s')
			);
			$_VOUCHERS->usage($sqlvoucher);
		}
		
		
		$_PROMOS = new Promos();
		if(!$_PROMOS->find(intval($idpromo))) die(json_encode(array('Status'=>'fail')));
		$_PROMOS->discountAmount($idpromo,1);
		
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
		$MailBodyUser  = '<h2>¡Hola '.$_USER->data()->name.'!</h2>
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
			<a href="'.ROOTPATH.'centros/'.$_CLIENTS->data()->permalink.'">'.$_CLIENTS->data()->name.'</a><br />
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
			<li>Nombre completo: '.$_USER->data()->name.'</li>
			<li>E-mail: '.$_USER->data()->mail.'</li>
			<li>Teléfono: '.(empty($_USER->data()->phone) ? 'no indicó ninguno' : $_USER->data()->phone).'</li>
		</ul>
		<hr>
		<h4>Datos de la promo:</h4>
		<p><a href="'.ROOTPATH.'promo/'.$_CLIENTS->data()->permalink.'/'.$idpromo.'-'.Permalink($_PROMOS->data()->title).'">'.$_PROMOS->data()->title.'</a></p>
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
		$mailer->addAddress($_USER->data()->mail, $_USER->data()->name);
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