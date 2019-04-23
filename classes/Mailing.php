<?php 

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class Mailing {

	private $_mailer,
					$_email='rodosoft@gmail.com',
					$_fullname='EstiloSPA',
					$_error;

	public function __construct(){
		require PATH.'vendor'.DS.'autoload.php';
		$this->_mailer = new PHPMailer(true);
		$this->_mailer->SMTPDebug = false;
		$this->_mailer->CharSet = 'UTF-8';
		$this->_mailer->isSMTP();
		$this->_mailer->Host = 'mail.estilospa.com';
		$this->_mailer->SMTPAuth = true;
		$this->_mailer->Username = 'webmaster@estilospa.com';
		$this->_mailer->Password = 'WmEsRaPd17';
		$this->_mailer->SMTPSecure = 'ssl';
		$this->_mailer->Port = 465;
		$this->_mailer->setFrom($this->_email,$this->_fullname);
		//$this->_mailer->addReplyTo('consultas@estilospa.com',$this->_fullname);
		$this->_mailer->isHTML(true);	
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
		if(is_null($user)) return false;

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

	public function publish($user=null){
		
		if(is_null($user)) return false;
		
		$this->_mailer->addAddress($this->_email, $this->_fullname);
		$this->_mailer->setFrom($user->email, $user->fullname);
		$this->_mailer->Subject = 'Nuevo interesado en publicar en EstiloSPA.com';

		$body = Templates::template('contact/publish',$user);
		if(!$this->send($body)) return false;

		return true;
	}


	public function contact($user=null){
		if(is_null($user)) return false;
		
		$this->_mailer->addAddress($this->_email, $this->_fullname);
		$this->_mailer->setFrom($user->email, $user->fullname);
		$this->_mailer->Subject = $user->subject;

		$body = Templates::template('contact/contact',$user);
		if(!$this->send($body)) return false;

		return true;
	}


	public function question_promo($questionid=0){
		if(!$questionid) return false;

		
		$Questions = new Questions();
		$User = new User();
		$Promos = new Promos();
		$Clients = new Clients();

		if(!$question = $Questions->get($questionid)) return false;

		if(!$Promos->find($question->rowid)) return false;
		$promo = $Promos->data();

		if(!$User->find($question->userid)) return false;
		$user = $User->data();

		if(!$Clients->find($promo->idclient)) return false;
		$client = $Clients->data();

		//$this->_mailer->addAddress($client->mail, $client->name);
		$this->_mailer->addAddress('rodosoft@hotmail.com', 'Rodo');
		$this->_mailer->setFrom($this->_email, $this->_fullname);
		$this->_mailer->Subject = 'Te hicieron una pregunta en EstiloSPA.com';

		$obj = new stdClass();
		$obj->promo_link = ROOT.'promo/'.$client->permalink.'/'.$promo->id.'-'.Permalink($promo->title);
		$obj->promo_title = $promo->title;
		$obj->user_name = $user->name;
		$obj->question = $question->message;
		$obj->questionid = $questionid;
		$obj->question_date = $question->creado;
		$body = Templates::template('questions/question-promo',$obj);
		if(!$this->send($body)) return false;

		return true;
	}



	public function response_promo($responseid=0){
		if(!$responseid) return false;

		
		$Questions = new Questions();
		$User = new User();
		$Promos = new Promos();
		$Clients = new Clients();

		if(!$response = $Questions->get_response($responseid)) return false;
		if(!$question = $Questions->get($response->messageid)) return false;

		if(!$Promos->find($question->rowid)) return false;
		$promo = $Promos->data();

		if(!$User->find($question->userid)) return false;
		$user = $User->data();

		if(!$Clients->find($promo->idclient)) return false;
		$client = $Clients->data();

		//$this->_mailer->addAddress($client->mail, $client->name);
		$this->_mailer->addAddress('rodosoft@hotmail.com', 'Rodo');
		$this->_mailer->setFrom($this->_email, $this->_fullname);
		$this->_mailer->Subject = 'Respuesta de '.$promo->title;

		$obj = new stdClass();
		$obj->promo_link = ROOT.'promo/'.$client->permalink.'/'.$promo->id.'-'.Permalink($promo->title);
		$obj->promo_title = $promo->title;
		$obj->user_name = $user->name;
		
		$obj->question = $question->message;
		$obj->question_date = $question->creado;
		
		$obj->response = $response->message;
		$obj->response_date = $response->creado;
		
		$body = Templates::template('questions/response-promo',$obj);
		if(!$this->send($body)) return false;

		return true;
	}

}