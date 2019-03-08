<?php

require_once '../config.php';
require 'templates-mail.php';
require 'phpmailer/PHPMailerAutoload.php';

if(!Input::exists()) die(json_encode(array('Status'=>'fail')));

$DB = DB::getInstance();
$_CLIENTS = new Clients();
$_FEATURES = new Features();
$_STORES = new Stores();
$_PROMOS = new Promos();
$_ASSOC = new Assoc();
$_SALES = new Sales();
$_MPConfig = new MPConfig();


switch (Input::get('Mode')) {
	case 'upgallery':
		if(!$_USER->logged() && ($_USER->data()->idtype != 1 || $_USER->data()->idtype != 3)) die(json_encode(array('Status'=>'fail')));
		$Folder = '../'.Input::get('Folder');	
		$upfile = new File($_FILES[0],$Folder);
		$upfile->MoveFile();
		$file = $upfile->Resize(array(array(1280,720,'-o'),array(600,600,'-t')), '', false);
		echo json_encode($file);
		break;
	case 'uplogo':
		if(!$_USER->logged() && ($_USER->data()->idtype != 1 || $_USER->data()->idtype != 3)) die(json_encode(array('Status'=>'fail')));
		$Folder = '../'.Input::get('Folder');	
		$upfile = new File($_FILES[0],$Folder);
		$upfile->MoveFile();
		$file = $upfile->Resize(array(array(600,600,'')), '', false);
		echo json_encode($file);
		break;

	case 'save':
		if(!$_USER->logged() && $_USER->data()->idtype != 1) die(json_encode(array('Status'=>'restricted')));
		if(!Input::check(array('Name','Subtitle','Gallery','Features','Permalink','Mail'))) die(json_encode(array('Status'=>'check')));
		$_CLIENTS->isadmin = true;
		if(!$_CLIENTS->save()) die(json_encode(array('Status'=>'fail')));
		$id = $_CLIENTS->getLastId();
		$_ASSOC->idclient = $id;
		$_ASSOC->arrusers = Input::get('Users');		
		$_ASSOC->client_user('save');
		/////////////////////////////////////////////////
		echo json_encode(array('Status'=>'ok','ID'=>$id));
		break;
	case 'saveclient':
		if(!$_USER->logged() && $_USER->data()->idtype != 3) die(json_encode(array('Status'=>'restricted')));
		if($_USER->data()->idclient != Input::get('ID')) die(json_encode(array('Status'=>'restricted')));
		if(!Input::check(array('Name','Subtitle','Gallery','Features','Mail'))) die(json_encode(array('Status'=>'check')));
		$_CLIENTS->isadmin = false;
		$_CLIENTS->save();
		$id = $_CLIENTS->getLastId();
		/////////////////////////////////////////////////
		echo json_encode(array('Status'=>'ok','ID'=>$id,'IDC1'=>$_USER->data()->idclient ,'IDC2'=>Input::get('ID')));
		break;

	case 'find':
		$id = Input::get('ID');
		$_CLIENTS->find($id);
		$_ASSOC->idclient = $id;
		$_ASSOC->client_user('get');
		$_STORES->get($id);
		$_FEATURES->get($id);
		echo json_encode(array('Status'=>'ok','Client'=>$_CLIENTS->data(),'Stores'=>$_STORES->data(),'Features'=>$_FEATURES->data(),'Users'=>$_ASSOC->data()));
		break;
	case 'get':
		$_CLIENTS->keywords = Input::get('Keywords');
		$_CLIENTS->sort = Input::get('Sort');
		$_CLIENTS->searchmixed = intval(Input::get('SearchMixed'));
		$_CLIENTS->get();
		echo json_encode(array('Status'=>'ok', 'Results'=>$_CLIENTS->data()));		
		break;
	case 'delete':
		$DB->delete('comments',array('idclient','=',Input::get('ID')));
		$DB->delete('favs',array('idclient','=',Input::get('ID')));
		$DB->delete('mp',array('idclient','=',Input::get('ID')));
		$_PROMOS->deleteAll(Input::get('ID'));
		$_STORES->deleteAll(Input::get('ID'));
		$_FEATURES->delete(Input::get('ID'));		
		$_CLIENTS->delete();
		echo json_encode(array('Status'=>'ok'));		
		break;
	//////////// SEARCH USERS //////////////////////
	/*case 'searchusers':
		if(!$_USER->logged() && !$_USER->data()->idtype == 1) die(json_encode(array('Status'=>'fail')));
		$_USER->search(Input::get('Keyword'));
		echo json_encode(array('Status'=>'ok', 'Results'=>$_USER->data()));		
		break;*/
	case 'unlink':
		if(!$_USER->logged() && ($_USER->data()->idtype != 3)) die(json_encode(array('Status'=>'restricted')));
		if(!$_USER->data()->idclient) die(json_encode(array('Status'=>'restricted')));
		$_MPConfig->unlink($_USER->data()->idclient);
		echo json_encode(array('Status'=>'ok'));
		break;
	case 'unlinkadmin':
		if(!$_USER->logged() && ($_USER->data()->idtype != 1)) die(json_encode(array('Status'=>'restricted')));
		$_MPConfig->unlink(Input::get('idclient'));
		echo json_encode(array('Status'=>'ok'));
		break;
	case 'renewadmin':
		if(!$_USER->logged() && ($_USER->data()->idtype != 1)) die(json_encode(array('Status'=>'restricted')));
		$_MPConfig->renewtoken(Input::get('idclient'));
		echo json_encode(array('Status'=>'ok'));
		break;
	///////////// STORES ///////////////////////////
	case 'getstores':		
		$_STORES->get(Input::get('IDC'));
		echo json_encode(array('Results'=>$_STORES->data()));
		break;
	case 'findstore':		
		$_STORES->find(Input::get('IDS'));
		$today = $_STORES->scheduleToday($_STORES->data()->schedules);
		$schedules = $_STORES->schedulesList($_STORES->data()->schedules);
		echo json_encode(array('Result'=>$_STORES->data(),'Today'=>$today,'Schedules'=>$schedules ));
		break;
	case 'savestore':
		if(!$_USER->logged()) die(json_encode(array('Status'=>'restricted')));
		$_STORES->save();
		echo json_encode(array('Status'=>'ok','ID'=>$_STORES->getLastId()));
		break;
	case 'reorderstores':
		$_STORES->reorder();
		echo json_encode(array('Status'=>'ok'));
		break;

	case 'deletestore':
		if(!$_USER->logged() && $_USER->data()->idtype != 1) die(json_encode(array('Status'=>'restricted')));
		$_STORES->delete(Input::get('IDS'));
		echo json_encode(array('Status'=>'ok'));
		break;

	////////////// SALES ///////////////////////////
	case 'changeplan':
		$_ASSOC->iduser = $_USER->data()->id;
		$_ASSOC->client_user('find');
		//////// SEND MAIL ///////////////////
		$MailBody = '<h2>Hola</h2>
		<p>El usuario '.$_ASSOC->data()->name.' ha solicitado cambiar el plan actual de su negocio <a href="'.ROOTPATH.'centros/'.$_ASSOC->data()->permalink.'">'.$_ASSOC->data()->clientname.'</a></p>	
		<h4>Datos del usuario:</h4>
		<ul>
			<li>Nombre: '.$_ASSOC->data()->name.' '.$_ASSOC->data()->lastname.'</li>
			<li>Email: '.$_ASSOC->data()->mail.'</li>
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
		///echo json_encode(array('Status'=>'ok','IDC'=>$_ASSOC->data()));
		break;
	case 'getsales':
		if(!empty(Input::get('From')) && !empty(Input::get('To'))){			
			$from = explode('/',Input::get('From'));
			$to = explode('/',Input::get('To'));
			$_SALES->range = true;
			$_SALES->from = $from[2].'-'.$from[1].'-'.$from[0].' 00:00:00';
			$_SALES->to = $to[2].'-'.$to[1].'-'.$to[0].' 23:59:59';
		}
		$_SALES->ordernumber = Input::get('OrderNumber');
		if($_USER->logged() && $_USER->data()->idtype==3){
			$_SALES->idclient = $_USER->data()->idclient;
		}
		if(Input::get('IDClient')){
			$_SALES->idclient = Input::get('IDClient');
		}
		$_SALES->get();
		echo json_encode(array('Status'=>'ok','Results'=>$_SALES->data()));
		break;
	case 'setsalestatus':
		if(!$_USER->logged() && ($_USER->data()->idtype != 3 || $_USER->data()->idtype != 1)) die(json_encode(array('Status'=>'restricted')));
		$_SALES->setStatus();
		echo json_encode(array('Status'=>'ok'));
		break;
	
	default:
		echo json_encode(array('Status'=>'fail'));
		break;
}