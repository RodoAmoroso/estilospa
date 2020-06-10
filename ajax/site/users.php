<?php

header("Content-Type: application/json; charset=utf-8", true);
///require 'templates-mail.php';
///require 'phpmailer/PHPMailerAutoload.php';

$UserAdmin = new UserAdmin();
$Sales = new Sales();
$Favs = new Favs();
$User = new User();
$Mailing = new Mailing();
$Subscribers = new Subscribers();

View::$scope = Config::get('paths/site');
View::$root = SITE;

switch($_action):

	case 'register':

		$hash = hash('sha256', uniqid());

		if(!filter_var(Input::get('email'),FILTER_VALIDATE_EMAIL)) die(Responses::response('invalid_email'));
		if($User->find(Input::get('email'))) die(Responses::response('user_exists'));
		if(strlen(Input::get('password'))<8) die(Responses::response('invalid_pass'));
		if(Input::get('password') != Input::get('password_repeat')) die(Responses::response('password_match'));
		/////////////////////////////////
		if(!$idu = $User->create(
			array(
				'name'=>Input::get('name'),
				'lastname'=>Input::get('lastname'),
				'mail'=>strtolower(Input::get('email')),
				'pass'=>password_hash(Input::get('password'),PASSWORD_DEFAULT),
				'created'=>date('Y-m-d H:i:s'),
				'hash'=>$hash,
				'idtype'=>2
			)
		)) die(Responses::response('fail'));

		///////// ENVIAR MAIL ///////////
		$user = new stdClass();
		$user->id = $idu;
		$user->name = Input::get('name');
		$user->mail = Input::get('email');
		$user->hash = $hash;
		if(!$Mailing->register($user)) die(Responses::response('fail'));
		echo Responses::response('ok','<h2>¡Te registraste con éxito!</h2> En unos segundos vamos a enviarte un mail a tu casilla de correo de activación de la cuenta.<br /><br />No te olvides de revisar la carpeta de correo basura (SPAM)');
		break;

	case 'login':
		$email = Input::get('email');
		$password = Input::get('password');
		if(!filter_var($email,FILTER_VALIDATE_EMAIL)) die(Responses::response('invalid_email'));


		if(!$User->find($email)) die(Responses::response('user_unexists'));
		if(!$User->isActive($email)) die(Responses::response('user_inactive'));
		if(!$User->login($email,$password)) die(Responses::response('login_fail'));

		echo Responses::response('ok');
		break;
	case 'logout':
		$User->logout();
		echo Responses::response('ok');
		break;
	case 'recover':
		$email = Input::get('email');
		if(!filter_var($email,FILTER_VALIDATE_EMAIL)) die(Responses::response('invalid_email'));

		if(!$User->find($email)) die(Responses::response('user_unexists'));
		if(!$User->isActive($email)) die(Responses::response('user_inactive'));
		if(!$newhash = $User->update_hash($User->data()->id)) die(Responses::response('fail'));
		$User->data()->hash = $newhash;
		if(!$Mailing->reset_password($User->data())) die(Responses::response('fail','No pudimos enviarte el email para recuperar la contraseña. Intenta más de nuevo más tarde.'));

		echo Responses::response('ok','En minutos llegará un mensaje con instrucciones para poder generar una contraseña nueva.');
		break;
	case 'reset':
		$password = Input::get('password');
		$hash = Input::get('hash');
		$userid = Input::get('userid');

		if(strlen(Input::get('password'))<8) die(Responses::response('invalid_pass'));
		if(!$User->find($userid)) die(Responses::response('user_unexists'));
		if(!$User->check_hash($userid,$hash)) die(Responses::response('fail'));
		if(!$User->reset_password($userid,$password)) die(Responses::response('fail'));

		echo Responses::response('ok','La contraseña se ha actualizado con éxito. Ahora podés ingresar con tu email y contraseña <a href="'.View::url('login').'" >haciendo click aquí</a>');
		break;
	case 'resend':
		$email = Input::get('email');

		if(!filter_var($email,FILTER_VALIDATE_EMAIL)) die(Responses::response('invalid_email'));
		if(!$User->find($email)) die(Responses::response('user_unexists'));
		if($User->isActive($email)) die(Responses::response('user_active'));

		if(!$newhash = $User->update_hash($User->data()->id)) die(Responses::response('fail'));
		$User->data()->hash = $newhash;
		if(!$Mailing->register($User->data())) die(Responses::response('fail'));

		echo Responses::response('ok','Se ha enviado un mensaje a tu casilla de correo con instrucciones para poder activar la cuenta. <br /><br />No te olvides de revisar la bandeja de correo basura (SPAM).');

		break;
	case 'upimage':
		if(!$User->logged()) die(Responses::response('fail'));

		$folder = '../'.Input::get('folder');
		$upfile = new File($_FILES['file'],$folder);
		$upfile->MoveFile();
		$file = $upfile->Resize(array(array(800,600,'-o'),array(260,260,'-t')), '', false);
		$User->update($User->data()->id,array(
			'image'=>json_encode(array('photoname'=>$upfile->filename,'extension'=>$upfile->extension))
		));
		echo json_encode($file);
		break;

	case 'qualify':
		if(!$User->logged()) die(Responses::response('require_login'));

		$Sales->iduser = $User->data()->id;
		if(!$Sales->find(Input::get('idsale'))) die(Responses::response('fail'));
		if(!is_null($Sales->data()->text)) die(Responses::response('fail'));

		if(!$Sales->qualify()) die(Responses::response('fail'));

		echo Responses::response('ok','Gracias por compartir tu experiencia con EstiloSPA.com!!!<br>Con tu aporte podemos mejorar y ofrecer un mejor servicio día a día.');
		break;
	case 'favs':
		if(!$User->logged()) die(Responses::response('fail'));
		$Favs->iduser = $User->data()->id;
		$Favs->idpromo = intval(Input::get('promoid'));
		$Favs->idclient = intval(Input::get('clientid'));
		$Favs->addremove();
		echo Responses::response('ok','',array('is_fav'=>$Favs->isfav()) );
		break;
	case 'deletefav':
		if(!$User->logged()) die(Responses::response('fail'));
		$Favs->delete(Input::get('id'));
		echo Responses::response('ok');
		break;

	case 'update':
		if(!$User->logged()) die(Responses::response('restricted'));
		if(!Input::check(Input::get('required'))) die(Responses::response('fail'));

		$values = array(
			'name'=>Input::get('name'),
			'lastname'=>Input::get('lastname'),
			'birth'=>Input::get('birth_year').'-'.Input::get('birth_month').'-'.Input::get('birth_day'),
			'newsletter'=>Input::get('newsletter'),
			'address'=>Input::get('address'),
			'addressobs'=>Input::get('addressObs'),
			'zipcode'=>Input::get('zipcode'),
			'city'=>Input::get('city'),
			'idprovince'=>Input::get('idprovence'),
			'phone'=>Input::get('phone'),
			'dni'=>Input::get('dni')
		);

		$password = Input::get('password');
		$password_new = Input::get('password_new');

		if($Subscribers->verify(Input::get('email'))) $Subscribers->add(Input::get('email'));

		if(!empty($password) && !empty($password_new)){
			if(!password_verify($password,$User->data()->pass)) die(Responses::response('wrong_password'));
			$values['pass'] = password_hash(Input::get('password_new'),PASSWORD_DEFAULT);
		}
		$User->update($User->data()->id,$values);

		echo Responses::response('ok','Los datos fueron guardados correctamente!');
		break;

	default:
		///echo json_encode(array('status'=>'fail'));
		echo Responses::response('fail');
		break;
endswitch;