<?php

require_once '../config.php';
require 'templates-mail.php';
require 'phpmailer/PHPMailerAutoload.php';

if(!Input::exists()) die(json_encode(array('Status'=>'fail')));

$mailestilospa = "estilospa.com@gmail.com";
////$mailestilospa = "rodosoft@gmail.com";

switch (Input::get('Mode')) {
	
	case 'requestglossary':
		if(!filter_var(Input::get('Mail'),FILTER_VALIDATE_EMAIL)) die(json_encode(array('Status'=>'wrongmail')));
		$_MESSAGES = new Messages();
		$_CLIENTS = new Clients();
		$_GLOSSARY = new Glossary();
		$_GLOSSARY->find(Input::get('IDG'));
		$_NOTIFICATIONS = new Notifications();
		$_MESSAGES->save();
		//$idmessage = $_MESSAGES->getLastId();
		$_CLIENTS->arrglossary = array(Input::get('IDG'));
		$_CLIENTS->visible = 1;
		if($_CLIENTS->get()){
			foreach ($_CLIENTS->data() as $client){
				///$_MESSAGES->addqueue($idmessage,$client->id);
				$_NOTIFICATIONS->add(array(
					'name_from'=>Input::get('Name'),
					'email_from'=>strtolower(Input::get('Mail')),
					'name_to'=>$client->name,
					'email_to'=>strtolower($client->mail),
					'subject'=>'Nueva consulta desde EstiloSPA.com',
					'body'=>'<h4>Hola '.$client->name.', '.Input::get('Name').' se ha contactado a través de la página de EstiloSPA para hacer una consulta sobre '.$_GLOSSARY->data()->name.':</h4><p style="line-height:18pt"><b>Nombre:</b> '.Input::get('Name').'<br /><b>E-mail:</b> '.Input::get('Mail').'<br /><b>Teléfono:</b> '.Input::get('Phone').'<br /></p><p>'.Input::get('Message').'</p>',
					'added'=>date('Y-m-d H:i:s'),
				));
			}
		}
		echo json_encode(array('Status'=>'ok'));
		break;

	case 'publish':
		$mailer->addReplyTo(Input::get('Mail'), Input::get('Name'));
		$mailer->setFrom(Input::get('Mail'), Input::get('Name'));
		$MailBody  = '<h4>Hola, '.Input::get('Name').' está interesado en publicar su centro en EstiloSPA:</h4>
			<p style="line-height:18pt">
				<b>Nombre y Apellido:</b> '.Input::get('Name').'<br />
				<b>E-mail:</b> '.Input::get('Mail').'<br />
				<b>Web:</b> '.(empty(Input::get('Web')) ? 'no indicó' : Input::get('Web')).'<br />
				<b>Teléfono:</b> '.Input::get('Phone').'<br />
				<b>Empresa:</b> '.Input::get('Company').'<br />
				<b>¿Cómo nos conoció?:</b> '.(empty(Input::get('How')) ? 'No respondió' : Input::get('How')).'<br />				
			</p>
			<p>
				'.(empty(Input::get('Message')) ? 'No escribió ningún mensaje adicional' : Input::get('Message')).'
			</p>
			';
		$mailer->addAddress($mailestilospa, 'EstiloSPA.com');
		$mailer->Subject = 'Nuevo interesado en publicar en EstiloSPA.com';
		$mailer->Body = $MailHead.$MailBody.$MailFoot;
		if(!$mailer->send()) {
			die(json_encode(array('Status'=>'fail','Error'=>$mailer->ErrorInfo)));
		}
		echo json_encode(array('Status'=>'ok'));
		break;

	case 'contact':
		if(!filter_var(Input::get('Mail'),FILTER_VALIDATE_EMAIL)) die(json_encode(array('Status'=>'fail')));
		$mailer->addReplyTo(Input::get('Mail'), Input::get('Name'));
		//$mailer->setFrom(Input::get('Mail'), Input::get('Name'));
		$MailBody  = '<h4>Hola, '.Input::get('Name').' se ha contactado a través de la página de EstiloSPA:</h4>
			<p style="line-height:18pt">
				<b>Nombre:</b> '.Input::get('Name').'<br />
				<b>E-mail:</b> '.Input::get('Mail').'<br />
				<b>Teléfono:</b> '.Input::get('Phone').'<br />
			</p>
			<p>
				'.(empty(Input::get('Message')) ? 'No escribió ningún mensaje adicional' : Input::get('Message')).'
			</p>
			';
		$mailer->addAddress($mailestilospa, 'EstiloSPA.com');
		$mailer->Subject = Input::get('Subject');
		$mailer->Body = $MailHead.$MailBody.$MailFoot;
		if(!$mailer->send()) {
			die(json_encode(array('Status'=>'fail','Error'=>$mailer->ErrorInfo)));
		}
		echo json_encode(array('Status'=>'ok'));
		break;

	case 'requestpromo':
		$mailer->addReplyTo(Input::get('Mail'), Input::get('Name'));
		$mailer->setFrom(Input::get('Mail'), Input::get('Name'));
		
		$_PROMOS = new Promos();
		$_CLIENTS = new Clients();
		if(!$_PROMOS->find(Input::get('IDP'))) die(json_encode(array('Status'=>'fail')));
		if(!$_CLIENTS->find($_PROMOS->data()->idclient)) die(json_encode(array('Status'=>'fail')));

		$MailBody  = '<h4>Hola '.$_CLIENTS->data()->name.', '.Input::get('Name').' se ha contactado a través de la página de EstiloSPA para consultar acerca de esta promo:</h4>
			<p><a href="'.ROOT.'promo/'.$_CLIENTS->data()->permalink.'/'.$_PROMOS->data()->id.'-'.Permalink($_PROMOS->data()->title).'" >'.$_PROMOS->data()->title.'</a></p>
			<p style="line-height:18pt">
				<b>Nombre:</b> '.Input::get('Name').'<br />
				<b>E-mail:</b> '.Input::get('Mail').'<br />
				<b>Teléfono:</b> '.Input::get('Phone').'<br />
				<b>Día y Horario de Preferencia:</b> '.Input::get('PSchedule').' - '.Input::get('PDay').'<br />
			</p>
			<p>
				'.(empty(Input::get('Message')) ? 'No escribió ningún mensaje adicional' : Input::get('Message')).'
			</p>
			';
		$mailer->addAddress($_CLIENTS->data()->mail, $_CLIENTS->data()->name);
		$mailer->addBCC($mailestilospa);
		///$mailer->addBCC('rodosoft@hotmail.com', 'Rodo');
		$mailer->Subject = 'Consulta sobre una promo en EstiloSPA.com';
		$mailer->Body = $MailHead.$MailBody.$MailFoot;
		if(!$mailer->send()) {
			die(json_encode(array('Status'=>'fail','Error'=>$mailer->ErrorInfo)));
		}
		$_MESSAGES = new Messages();
		$_MESSAGES->save();
		echo json_encode(array('Status'=>'ok'));
		break;

	case 'requestclient':
		$mailer->addReplyTo(Input::get('Mail'), Input::get('Name'));
		$mailer->setFrom(Input::get('Mail'), Input::get('Name'));
		
		$_CLIENTS = new Clients();
		if(!$_CLIENTS->find(Input::get('IDC'))) die(json_encode(array('Status'=>'fail')));

		$MailBody  = '<h4>Hola '.$_CLIENTS->data()->name.', '.Input::get('Name').' se ha contactado a través de la página de EstiloSPA para hacer una consulta:</h4>			
			<p style="line-height:18pt">
				<b>Nombre:</b> '.Input::get('Name').'<br />
				<b>E-mail:</b> '.Input::get('Mail').'<br />
				<b>Teléfono:</b> '.Input::get('Phone').'<br />
			</p>
			<p>
				'.(empty(Input::get('Message')) ? 'No escribió ningún mensaje adicional' : Input::get('Message')).'
			</p>
			';
		$arrMails = str_replace(',', ';', $_CLIENTS->data()->mail);
		$arrMails = explode(';',$arrMails);
		foreach($arrMails as $mail){
			$mailer->addAddress(strtolower(trim($mail)), $_CLIENTS->data()->name);
		}
		//$mailer->addAddress($_CLIENTS->data()->mail, $_CLIENTS->data()->name);

		$mailer->addBCC($mailestilospa);
		//$mailer->addAddress('rodosoft@hotmail.com', 'Rodo');
		$mailer->Subject = 'Nueva consulta desde EstiloSPA.com';
		$mailer->Body = $MailHead.$MailBody.$MailFoot;
		if(!$mailer->send()){			
			die(json_encode(array('Status'=>'fail','Error'=>$mailer->ErrorInfo)));
		}
		$_MESSAGES = new Messages();
		$_MESSAGES->save();
		echo json_encode(array('Status'=>'ok'));
		break;

	case 'subscribe':

		$_subscribers = new Subscribers();

		if(!filter_var(Input::get('email'),FILTER_VALIDATE_EMAIL)) die(json_encode(array('status'=>'fail','message'=>'Ingresa un email válido')));
		if(!$_subscribers->verify(Input::get('email'))) die(json_encode(array('status'=>'fail','message'=>'<p class="alert alert-danger">El email ingresado ya se encuentra registrado en nuestra base de datos.</p>')));
		if(!$_subscribers->add(Input::get('email'))) die(json_encode(array('status'=>'fail','message'=>'<p class="alert alert-danger">Hubo problemas al procesar la solicitud. Intenta más tarde</p>')));

		echo json_encode(array('status'=>'ok','message'=>'<p class="alert alert-success">¡Gracias! Te has subscripto a nuestro Newsletter donde recibirás las mejores ofertas y promociones</p>'));
		break;
	
	default:
		echo json_encode(array('Status'=>'fail'));
		break;
}