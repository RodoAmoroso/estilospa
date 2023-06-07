<?php

header("Content-Type: application/json; charset=utf-8", true);

$User = new User();
$UserAdmin = new UserAdmin();
$Assoc = new Assoc();
$Mailing = new Mailing();

$Users = new Users;

if(!Input::check(Input::get('required'))) die(Responses::response('fail'));
if(!$User->logged() || $User->data()->idtype != 1) die(Responses::response('restricted'));

switch($_action){


	case 'save':

		$validate = Input::validate(
			array('email'=>Input::get('mail'))
		);
		if(!$validate->status) die($validate->response);

		if(!Input::get('id')){
			$validate = Input::validate(
				array('password'=>Input::get('pass'))
			);
			if(!$validate->status) die($validate->response);
			if($UserAdmin->find(Input::get('mail'))) die(Responses::response('fail','Ya existe un usuario con ese email'));
		}

		$UserAdmin->save();
		$ID = $UserAdmin->getLastId();

		if(Input::get('idtype')==3 || Input::get('idtype')==4){

			$Assoc->idclient = Input::get('idclient');
			$Assoc->iduser = $ID;
			$Assoc->client_user('save');

			if(!Input::get('id') && Input::get('notify')){
				$UserAdmin->find($ID);
				$userdata = $UserAdmin->data();
				$userdata->password = Input::get('pass');
				if(!$Mailing->new_user($userdata)) die(Responses::response('fail','No se pudo enviar el email de notificación al usuario'));
			}
		}

		echo Responses::response('ok','Los datos fueron guardados correctamente');
		break;


	case 'get':
		$UserAdmin->keywords = Input::get('keywords');
		$UserAdmin->type = Input::get('type');
		$UserAdmin->limit = '';
		$UserAdmin->get();
		echo Responses::response('ok','',array('results'=>$UserAdmin->data()));
		break;

	case 'get-datatable':

		$Users->search = Input::get('search')['value'];
		$Users->filters = Input::get('filters');
		$Users->limit = '0,50000';

		$total = $Users->get_total();

		$response = [
			'draw'=>Input::get('draw','int'),
			'recordsTotal'=>(int) $total,
			'recordsFiltered'=>(int) $total,
			'data'=>[],
			'request'=>Input::get_all()
		];

		$Users->limit = Input::get('start','int').','.Input::get('length','int');
		/*if(Input::get('order')){
			$sort = Input::get('columns')[Input::get('order')[0]['column']]['data'].'_'.Input::get('order')[0]['dir'];
			$Users->sort = $sort;
		}*/
		if($results = $Users->get()){
			if( method_exists($Users, 'datatable') ){
				foreach($results as $result){
					$response['data'][] = $Users->datatable($result);
				}
			}else{
				$response['data'] = $results;
			}
		}

		echo Responses::response('ok','',$response);
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

		$Assoc->iduser = Input::get('ID');
		$Assoc->client_user('get');

		$UserAdmin->find(Input::get('ID'));
		echo Responses::response('ok','',[
			'result'=>$UserAdmin->data(),
			'assoc'=>$Assoc->data(),
			'request'=>Input::get_all()
		]);
		break;

	case 'upimage':
		$Folder = '../'.Input::get('folder');
		$upfile = new File($_FILES['file'],$Folder);
		$upfile->MoveFile();
		$file = $upfile->Resize(array(array(600,600,'-o'),array(260,260,'-t')), '', false);
		echo Responses::response('ok');
		break;


	case 'login_as':

		if(!$user = $User->find(Input::get('userid'))) die(Responses::response('fail','Usuario no encontrado'));

		$hash = $User->get_session(Input::get('userid'));

		Cookie::put(Config::get('cookie/cookie_name'),$hash);
		Session::put(Config::get('session/session_name'),$hash);

		echo Responses::response('ok','',array('user'=>$User->data()));
		break;


	default:
		echo Responses::response('fail');
		break;

}