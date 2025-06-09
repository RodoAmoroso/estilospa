<?php

$User = new User();
$Reservations = new Reservations();
$Clients = new Clients();
$Stores = new Stores();
$Mailing = new Mailing();
$Promos = new Promos();

if(!Input::check(Input::get('required'))) die(Responses::response('fail'));

switch($_action){


	case 'change_month':
		$fecha = new DateTime(Input::get('year').'-'.Input::get('month').'-01');
		if(Input::get('action') == 'next'){
			$fecha->modify('+1 month');
		}else{
			$fecha->modify('-1 month');
		}
		echo Responses::response('ok','',array(
			'month'=>$fecha->format('n'),
			'month_name'=>Dates::translateMonths($fecha->format('F')),
			'year'=>$fecha->format('Y')
		));
		break;

	case 'change_days':

		if(Input::get('action') == 'next'){
				$day = Input::get('lastday');
			}else if(Input::get('action') == 'prev'){
				$day = Input::get('firstday');
			}else{
				$day = '01';
			}

		$fecha = new DateTime(Input::get('year').'-'.Input::get('month').'-'.$day);

		for($i=1; $i<=4; $i++){
			$_arrdays[] = array(
				'day'=>$fecha->format('d'),
				'dayname'=>$fecha->format('D'),
				'name'=>Dates::translateDays($fecha->format('l'))
			);
			if(Input::get('action') == 'next' || Input::get('action') == ''){
				$fecha->modify('+1 day');
			}else{
				$fecha->modify('-1 day');
			}
		}

		if(Input::get('action') == 'prev'){
			$_arrdays = array_reverse($_arrdays);
		}

		echo Responses::response('ok','',array(
			'days'=>$_arrdays,
			'month'=>$fecha->format('m'),
			'month_name'=>Dates::translateMonths($fecha->format('F')),
			'year'=>$fecha->format('Y')
		));
		break;

	case 'get_hours':

		$Stores->get(Input::get('idclient'));
		//$Stores->get(112);
		if(!$Stores->data()) die(Responses::response('ok','',['hours'=>false]));

		$schedules = json_decode($Stores->data()[0]->schedules);
		$hours = array();
		foreach($schedules as $day){
			if(Input::get('activeday') == $day->day){
				$hours = $day->schedules;
			}
		}

		$taken_days = $Reservations->taken_days(Input::get('idclient'),Input::get('date'));

		echo Responses::response('ok','',array(
			'hours'=>$hours,
			'taken_days'=>$taken_days
		));
		break;

	case 'book':

		if(!$User->logged()){
			if(!$User->find(Input::get('email'))){

				if(!filter_var(Input::get('email'),FILTER_VALIDATE_EMAIL)) die(Responses::response('invalid_email'));

				$hash = hash('sha256', uniqid());
				$password = rand(11111,99999);

				if(!$idu = $User->create(
					array(
						'name'=>Input::get('name'),
						'lastname'=>Input::get('lastname'),
						'mail'=>strtolower(Input::get('email')),
						'phone'=>Input::get('phone'),
						'pass'=>password_hash($password,PASSWORD_DEFAULT),
						'created'=>date('Y-m-d H:i:s'),
						'hash'=>$hash,
						'active'=>0,
						'idtype'=>2
					)
				)) die(Responses::response('fail'));


				$userdata = new stdClass();
				$userdata->id = $idu;
				$userdata->name = Input::get('name');
				$userdata->mail = Input::get('email');
				$userdata->hash = $hash;

				if(!$Mailing->register($userdata)) die(Responses::response('fail','No se pudo enviar el email.'));
				//$User->login(Input::get('email'),$password);

			}else{
				$idu = $User->data()->id;
			}
		}else{
			$idu = $User->data()->id;
		}

		$User->update($idu,array(
			'phone'=>Input::get('phone'),
			'name'=>Input::get('name'),
			'lastname'=>Input::get('lastname')
		));

		if(!$reservationid = $Reservations->add(array(
			'promoid'=>empty(Input::get('promoid')) ? null : Input::get('promoid'),
			'clientid'=>empty(Input::get('clientid')) ? null : Input::get('clientid'),
			'userid'=>$idu,
			'book_date'=>Input::get('date'),
			'status'=>0,
			'comments'=>Input::get('message')
		))) die(Responses::response('fail','Hubo un problema al guardar la reserva. Intentá nuevamente.'));

		$Reservations->reservations_sales($reservationid,Input::get('sale_hash'));

		$Mailing->new_reservation($reservationid);

		echo Responses::response('ok','<h3>¡Tu solicitud de turno ha sido enviada!</h3><br>IMPORTANTE: Deberás aguardar la confirmación de tu turno vía email, para concurrir.');
		break;


	case 'get':
		if(!$User->logged()) die(Responses::response('require_login'));
		$Reservations->from = Input::get('from');
		$Reservations->to = Input::get('to');
		$reservations = $Reservations->get(null,$User->data()->id);
		echo Responses::response('ok','',array('results'=>$reservations));
		break;

	case 'find':
		if(!$User->logged()) die(Responses::response('require_login'));
		if(!$reservation = $Reservations->find(Input::get('id'))) die(Responses::response('fail','La reserva no ha sido encontrada'));

		if($reservation->user->id != $User->data()->id) die(Responses::response('restricted'));
		echo Responses::response('ok','',array('result'=>$reservation));
		break;

	case 'confirm':

		if(!$User->logged()) die(Responses::response('require_login'));

		//$Promos->find(Input::get('promoid'));
		//if(!$Promos->data()) die(Responses::response('fail'));

		$Reservations->confirm(Input::get('id'));
		$Mailing->confirm_reservation_user(Input::get('id'),$User->data()->id);

		echo Responses::response('ok','La reserva ha sido confirmada exitosamente. Se envió un aviso al centro.');
		break;

	case 'delete':

		if(!$User->logged()) die(Responses::response('require_login'));

		if(!$Mailing->cancel_reservation_user(Input::get('id'),$User->data()->id)) die(Responses::response('fail'));
		$Reservations->delete(Input::get('id'));

		echo Responses::response('ok','La reserva ha sido cancelada. Se envió aviso al centro.');
		break;


	default:
		echo Responses::response('fail');
		break;


}