<?php
require_once 'config.php';
require_once 'mercadopago/mercadopago.php';
require 'ajax/templates-mail.php';
require 'ajax/phpmailer/PHPMailerAutoload.php';
error_reporting(0);

$mp = new MP("APP_USR-7300466898804487-070519-065286686bbe9e2c819c57c7094d11da__LD_LC__-263157583");
$_SALES = new Sales();
$_VOUCHERS = new Vouchers();
$_PROMOS = new Promos();
$_CLIENTS = new Clients();
$_STORES = new Stores();

if (!isset($_GET["id"], $_GET["topic"]) || !ctype_digit($_GET["id"])) {
	http_response_code(400);
	return;
}

// Get the payment and the corresponding merchant_order reported by the IPN.

switch($_GET["topic"]) {
	case 'payment':
		$payment_info = $mp->get('/v1/payments/'.$_GET["id"]);
		if($payment_info["status"] == 200) {

			
			/*echo '<pre>';
			print_r($payment_info);
			echo '</pre>';
			die();*/

			//$external_reference = $payment_info["response"]['external_reference']; #obtengo el hash tempsales, de acá saco idpromo, iduser, price, quantity, voucher (id o descuento ??)
			$hash = $payment_info["response"]['external_reference'];;
			//$hash = '08c6578d8d450033563cb955cc9db572ff92fcdb64c3c0ff71fe4e51a65b771b';

			if($_SALES->findtemp($hash)){
				$idpromo = $_SALES->data()->idpromo;
				$iduser = $_SALES->data()->iduser;
				$quantity = $_SALES->data()->quantity;
				$price = $_SALES->data()->price;
				$idcode = $_SALES->data()->idcode;
				$_USER->find($iduser);

				$collection_id = $payment_info["response"]['id'];
				$payment_type = $payment_info["response"]['payment_type_id'];
				$merchant_order_id = $payment_info["response"]['order']['id'];
				$collection_status = $payment_info["response"]['status'];
				//echo $payment_info["response"]['transaction_amount'].'<br />';
				if($_PROMOS->find(intval($idpromo))){
					$_CLIENTS->find($_PROMOS->data()->idclient);
					if(!$_SALES->check($merchant_order_id)){

						///////////// INSERT SALE ///////////////////////
						$arrfields = array(
							'iduser'=>$iduser,
							'idpromo'=>$idpromo,
							'collection_id'=>$collection_id,
							'collection_status'=>$collection_status,
							'preference_id'=>'',
							'external_reference'=>'',
							'payment_type'=>$payment_type,
							'merchant_order_id'=>$merchant_order_id,	
							'price'=>$price,
							'added'=>date('Y-m-d H:i:s'),
							'quantity'=>$quantity,
							'hash'=>$hash
						);
						$_SALES->save($arrfields);
						///////// VOUCHER ////////////
						if($idcode){
							if($_VOUCHERS->findcode($idcode)){
								$idvoucher = $_VOUCHERS->data()->idvoucher;
								if($_VOUCHERS->find($idvoucher)){
									$sqlvoucher = array(
										'idvoucher'=>$idvoucher,
										'idcode'=>$idcode,
										'iduser'=>$iduser,
										'idsale'=>$_SALES->getLastId(),
										'ispercent'=>$_VOUCHERS->data()->ispercent,
										'value'=>$_VOUCHERS->data()->value,
										'added'=>date('Y-m-d H:i:s')
									);
									$_VOUCHERS->usage($sqlvoucher);
								}
							}
						}
						//////////////////////////////
						$_SALES->deletetemp($hash);
						$_PROMOS->discountAmount($idpromo,$quantity);
						$priceformat = number_format($price,2,',','.');
						////////////// MAILING /////////////////////////
						$_STORES->get($_CLIENTS->data()->id);
						$stores = '';
						if($_STORES->data()){
							foreach($_STORES->data() as $store){
								$stores .= '- '.$store->address.', '.$store->city.' - '.$store->name.' '.(!empty($store->phones) ? ' - Tel: '.$store->phones : '' ).(!empty($store->whatsapp) ? ' - Celular: '.$store->whatsapp : '' ).'<br />';
							}
						}
						////////////////// GIFT ////////////////////////////
						$MailBodyGift = '';
						$clientdatagift = '';
						$userdatagift = '';
						$isgift = false;
						if($_SALES->findgift($hash)){
							$isgift = true;
							$MailBodyGift = '<h2>¡Hola '.$_SALES->data()->touser.'!</h2>
							<h3>'.$_SALES->data()->fromuser.' te ha regalado la siguiente experiencia EstiloSPA!!!</h3>
							<div style="background-color:#dfdfdf;padding:16px;font-style:italic">'.$_SALES->data()->message.'</div>
							<hr>
							<p>A continuación te detallamos en qué consiste:</p>
							<br />
							<h4>'.$_PROMOS->data()->title.'</h4>
							<p>'.$_PROMOS->data()->description.'</p>
							<p>'.$_PROMOS->data()->includes.'</p>
							<hr>
							<p><b>Nro de Comprobante: '.$collection_id.'</b></p>
							<hr>
							<h4>Canjeable en:</h4>
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
							$clientdatagift = '
							<h4>El usuario ha regalado el servicio a:</h4>
							<ul>
								<li>Nombre: '.$_SALES->data()->touser.'</li>
								<li>E-mail: '.$_SALES->data()->mail.'</li>
							</ul>';
							$userdatagift = '
							<h4>Le regalaste este servicio a:</h4>
							<p>
								- Nombre: '.$_SALES->data()->touser.'<br />
								- E-mail: '.$_SALES->data()->mail.'
							</p>';
							///die($MailBodyGift);
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
						<p>Valor: '.$quantity.' x $ '.$priceformat.'</p>
						<p><b>Total: $ '.number_format($price*$quantity,2,',','.').'</b></p>
						<hr>
						'.$userdatagift.'
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
						'.$clientdatagift.'
						<hr>
						<h4>Datos de la promo:</h4>
						<p><a href="'.ROOTPATH.'promo/'.$_CLIENTS->data()->permalink.'/'.$idpromo.'-'.Permalink($_PROMOS->data()->title).'">'.$_PROMOS->data()->title.'</a></p>
						<p>'.$_PROMOS->data()->description.'</p>
						<hr>
						<p><b>Nro de Comprobante: '.$collection_id.'</b></p>
						<p>Valor: '.$quantity.' x $ '.$priceformat.'</p>
						<p><b>Total: $ '.number_format($price*$quantity,2,',','.').'</b></p>
						<br /><br />
						<hr>
						<p>
							Gracias.<br />
							El equipo de EstiloSPA.com
						</p>';
						/////////////////////////////////////////////////////////
						$mailer->Subject = 'Detalles de compra de '.$_PROMOS->data()->title;	
						$mailer->Body = $MailHead.$MailBodyUser.$MailFoot;
						$mailer->addAddress($_USER->data()->mail, $_USER->data()->name);
						//$mailer->addAddress('rodosoft@gmail.com','Rodo');
						$mailer->send();

						$mailer->ClearAllRecipients();
						$mailer->Subject = 'Nueva venta en EstiloSPA - Nro: '.$collection_id;
						$mailer->Body = $MailHead.$MailBodyClient.$MailFoot;
						$mailer->addAddress($_CLIENTS->data()->mail, $_CLIENTS->data()->name);
						$mailer->addBCC('estilospa.com@gmail.com');
						//$mailer->addAddress('rodosoft@hotmail.com', 'Rodo');
						$mailer->send();
						if($isgift){
							$mailer->ClearAllRecipients();
							$mailer->Subject = $_SALES->data()->fromuser.' te ha regalado esta promo!';
							$mailer->Body = $MailHead.$MailBodyGift.$MailFoot;
							$mailer->addAddress($_SALES->data()->mail, $_SALES->data()->touser);
							//$mailer->addAddress('rodosoft@hotmail.com', 'Rodo');
							$mailer->send();
						}
					}else{

						$_SALES->update($_SALES->data()->id,array(
							'collection_id'=>$collection_id,
							'collection_status'=>$collection_status,
							'payment_type'=>$payment_type,
							'merchant_order_id'=>$merchant_order_id,
							'added'=>date('Y-m-d H:i:s')
						));
					}
				}
			}
		}
		http_response_code($payment_info["status"]);
		break;

	case 'merchant_order':
		try{
			$payment_info = $mp->get('/merchant_orders/'.$_GET["id"]);
		}catch(MercadoPagoException $e){
			//print_r($e->getMessage());
			http_response_code(400);
			return;
		}
		if($payment_info["status"] == 200) {
			/*echo '<pre>';
			print_r($payment_info);
			echo '</pre>';*/
		}
		http_response_code($payment_info["status"]);
		break;
	
	default:
		http_response_code(400);
		break;
}