<?php

require_once '../config.php';
header("Content-Type: application/json; charset=utf-8", true);

$User = new User();
$UserAdmin = new UserAdmin();
$Assoc = new Assoc();
$Mailing = new Mailing();


if(!Input::check(Input::get('required'))) die(Responses::response('fail'));
if(!$User->logged() || $User->data()->idtype != 1) die(Responses::response('restricted'));

switch($_action){


	case 'save':

		$validate = Input::validate(
			array('email'=>Input::get('Mail'))
		);
		if(!$validate->status) die($validate->response);
		
		if(!Input::get('ID')){
			$validate = Input::validate(
				array('password'=>Input::get('Pass'))
			);
			if(!$validate->status) die($validate->response);
			if($UserAdmin->find(Input::get('Mail'))) die(Responses::response('fail','Ya existe un usuario con ese email'));
		}		

		$UserAdmin->save();		
		$ID = $UserAdmin->getLastId();

		if(Input::get('IDType')==3){
			
			$Assoc->idclient = Input::get('IDClient');
			$Assoc->iduser = $ID;
			$Assoc->client_user('save');

			if(!Input::get('ID') && Input::get('Notify')){
				$UserAdmin->find($ID);
				$userdata = $UserAdmin->data();
				$userdata->password = Input::get('Pass');
				if(!$Mailing->new_user($userdata)) die(Responses::response('fail','No se pudo enviar el email de notificación al usuario'));
			}
		}

		echo Responses::response('ok');
		break;


	case 'get':
		$UserAdmin->keywords = Input::get('keywords');
		$UserAdmin->type = Input::get('type');
		$UserAdmin->limit = '';
		$UserAdmin->get();
		echo Responses::response('ok','',array('results'=>$UserAdmin->data()));
		break;

	case 'getbytype':
		$UserAdmin->keywords = Input::get('keywords');
		$UserAdmin->idtype = 3;
		$UserAdmin->get();
		$results = $UserAdmin->data();
		if($results){
			foreach($results as $k=>$user){
				$results[$k]->name = $user->name.' '.$user->lastname.' ('.$user->mail.')';
			}
		}
		echo Responses::response('ok','',array('results'=>$results));
		break;

	case 'delete':
		$UserAdmin->delete(Input::get('ID'));
		echo Responses::response('ok');
		break;

	case 'find':
		$UserAdmin->find(Input::get('ID'));
		$Assoc->iduser = Input::get('ID');
		$Assoc->client_user('get');
		echo Responses::response('ok','',array('result'=>$UserAdmin->data(),'assoc'=>$Assoc->data()) );
		break;

	case 'upimage':
		$Folder = '../'.Input::get('folder');	
		$upfile = new File($_FILES['file'],$Folder);
		$upfile->MoveFile();
		$file = $upfile->Resize(array(array(600,600,'-o'),array(260,260,'-t')), '', false);
		echo Responses::response('ok');
		break;


	case 'login_as':

		$user = $User->find(Input::get('userid'));
		$User->login();

		echo Responses::response('ok','',array('user'=>$User->data()));
		break;


	default:
		echo Responses::response('fail');
		break;

}