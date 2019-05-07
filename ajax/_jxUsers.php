<?php

require_once '../config.php';
require 'templates-mail.php';
require 'phpmailer/PHPMailerAutoload.php';

$_USERADMIN = new UserAdmin();
$_SALES = new Sales();
$_FAVS = new Favs();


if(!Input::exists()) die(json_encode(array('Status'=>'fail')));

switch(Input::get('Mode')):

	////////// REGISTER ////////////////////////////
	case 'register':
		$mail = Input::get('Mail');
		$name = Input::get('Name');
		$pass = Input::get('Pass');
		$hash = hash('sha256', uniqid());
		////////// Check Mail ///////////
		if(!filter_var($mail,FILTER_VALIDATE_EMAIL)):
			die(json_encode(array('Status'=>'wrongmail')));
		endif;
		$user = new User();
		if($user->find($mail)):
			die(json_encode(array('Status'=>'exists')));
		endif;
		////////// Check Pass ///////////
		if(strlen($pass)<8):
			die(json_encode(array('Status'=>'wrongpass')));
		endif;
		/////////////////////////////////
		$idu = $user->create(array('name'=>$name,'mail'=>strtolower($mail),'pass'=>password_hash($pass,PASSWORD_DEFAULT),'created'=>date('Y-m-d H:i:s'),'hash'=>$hash,'idtype'=>2));
		///////// ENVIAR MAIL ///////////
		$MailBody  = '<h1>Bienvenido/a a EstiloSPA.com!!!</h1><br /><br />Estás a un paso de vivir la experiencia de obtener promociones y descuentos en días de spa, tratamientos de belleza, centros de estética, depilación, masajes y mucho más...<br /><br />Para activar tu cuenta debes hacer click en el siguiente enlace: <br /><br /><a href="'.ROOTPATH.'activar-cuenta/'.$idu.'-'.$hash.'">'.ROOTPATH.'activar-cuenta/'.$idu.'-'.$hash.'</a><br /><br /><br />Gracias.<br />El equipo de EstiloSPA.com';

		$mailer->addAddress($mail, $name);
		$mailer->Subject = 'Registro nuevo usuario en EstiloSPA.com';
		$mailer->Body = $MailHead.$MailBody.$MailFoot;

		if(!$mailer->send()) {
			echo json_encode(array('Status'=>'fail','Error'=>$mailer->ErrorInfo));
		} else {
			echo json_encode(array('Status'=>'ok'));
		}	
		break;
	////////// LOGIN ///////////////////////////////
	case 'login':
		$mail = Input::get('Mail');
		$pass = Input::get('Pass');
		if(!filter_var($mail,FILTER_VALIDATE_EMAIL)):
			die(json_encode(array('Status'=>'wrongmail')));
		endif;
		$user = new User();		
		if(!$user->find($mail)):
			die(json_encode(array('Status'=>'none')));
		endif;
		if(!$user->isActive($mail)):
			die(json_encode(array('Status'=>'inactive')));
		endif;
		if(!$user->login($mail,$pass)):
			die(json_encode(array('Status'=>'wrongpass')));
		endif;		
		echo json_encode(array('Status'=>'ok'));
		break;
	////////// LOGOUT //////////////////////////////
	case 'logout':
		$_USER->logout();
		echo json_encode(array('Status'=>'ok'));
		break;
	////////// RECOVER /////////////////////////////	
	case 'recover':
		$mail = Input::get('Mail');
		if(!filter_var($mail,FILTER_VALIDATE_EMAIL)):
			die(json_encode(array('Status'=>'wrongmail')));
		endif;
		$user = new User();
		if(!$user->find($mail)):
			die(json_encode(array('Status'=>'none')));
		endif;
		if(!$user->isActive($mail)):
			die(json_encode(array('Status'=>'inactive')));
		endif;
		$MailBody = '<h1>Recuperar contraseña</h1><br /><br />Para poder recuperar tu acceso al sitio debes generar una contraseña nueva haciendo click en el siguiente enlace: <br /><br /><a href="'.ROOTPATH.'reset-password/'.$user->data()->id.'-'.$user->data()->hash.'">'.ROOTPATH.'reset-password/'.$user->data()->id.'-'.$user->data()->hash.'</a><br /><br /><br />Gracias.<br />El equipo de EstiloSPA.com';

		$mailer->addAddress($mail, $user->data()->name);
		$mailer->Subject = 'Recuperar acceso en EstiloSPA.com';
		$mailer->Body = $MailHead.$MailBody.$MailFoot;
		if(!$mailer->send()) {
			echo json_encode(array('Status'=>'fail','Error'=>$mailer->ErrorInfo));
		} else {
			echo json_encode(array('Status'=>'ok'));
		}
		break;
	////////// RESET ///////////////////////////////	
	case 'reset':
		$pass = Input::get('Pass');
		$hash = Input::get('Hash');
		$id = Input::get('ID');
		if(strlen($pass)<8):
			die(json_encode(array('Status'=>'wrongpass')));
		endif;
		$db = DB::getInstance()->query("SELECT id FROM spa_users WHERE id={$id} AND hash='{$hash}'");
		if(!$db->count()){
			die(json_encode(array('Status'=>'fail')));
		}
		$newhash = hash('sha256', uniqid());
		$db->update("users",$id,array('pass'=>password_hash($pass,PASSWORD_DEFAULT),'hash'=>$newhash));
		echo json_encode(array('Status'=>'ok'));
		break;
	////////// RESEND //////////////////////////////
	case 'resend':
		$mail = Input::get('Mail');
		if(!filter_var($mail,FILTER_VALIDATE_EMAIL)):
			die(json_encode(array('Status'=>'wrongmail')));
		endif;
		$user = new User();		
		if(!$user->find($mail)):
			die(json_encode(array('Status'=>'none')));
		endif;
		$db = DB::getInstance();
		if($user->data()->active):
			die(json_encode(array('Status'=>'active')));
		endif;
		
		$hash = hash('sha256', uniqid());
		$idu = $user->data()->id;
		$db->update('users',$idu,array('hash'=>$hash));

		$MailBody  = '<h1>Bienvenido/a a EstiloSPA.com!!!</h1><br /><br />Estás a un paso de vivir la experiencia de obtener promociones y descuentos en días de spa, tratamientos de belleza, centros de estética, depilación, masajes y mucho más...<br /><br />Para activar tu cuenta debes hacer click en el siguiente enlace: <br /><br /><a href="'.ROOTPATH.'activar-cuenta/'.$idu.'-'.$hash.'">'.ROOTPATH.'activar-cuenta/'.$idu.'-'.$hash.'</a><br /><br /><br />Gracias.<br />El equipo de EstiloSPA.com';

		$mailer->addAddress($mail, $user->data()->name);
		$mailer->Subject = 'Activación de nuevo usuario en EstiloSPA.com';
		$mailer->Body = $MailHead.$MailBody.$MailFoot;

		if(!$mailer->send()) {
			echo json_encode(array('Status'=>'fail','Error'=>$mailer->ErrorInfo));
		} else {
			echo json_encode(array('Status'=>'ok'));
		}	
		break;
	/////////// CHANGE IMAGE ///////////////////////
	case 'upimage':
		if(!$_USER->logged()):
			die(json_encode(array('Status'=>'fail')));
		endif;
		$Folder = '../'.Input::get('Folder');	
		$upfile = new File($_FILES[0],$Folder);
		$upfile->MoveFile();
		$db = DB::getInstance();
		$db->update('users',$_USER->data()->id,array('image'=>json_encode(array('photoname'=>$upfile->photoname,'extension'=>$upfile->extension))));
		$file = $upfile->Resize(array(array(800,600,'-o'),array(260,260,'-t')), '', false);
		echo json_encode($file);
		break;
	case 'upimageadmin':
		if(!$_USER->logged() && $_USER->data()->idtype != 1) die(json_encode(array('Status'=>'fail')));
		$Folder = '../'.Input::get('Folder');	
		$upfile = new File($_FILES[0],$Folder);
		$upfile->MoveFile();
		$file = $upfile->Resize(array(array(600,600,'-o'),array(260,260,'-t')), '', false);
		echo json_encode($file);
		break;
	/////////// UPDATE /////////////////////////////
	case 'update':
		if(!$_USER->logged()):
			die(json_encode(array('Status'=>'fail')));
		endif;
		$arrUpdate = array(
			'name'=>Input::get('Name'),
			'lastname'=>Input::get('Lastname'),
			'birth'=>Input::get('Birth'),
			'newsletter'=>Input::get('Newsletter'),
			'address'=>Input::get('Address'),
			'addressobs'=>Input::get('AddressObs'),
			'zipcode'=>Input::get('Zip'),
			'city'=>Input::get('City'),
			'idprovince'=>Input::get('IDProvence'),
			'phone'=>Input::get('Phone'),
			'dni'=>Input::get('DNI')
		);
		$db = DB::getInstance();
		$pass = Input::get('Pass');
		$passnew = Input::get('PassNew');
		if(!empty($pass) && !empty($passnew)){
			$db->get('users',array('id','=',$_USER->data()->id));
			if(!password_verify(Input::get('Pass'),$db->first()->pass) ){
				die(json_encode(array('Status'=>'wrongpass')));
			}
			$arrPass = array('pass'=>password_hash(Input::get('PassNew'),PASSWORD_DEFAULT));
			$arrUpdate = array_merge($arrUpdate,$arrPass);
		}
		$db->update('users',$_USER->data()->id,$arrUpdate);
		echo json_encode(array('Status'=>'ok'));
		break;
	////////////////////////////////////////////////
	case 'save':
		if(!$_USER->logged() && $_USER->data()->idtype != 1) die(json_encode(array('Status'=>'fail')));
		if(!filter_var(Input::get('Mail'),FILTER_VALIDATE_EMAIL)) die(json_encode(array('Status'=>'wrongmail')));
		if(!Input::get('ID')){
			if(empty(Input::get('Pass'))) die(json_encode(array('Status'=>'pass')));
			if($_USERADMIN->find(Input::get('Mail'))) die(json_encode(array('Status'=>'exists')));
		}		

		$_USERADMIN->save();
		$ID = $_USERADMIN->getLastId();
		if(Input::get('IDType')==3){
			$assoc = new Assoc();
			$assoc->idclient = Input::get('IDClient');
			$assoc->iduser = $ID;
			$assoc->client_user('save');
			////////////////////// MAIL ///////////////////////////
			if(!Input::get('ID') && Input::get('Notify')):
			$MailBody  = '<h2>¡Hola '.Input::get('Name').'!</h2>
			<p>Te damos la bienvenida al nuevo sitio de <a href="'.ROOTPATH.'">EstiloSPA.com!!!</a></p>
			<p>Te enviamos el nombre de usuario y contraseña para poder ingresar al portal. Para ello deberás ingresar a <a href="'.ROOTPATH.'login">'.ROOTPATH.'login</a></p>
			<p>
				<b>Nombre de usuario</b>: '.Input::get('Mail').'<br />
				<b>Contraseña</b>: '.Input::get('Pass').'
			</p>
			<br />
			<p>Una vez dentro de la plataforma podrás editar la información de tu comercio dirigiéndote a la sección de configuración de tu cuenta que se encuentra en el menú de tu usuario en la parte superior derecha del sitio.</p>
			<p>Desde ahí también podrás crear promociones de los servicios que brinda tu comercio para poder venderlas dentro de nuestro portal.</p>
			<p>Te recordamos que para poder habilitar la venta online de las promociones deberás contar con una cuenta de MercadoPago  para poder vincularla con nuestra plataforma.</p>
			<br /><br />
			<p>
				Gracias.<br />
				El equipo de EstiloSPA.com
			</p>';
			$mailer->addAddress(Input::get('Mail'), Input::get('Name'));
			$mailer->Subject = 'Registro nuevo usuario en EstiloSPA.com';
			$mailer->Body = $MailHead.$MailBody.$MailFoot;
			if(!$mailer->send()) {
				die(json_encode(array('Status'=>'fail','Error'=>$mailer->ErrorInfo)));
			}else{
				die( json_encode(array('Status'=>'oksend')) );
			}
			endif;
			///////////////////////////////////////////////////////
		}
		echo json_encode(array('Status'=>'ok'));
		break;
	case 'get':
		if(!$_USER->logged() && $_USER->data()->idtype != 1) die(json_encode(array('Status'=>'fail')));		
		$_USERADMIN->keywords = Input::get('Keywords');
		$_USERADMIN->type = Input::get('Type');
		$_USERADMIN->get();
		echo json_encode(array('Status'=>'ok','Results'=>$_USERADMIN->data()));
		break;
	case 'getusersclient':
		if(!$_USER->logged() && $_USER->data()->idtype != 1) die(json_encode(array('Status'=>'fail')));		
		$_USERADMIN->keywords = Input::get('Keywords');
		$_USERADMIN->idtype = 3;
		$_USERADMIN->get();
		echo json_encode(array('Status'=>'ok','Results'=>$_USERADMIN->data()));
		break;
	case 'delete':
		if(!$_USER->logged() && $_USER->data()->idtype != 1) die(json_encode(array('Status'=>'fail')));
		//$user = new User();
		$_USERADMIN->delete(Input::get('ID'));
		echo json_encode(array('Status'=>'ok'));
		break;
	case 'find':
		$_USERADMIN->find(Input::get('ID'));
		$_ASSOC = new Assoc();
		$_ASSOC->iduser = Input::get('ID');
		$_ASSOC->client_user('get');
		echo json_encode(array('Status'=>'ok','Result'=>$_USERADMIN->data(),'Assoc'=>$_ASSOC->data()));
		break;
	////////////////////////////////////////////////
	case 'qualify':
		if(!$_USER->logged()) die(json_encode(array('Status'=>'fail')));
		$_SALES->iduser = $_USER->data()->id;
		if(!$_SALES->find(Input::get('IDSale'))) die(json_encode(array('Status'=>'fail')));
		if(!is_null($_SALES->data()->text)) die(json_encode(array('Status'=>'fail text')));
		$_SALES->qualify();
		echo json_encode(array('Status'=>'ok'));
		break;
	case 'favs':
		if(!$_USER->logged()) die(json_encode(array('Status'=>'fail')));
		$_FAVS->iduser = $_USER->data()->id;
		$_FAVS->idpromo = intval(Input::get('IDP'));
		$_FAVS->idclient = intval(Input::get('IDC'));
		$_FAVS->addremove();
		echo json_encode(array('Status'=>'ok', 'IsFav'=>$_FAVS->isfav()));
		break;
	case 'deletefav':
		if(!$_USER->logged()) die(json_encode(array('Status'=>'fail')));
		$_FAVS->delete(Input::get('ID'));
		echo json_encode(array('Status'=>'ok'));
		break;
	////////////////////////////////////////////////
	default:
		echo json_encode(array('Status'=>'fail'));
		break;
endswitch;
?>