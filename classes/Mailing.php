<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class Mailing {

	public 	$_mailer,
					$_email='rodosoft@hotmail.com',
					//$_email='estilospa.com@gmail.com',
					$_fullname='EstiloSPA',
					$_error,
					$_notifications,
					$_newsletters;

	public function __construct(){
		require PATH.'vendor'.DS.'autoload.php';
		$this->_mailer = new PHPMailer(true);
		$this->_mailer->SMTPDebug = false;
		$this->_mailer->CharSet = 'UTF-8';
		$this->_mailer->isSMTP();
		$this->_mailer->Host = 'warao.lineadns.com';
		$this->_mailer->SMTPAuth = true;
		$this->_mailer->Username = 'noresponder@estilospa.com';
		$this->_mailer->Password = 'hOFSVeoUC7mu';
		$this->_mailer->SMTPSecure = 'ssl';
		$this->_mailer->Port = 465;
		$this->_mailer->setFrom('noresponder@estilospa.com',$this->_fullname);
		$this->_mailer->addReplyTo('consultas@estilospa.com',$this->_fullname);
		$this->_mailer->isHTML(true);

		$this->_notifications = new Notifications();
		$this->_newsletters = new Newsletters();
	}
	public function get_email_name(){
		$output = new stdClass();
		$output->email = $this->_email;
		$output->name = $this->_fullname;
		return $output;
	}

	public function send($body){
		$this->_mailer->Body = Templates::template('email',$body);
		try {
			$this->_mailer->send();
		}catch(Exception $e){
			$this->_error = $this->_mailer->ErrorInfo;
			return false;
		}
		return true;
	}


	public function register($user=null){
		if(!is_object($user)) return false;

		$this->_mailer->addAddress($user->mail, $user->name);
		$this->_mailer->Subject = 'Registro nuevo usuario en EstiloSPA.com';

		$body = Templates::template('users/register',$user);
		if(!$this->send($body)) return false;

		return true;

	}


	public function reset_password($user=null){
		if(is_null($user)) return false;

		$this->_mailer->addAddress($user->mail, $user->name);
		$this->_mailer->Subject = 'Recuperar acceso en EstiloSPA.com';

		$body = Templates::template('users/reset-password',$user);
		if(!$this->send($body)) return false;

		return true;

	}

	public function publish(){

		$user = (object) Input::get_all();

		$this->_mailer->addAddress($this->_email, $this->_fullname);
		$this->_mailer->clearReplyTos();
		$this->_mailer->addReplyTo($user->email, $user->fullname);
		$this->_mailer->Subject = 'Nuevo interesado en publicar en EstiloSPA.com';

		$body = Templates::template('contact/publish',$user);
		if(!$this->send($body)) return false;

		return true;
	}


	public function contact(){
		$user = (object) Input::get_all();

		$this->_mailer->addAddress($this->_email, $this->_fullname);
		$this->_mailer->clearReplyTos();
		$this->_mailer->addReplyTo($user->email, $user->fullname);
		$this->_mailer->Subject = $user->subject;

		$body = Templates::template('contact/contact',$user);
		if(!$this->send($body)) return false;

		return true;
	}


	public function question($questionid=0){
		if(!$questionid) return false;


		$Questions = new Questions();
		$User = new User();
		$Promos = new Promos();
		$Glossary = new Glossary();
		$Clients = new Clients();

		if(!$question = $Questions->get($questionid)) return false;

		if(!$User->find($question->userid)) return false;
		$user = $User->data();


		$obj = new stdClass();
		$obj->user_name = $user->name;
		$obj->question = $question->message;
		$obj->questionid = $questionid;
		$obj->question_date = $question->creado;

		switch ($question->type) {

			case 'promos':

				if(!$Promos->find($question->rowid)) return false;
				$promo = $Promos->data();
				if(!$Clients->find($promo->idclient)) return false;
				$client = $Clients->data();

				$obj->promo_link = ROOT.'promo/'.$client->permalink.'/'.$promo->id.'-'.Permalink($promo->title);
				$obj->promo_title = $promo->title;

				$obj->client = $client;
				$body = Templates::template('questions/question-promo',$obj);

				$arrMails = str_replace(',', ';', $client->mail);
				$arrMails = explode(';',$arrMails);
				foreach($arrMails as $mail){
					$this->_mailer->addAddress(strtolower(trim($mail)), $client->name);
				}
				//$this->_mailer->addAddress($this->_email, $this->_fullname);
				//$this->_mailer->clearReplyTos();
				//$this->_mailer->addReplyTo($this->_email, $this->_fullname);
				$this->_mailer->Subject = 'Te hicieron una pregunta en EstiloSPA.com';
				if(!$this->send($body)) return false;

				$this->_notifications->add_log('Nueva pregunta enviada de '.$user->name.' '.$user->lastname.' ('.$user->mail.') en la promo <a href="'.$obj->promo_link.'#questions" target="_blank">'.$promo->title.'</a>','question');


				break;

			case 'clients':

				if(!$Clients->find($question->rowid)) return false;
				$client = $Clients->data();
				$obj->client = $client;

				$body = Templates::template('questions/question-client',$obj);

				$arrMails = str_replace(',', ';', $client->mail);
				$arrMails = explode(';',$arrMails);
				foreach($arrMails as $mail){
					$this->_mailer->addAddress(strtolower(trim($mail)), $client->name);
				}
				///$this->_mailer->addAddress($this->_email, $this->_fullname);
				///$this->_mailer->clearReplyTos();
				///$this->_mailer->addReplyTo($this->_email, $this->_fullname);
				$this->_mailer->Subject = 'Te hicieron una pregunta en EstiloSPA.com';
				if(!$this->send($body)) return false;

				$this->_notifications->add_log('Nueva pregunta enviada de '.$user->name.' '.$user->lastname.' ('.$user->mail.') en el centro <a href="'.ROOT.'centros/'.$client->permalink.'#questions" target="_blank">'.$client->name.'</a>','question');

				break;

			case 'glossary':

				if(!$clients = $Glossary->get_clients($question->rowid)) return false;
				if(!$Glossary->find($question->rowid)) return false;
				$glossary = $Glossary->data();

				$obj->glossary_link = ROOT.'etiqueta/'.$glossary->id.'-'.Permalink($glossary->name);
				$obj->glossary_name = $glossary->name;

				foreach($clients as $client){
					$arrMails = str_replace(',', ';', $client->mail);
					$arrMails = explode(';',$arrMails);

					$obj->client = $client;

					foreach($arrMails as $mail){
						$this->_notifications->add(array(
							'name_from'=>$user->name.' '.$user->lastname,
							'email_from'=>$user->mail,
							'name_to'=>$client->name,
							'email_to'=>strtolower($mail),
							'subject'=>"Te hicieron una pregunta en EstiloSPA.com",
							'body'=>Templates::template('questions/question-glossary',$obj),
							'log'=>'Notificación de nueva pregunta enviada a <a href="'.ROOT.'centros/'.$client->permalink.'" target="_blank">'.$client->name.'</a> con la etiqueta <a href="'.$obj->glossary_link.'" target="_blank">'.$glossary->name.'</a>',
							'type'=>'question',
							'added'=>date('Y-m-d H:i:s'),
						));
					}
					//$Questions->add($questionid,$client->id);
				}

				$this->_notifications->add_log('Nueva pregunta enviada de '.$user->name.' '.$user->lastname.' ('.$user->mail.') a la etiqueta <a href="'.$obj->glossary_link.'#questions" target="_blank">'.$glossary->name.'</a>','question');

				break;

		}

		return true;
	}


	public function response($responseid=0){
		if(!$responseid) return false;


		$Questions = new Questions();
		$User = new User();
		$Promos = new Promos();
		$Clients = new Clients();
		$Glossary = new Glossary();
		$Assoc = new Assoc();

		if(!$response = $Questions->get_response($responseid)) return false;
		if(!$question = $Questions->get($response->messageid)) return false;

		if(!$User->find($question->userid)) return false;
		$user = $User->data();

		$obj = new stdClass();
		$obj->user_name = $user->name.' '.$user->lastname;
		$obj->question = $question->message;
		$obj->question_date = $question->creado;
		$obj->response = $response->message;
		$obj->response_date = $response->creado;

		switch ($question->type) {
			case 'promos':
				if(!$Promos->find($question->rowid)) return false;
				$promo = $Promos->data();
				if(!$Clients->find($promo->idclient)) return false;
				$client = $Clients->data();

				$obj->client = $client;
				$obj->promo = $promo;

				$obj->client_link = ROOT.'centros/'.$client->permalink;
				$obj->promo_link = ROOT.'promo/'.$client->permalink.'/'.$promo->id.'-'.Permalink($promo->title);
				$obj->promo_title = $promo->title;
				$body = Templates::template('questions/response-promo',$obj);

				$this->_mailer->addAddress($user->mail, $user->name);
				//$this->_mailer->addAddress($this->_email, $this->_fullname);
				$this->_mailer->Subject = 'Respuesta de '.$promo->title;

				$this->_notifications->add_log('Nueva respuesta enviada a '.$user->name.' '.$user->lastname.' ('.$user->mail.') en la promo <a href="'.ROOT.'promo/'.$promo->permalink.'/'.$promo->id.'-'.Permalink($promo->title).'#questions" target="_blank">'.$promo->title.'</a>','question');

				break;

			case 'clients':
				if(!$Clients->find($question->rowid)) return false;
				$client = $Clients->data();

				$obj->client_link = ROOT.'centros/'.$client->permalink;
				$obj->client = $client;

				$body = Templates::template('questions/response-client',$obj);

				$this->_mailer->addAddress($user->mail, $user->name);
				//$this->_mailer->addAddress('rodosoft@hotmail.com', 'Rodo');
				$this->_mailer->Subject = 'Respondieron tu pregunta en EstiloSPA.com';

				$this->_notifications->add_log('Nueva respuesta enviada a '.$user->name.' '.$user->lastname.' ('.$user->mail.') en el centro <a href="'.ROOT.'centros/'.$client->permalink.'#questions" target="_blank">'.$client->name.'</a>','question');

				break;

			case 'glossary':
				if(!$Glossary->find($question->rowid)) return false;
				$glossary = $Glossary->data();

				$obj->glossary_link = ROOT.'etiqueta/'.$glossary->id.'-'.Permalink($glossary->name);
				$obj->glossary_name = $glossary->name;

				$Assoc->iduser = $response->userid;
				$Assoc->client_user('get');
				if(!$assoc = $Assoc->data()) return false;
				if(!$Clients->find($assoc[0]->idclient)) return false;
				$obj->client = $Clients->data();

				$obj->client_link = ROOT.'centros/'.$obj->client->permalink;

				$body = Templates::template('questions/response-glossary',$obj);

				$this->_mailer->addAddress($user->mail, $user->name);
				//$this->_mailer->addAddress($this->_email, $this->_fullname);
				$this->_mailer->Subject = 'Respondieron tu pregunta en EstiloSPA.com';

				$this->_notifications->add_log('Nueva respuesta enviada a '.$user->name.' '.$user->lastname.' ('.$user->mail.') en la etiqueta <a href="'.ROOT.'etiqueta/'.$glossary->id.'-'.Permalink($glossary->name).'" target="_blank">'.$glossary->name.'</a>','question');

				break;

		}

		//$this->_mailer->clearReplyTos();
		//$this->_mailer->addReplyTo($this->_email, $this->_fullname);
		if(!$this->send($body)) return false;

		return true;
	}



	public function new_user($user=null){
		if(!is_object($user)) return false;

		$this->_mailer->addAddress($user->mail, $user->name);
		$this->_mailer->Subject = 'Registro nuevo usuario en EstiloSPA.com';

		$body = Templates::template('admin/new-user',$user);
		if(!$this->send($body)) return false;

		return true;
	}


	public function change_plan($user=null){
		if(!is_object($user)) return false;

		$this->_mailer->addAddress($this->_email, $this->_fullname);
		$this->_mailer->Subject = 'Cambio de Plan';

		$body = Templates::template('panel/change-plan',$user);
		if(!$this->send($body)) return false;

		return true;
	}


	public function sales_success_user($obj=null){
		if(!is_object($obj)) return false;

		$this->_mailer->addAddress($obj->useremail, $obj->username);
		$this->_mailer->Subject = 'Detalles de compra de '.$obj->title;

		$body = Templates::template('sales/success-user',$obj);
		if(!$this->send($body)) return false;


		$this->_notifications->add_log('Nueva compra de '.$obj->username.' ('.$obj->useremail.'). Promo: <a href="'.$obj->promolink.'" target="_blank">'.$obj->title.'</a>','sale');

		return true;
	}
	public function sales_success_client($obj=null){
		if(!is_object($obj)) return false;

		$this->_mailer->clearAllRecipients();

		//$this->_mailer->addAddress($obj->clientemail, $obj->clientname);
		$arrMails = str_replace(',', ';', $obj->clientemail);
		$arrMails = explode(';',$arrMails);
		foreach($arrMails as $mail){
			$this->_mailer->addAddress(strtolower(trim($mail)), $obj->clientname);
		}

		$this->_mailer->addBCC($this->_email, $this->_fullname);
		//$this->_mailer->addAddress($this->_email, $this->_fullname);
		$this->_mailer->Subject = 'Nueva venta en EstiloSPA.com - Nro: '.$obj->title;

		$body = Templates::template('sales/success-client',$obj);
		if(!$this->send($body)) return false;

		return true;
	}
	public function sales_success_gift($obj=null){
		if(!is_object($obj)) return false;

		$this->_mailer->clearAllRecipients();

		$this->_mailer->addAddress($obj->gift->to_user->mail, $obj->gift->to_user->name.' '.$obj->gift->to_user->lastname);
		$this->_mailer->Subject = $obj->gift->from_user->name.' te ha regalado esta promo desde EstiloSPA.com!';

		$body = Templates::template('sales/success-gift',$obj);
		if(!$this->send($body)) return false;

		return true;
	}


	public function sales_pending($obj=null){
		if(!is_object($obj)) return false;

		$this->_mailer->addAddress($obj->useremail, $obj->username);
		//$this->_mailer->addAddress($this->_email, $this->_fullname);
		$this->_mailer->Subject = 'Compra en EstiloSPA.com - '.$obj->title;

		$body = Templates::template('sales/pending-user',$obj);
		if(!$this->send($body)) return false;

		return true;
	}
	public function sales_rejected($obj=null){
		if(!is_object($obj)) return false;

		$this->_mailer->addAddress($obj->useremail, $obj->username);
		//$this->_mailer->addAddress($this->_email, $this->_fullname);
		$this->_mailer->Subject = 'Compra en EstiloSPA.com - '.$obj->title;

		$body = Templates::template('sales/rejected-user',$obj);
		if(!$this->send($body)) return false;

		return true;
	}


	public function notifications($obj=null){
		if(!is_object($obj)) return false;

		$this->_mailer->Username = 'noreply@estilospa.com';
		$this->_mailer->Password = 'G6qAqJaMNSwC';
		$this->_mailer->setFrom('noreply@estilospa.com',$this->_fullname);

		$this->_mailer->clearAllRecipients();
		$this->_mailer->addAddress($obj->email_to, $obj->name_to);
		//$this->_mailer->addAddress($this->_email, $this->_fullname);
		$this->_mailer->Subject = $obj->subject;
		if(!$this->send($obj->body)) return false;

		return true;
	}

	public function newsletters($obj=null){
		if(!is_object($obj)) return false;

		$User = new User();
		if(!$User->find($obj->userid)) return false;
		$user = $User->data();

		$this->_mailer->clearAllRecipients();
		$this->_mailer->addAddress($user->mail, $user->name.' '.$user->lastname);
		//$this->_mailer->addAddress($this->_email, $this->_fullname);
		$this->_mailer->Subject = $obj->subject;
		if(!$this->send($obj->body)) return false;

		return true;
	}


	public function new_reservation($reservationid=0){
		$Reservations = new Reservations();
		if(!$reservation = $Reservations->find($reservationid)) return false;

		$arrMails = str_replace(',', ';', $reservation->client->mail);
		$arrMails = explode(';',$arrMails);
		foreach($arrMails as $mail){
			$this->_mailer->addAddress(strtolower(trim($mail)), $reservation->client->name);
		}
		//$this->_mailer->addAddress($this->_email, $this->_fullname);
		$this->_mailer->Subject = 'Solicitud de reserva nueva en EstiloSPA.com';

		$body = Templates::template('reservations/new-reservation',$reservation);
		if(!$this->send($body)) return false;

		if($reservation->promo){
			$this->_notifications->add_log('Nueva solicitud de reserva de '.$reservation->user->fullname.' ('.$reservation->user->mail.'). Promo: <a href="'.$reservation->promo->promolink.'" target="_blank">'.$reservation->promo->title.'</a> para el día '.$reservation->fecha,'reservation');
		}else{
			$this->_notifications->add_log('Nueva solicitud de reserva de '.$reservation->user->fullname.' ('.$reservation->user->mail.') a <a href="'.ROOT.'centros/'.$reservation->client->permalink.'" target="_blank">'.$reservation->client->name.'</a>','reservation');
		}


		return true;
	}

	public function cancel_reservation($reservationid=0){
		$Reservations = new Reservations();
		if(!$reservation = $Reservations->find($reservationid)) return false;

		//$this->_mailer->addAddress($this->_email, $this->_fullname);
		$this->_mailer->addAddress($reservation->user->mail, $reservation->user->name);
		$this->_mailer->Subject = 'Cancelación de reserva en EstiloSPA.com';

		$body = Templates::template('reservations/cancel-user-reservation',$reservation);
		if(!$this->send($body)) return false;

		if($reservation->promo){
			$this->_notifications->add_log('Cancelación de reserva de parte del centro para la promo: <a href="'.$reservation->promo->promolink.'" target="_blank">'.$reservation->promo->title.'</a> para el día '.$reservation->fecha.' hs. El usuario '.$reservation->user->fullname.' ('.$reservation->user->mail.') ha sido notificado.','reservation');
		}else{
			$this->_notifications->add_log('Cancelación de reserva de parte de <a href="'.$reservation->client->permalink.'" target="_blank">'.$reservation->client->name.'</a> para el día '.$reservation->fecha.' hs. El usuario '.$reservation->user->fullname.' ('.$reservation->user->mail.') ha sido notificado.','reservation');
		}


		return true;
	}
	public function cancel_reservation_user($reservationid=0,$userid=0){
		$Reservations = new Reservations();
		if(!$reservation = $Reservations->find($reservationid)) return false;

		if($userid!=$reservation->userid) return false;

		$arrMails = str_replace(',', ';', $reservation->client->mail);
		$arrMails = explode(';',$arrMails);
		foreach($arrMails as $mail){
			$this->_mailer->addAddress(strtolower(trim($mail)), $reservation->client->name);
		}
		//$this->_mailer->addAddress($this->_email, $this->_fullname);
		$this->_mailer->Subject = 'Cancelación de reserva en EstiloSPA.com';

		$body = Templates::template('reservations/cancel-client-reservation',$reservation);
		if(!$this->send($body)) return false;

		if($reservation->promo){
			$this->_notifications->add_log('Cancelación de reserva de parte del usuario '.$reservation->user->fullname.' ('.$reservation->user->mail.') para la promo: <a href="'.$reservation->promo->promolink.'" target="_blank">'.$reservation->promo->title.'</a> para el día '.$reservation->fecha.' hs. El centro ha sido notificado.','reservation');
		}else{
			$this->_notifications->add_log('Cancelación de reserva de parte del usuario '.$reservation->user->fullname.' ('.$reservation->user->mail.') para el día '.$reservation->fecha.' hs. en el centro <a href="'.$reservation->client->permalink.'" target="_blank">'.$reservation->client->name.'<a>. El centro ha sido notificado.','reservation');
		}


		return true;
	}

	public function confirm_reservation($reservationid=0){
		$Reservations = new Reservations();
		if(!$reservation = $Reservations->find($reservationid)) return false;

		////$sale_reservation = $Reservations->get_reservation_sale($reservationid);

		//$this->_mailer->addAddress($this->_email, $this->_fullname);
		$this->_mailer->addAddress($reservation->user->mail, $reservation->user->name);
		$this->_mailer->Subject = 'Confirmación de reserva en EstiloSPA.com';

		$body = Templates::template('reservations/confirm-user-reservation',$reservation);
		if(!$this->send($body)) return false;

		if($reservation->promo){
			$this->_notifications->add_log('Confirmación de reserva de parte del centro para la promo: <a href="'.$reservation->promo->promolink.'" target="_blank">'.$reservation->promo->title.'</a> para el día '.$reservation->fecha.' hs. El usuario '.$reservation->user->fullname.' ('.$reservation->user->mail.') ha sido notificado.','reservation');
		}else{
			$this->_notifications->add_log('Confirmación de reserva de parte de <a href="'.ROOT.'centros/'.$reservation->client->permalink.'" target="_blank">'.$reservation->client->name.'<a> para el día '.$reservation->fecha.' hs. El usuario '.$reservation->user->fullname.' ('.$reservation->user->mail.') ha sido notificado.','reservation');
		}


		return true;
	}
	public function confirm_reservation_user($reservationid=0,$userid=0){
		$Reservations = new Reservations();
		if(!$reservation = $Reservations->find($reservationid)) return false;
		if($userid != $reservation->userid) return false;

		$arrMails = str_replace(',', ';', $reservation->client->mail);
		$arrMails = explode(';',$arrMails);
		foreach($arrMails as $mail){
			$this->_mailer->addAddress(strtolower(trim($mail)), $reservation->client->name);
		}
		//$this->_mailer->addAddress($this->_email, $this->_fullname);
		$this->_mailer->Subject = 'Confirmación de reserva en EstiloSPA.com';

		$body = Templates::template('reservations/confirm-client-reservation',$reservation);
		if(!$this->send($body)) return false;

		if($reservation->promo){
			$this->_notifications->add_log('Confirmación de nueva fecha de reserva de parte del usuario '.$reservation->user->fullname.' ('.$reservation->user->mail.') para la promo: <a href="'.$reservation->promo->promolink.'" target="_blank">'.$reservation->promo->title.'</a> para el día '.$reservation->fecha.' hs. El centro ha sido notificado.','reservation');
		}else{
			$this->_notifications->add_log('Confirmación de nueva fecha de reserva de parte del usuario '.$reservation->user->fullname.' ('.$reservation->user->mail.') para el día '.$reservation->fecha.' hs. en <a href="'.$reservation->client->permalink.'" target="_blank">'.$reservation->client->name.'<a>. El centro ha sido notificado.','reservation');
		}


		return true;
	}

	public function update_reservation($reservationid=0){
		$Reservations = new Reservations();
		if(!$reservation = $Reservations->find($reservationid)) return false;

		//$this->_mailer->addAddress($this->_email, $this->_fullname);
		$this->_mailer->addAddress($reservation->user->mail, $reservation->user->name);
		$this->_mailer->Subject = 'Cambio de día y horario de reserva en EstiloSPA.com';

		$body = Templates::template('reservations/update-user-reservation',$reservation);
		if(!$this->send($body)) return false;

		return true;
	}


	public function root(){
		return $_SERVER['HTTP_HOST'];
	}




}