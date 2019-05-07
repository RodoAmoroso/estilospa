<?php

require_once '../config.php';
///require 'templates-mail.php';
///require 'phpmailer/PHPMailerAutoload.php';

if(!Input::exists()) die(json_encode(array('Status'=>'fail')));

$DB = DB::getInstance();
$Clients = new Clients();
$Features = new Features();
$Stores = new Stores();
$Promos = new Promos();
$Assoc = new Assoc();
$Sales = new Sales();
$MPConfig = new MPConfig();


switch (Input::get('Mode')) {
	case 'upgallery':
		if(!$User->logged() && ($User->data()->idtype != 1 || $User->data()->idtype != 3)) die(json_encode(array('Status'=>'fail')));
		$Folder = '../'.Input::get('Folder');	
		$upfile = new File($_FILES[0],$Folder);
		$upfile->MoveFile();
		$file = $upfile->Resize(array(array(1280,720,'-o'),array(600,600,'-t')), '', false);
		echo json_encode($file);
		break;
	case 'uplogo':
		if(!$User->logged() && ($User->data()->idtype != 1 || $User->data()->idtype != 3)) die(json_encode(array('Status'=>'fail')));
		$Folder = '../'.Input::get('Folder');	
		$upfile = new File($_FILES[0],$Folder);
		$upfile->MoveFile();
		$file = $upfile->Resize(array(array(600,600,'')), '', false);
		echo json_encode($file);
		break;

	case 'save':
		if(!$User->logged() && $User->data()->idtype != 1) die(json_encode(array('Status'=>'restricted')));
		if(!Input::check(array('Name','Subtitle','Gallery','Features','Permalink','Mail'))) die(json_encode(array('Status'=>'check')));
		$Clients->isadmin = true;
		if(!$Clients->save()) die(json_encode(array('Status'=>'fail')));
		$id = $Clients->getLastId();
		$Assoc->idclient = $id;
		$Assoc->arrusers = Input::get('Users');		
		$Assoc->client_user('save');
		/////////////////////////////////////////////////
		echo json_encode(array('Status'=>'ok','ID'=>$id));
		break;
	case 'saveclient':
		if(!$User->logged() && $User->data()->idtype != 3) die(json_encode(array('Status'=>'restricted')));
		if($User->data()->idclient != Input::get('ID')) die(json_encode(array('Status'=>'restricted')));
		if(!Input::check(array('Name','Subtitle','Gallery','Features','Mail'))) die(json_encode(array('Status'=>'check')));
		$Clients->isadmin = false;
		$Clients->save();
		$id = $Clients->getLastId();
		/////////////////////////////////////////////////
		echo json_encode(array('Status'=>'ok','ID'=>$id,'IDC1'=>$User->data()->idclient ,'IDC2'=>Input::get('ID')));
		break;

	case 'find':
		$id = Input::get('ID');
		$Clients->find($id);
		$Assoc->idclient = $id;
		$Assoc->client_user('get');
		$Stores->get($id);
		$Features->get($id);
		echo json_encode(array('Status'=>'ok','Client'=>$Clients->data(),'Stores'=>$Stores->data(),'Features'=>$Features->data(),'Users'=>$Assoc->data()));
		break;
	case 'get':
		$Clients->keywords = Input::get('Keywords');
		$Clients->sort = Input::get('Sort');
		$Clients->searchmixed = intval(Input::get('SearchMixed'));
		$Clients->get();
		echo json_encode(array('Status'=>'ok', 'Results'=>$Clients->data()));		
		break;
	case 'delete':
		$DB->delete('comments',array('idclient','=',Input::get('ID')));
		$DB->delete('favs',array('idclient','=',Input::get('ID')));
		$DB->delete('mp',array('idclient','=',Input::get('ID')));
		$Promos->deleteAll(Input::get('ID'));
		$Stores->deleteAll(Input::get('ID'));
		$Features->delete(Input::get('ID'));		
		$Clients->delete();
		echo json_encode(array('Status'=>'ok'));		
		break;
	//////////// SEARCH USERS //////////////////////
	/*case 'searchusers':
		if(!$User->logged() && !$User->data()->idtype == 1) die(json_encode(array('Status'=>'fail')));
		$User->search(Input::get('Keyword'));
		echo json_encode(array('Status'=>'ok', 'Results'=>$User->data()));		
		break;*/
	case 'unlink':
		if(!$User->logged() && ($User->data()->idtype != 3)) die(json_encode(array('Status'=>'restricted')));
		if(!$User->data()->idclient) die(json_encode(array('Status'=>'restricted')));
		$MPConfig->unlink($User->data()->idclient);
		echo json_encode(array('Status'=>'ok'));
		break;
	case 'unlinkadmin':
		if(!$User->logged() && ($User->data()->idtype != 1)) die(json_encode(array('Status'=>'restricted')));
		$MPConfig->unlink(Input::get('idclient'));
		echo json_encode(array('Status'=>'ok'));
		break;
	case 'renewadmin':
		if(!$User->logged() && ($User->data()->idtype != 1)) die(json_encode(array('Status'=>'restricted')));
		$MPConfig->renewtoken(Input::get('idclient'));
		echo json_encode(array('Status'=>'ok'));
		break;
	///////////// STORES ///////////////////////////
	case 'getstores':		
		$Stores->get(Input::get('IDC'));
		echo json_encode(array('Results'=>$Stores->data()));
		break;
	case 'findstore':		
		$Stores->find(Input::get('IDS'));
		$today = $Stores->scheduleToday($Stores->data()->schedules);
		$schedules = $Stores->schedulesList($Stores->data()->schedules);
		echo json_encode(array('Result'=>$Stores->data(),'Today'=>$today,'Schedules'=>$schedules ));
		break;
	case 'savestore':
		if(!$User->logged()) die(json_encode(array('Status'=>'restricted')));
		$Stores->save();
		echo json_encode(array('Status'=>'ok','ID'=>$Stores->getLastId()));
		break;
	case 'reorderstores':
		$Stores->reorder();
		echo json_encode(array('Status'=>'ok'));
		break;

	case 'deletestore':
		if(!$User->logged() && $User->data()->idtype != 1) die(json_encode(array('Status'=>'restricted')));
		$Stores->delete(Input::get('IDS'));
		echo json_encode(array('Status'=>'ok'));
		break;

	////////////// SALES ///////////////////////////
	
	case 'changeplan':
		$Assoc->iduser = $User->data()->id;
		$Assoc->client_user('find');
		//////// SEND MAIL ///////////////////
		$MailBody = '<h2>Hola</h2>
		<p>El usuario '.$Assoc->data()->name.' ha solicitado cambiar el plan actual de su negocio <a href="'.ROOT.'centros/'.$Assoc->data()->permalink.'">'.$Assoc->data()->clientname.'</a></p>	
		<h4>Datos del usuario:</h4>
		<ul>
			<li>Nombre: '.$Assoc->data()->name.' '.$Assoc->data()->lastname.'</li>
			<li>Email: '.$Assoc->data()->mail.'</li>
		</ul>
		';
		
		$mailer->addAddress('consultas@estilospa.com', 'EstiloSPA.com');
		$mailer->Subject = 'Cambio de Plan';
		$mailer->Body = $MailHead.$MailBody.$MailFoot;
		if(!$mailer->send()) {
			die(json_encode(array('Status'=>'fail','Error'=>$mailer->ErrorInfo)));
		}else{
			die( json_encode(array('Status'=>'ok')) );
		}
		///echo json_encode(array('Status'=>'ok','IDC'=>$Assoc->data()));
		break;

	case 'getsales':
		if(!empty(Input::get('From')) && !empty(Input::get('To'))){			
			$from = explode('/',Input::get('From'));
			$to = explode('/',Input::get('To'));
			$Sales->range = true;
			$Sales->from = $from[2].'-'.$from[1].'-'.$from[0].' 00:00:00';
			$Sales->to = $to[2].'-'.$to[1].'-'.$to[0].' 23:59:59';
		}
		$Sales->ordernumber = Input::get('OrderNumber');
		if($User->logged() && $User->data()->idtype==3){
			$Sales->idclient = $User->data()->idclient;
		}
		if(Input::get('IDClient')){
			$Sales->idclient = Input::get('IDClient');
		}
		$Sales->get();
		echo json_encode(array('Status'=>'ok','Results'=>$Sales->data()));
		break;
	case 'setsalestatus':
		if(!$User->logged() && ($User->data()->idtype != 3 || $User->data()->idtype != 1)) die(json_encode(array('Status'=>'restricted')));
		$Sales->setStatus();
		echo json_encode(array('Status'=>'ok'));
		break;
	
	default:
		echo json_encode(array('Status'=>'fail'));
		break;
}