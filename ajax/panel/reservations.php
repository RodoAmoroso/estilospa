<?php

require_once '../config.php';
header("Content-Type: application/json; charset=utf-8", true);

$User = new User();
$Reservations = new Reservations();
$Promos = new Promos();
$Mailing = new Mailing();

if(!Input::check(Input::get('required'))) die(Responses::response('fail'));
if(!$User->logged() || $User->data()->idtype != 3) die(Responses::response('restricted'));

switch($_action){

	case 'get':
		$Reservations->from = Input::get('from');
		$Reservations->to = Input::get('to');
		$reservations = $Reservations->get($User->data()->idclient);		
		echo Responses::response('ok','',array('results'=>$reservations));
		break;

	case 'find':
		if(!$reservation = $Reservations->find(Input::get('reservationid'))) die(Responses::response('fail','La reserva no ha sido encontrada'));

		if($reservation->clientid != $User->data()->idclient) die(Responses::response('restricted'));
		echo Responses::response('ok','',array('result'=>$reservation));
		break;

	case 'delete':

		//$Promos->find(Input::get('promoid'));
		//if(!$Promos->data()) die(Responses::response('fail'));
		//if($Promos->data()->idclient != $User->data()->idclient) die(Responses::response('restricted'));

		$Mailing->cancel_reservation(Input::get('id'));
		$Reservations->delete(Input::get('id'));
		
		echo Responses::response('ok','La reserva ha sido cancelada. Se envió aviso al usuario.');
		break;

	case 'confirm':

		//$Promos->find(Input::get('promoid'));
		//if(!$Promos->data()) die(Responses::response('fail'));
		//if($Promos->data()->idclient != $User->data()->idclient) die(Responses::response('restricted'));

		$Reservations->confirm(Input::get('id'));
		$Mailing->confirm_reservation(Input::get('id'));
		
		echo Responses::response('ok','La reserva ha sido confirmada exitosamente. Se envió aviso al usuario.');
		break;


	case 'change_date':
		//$Promos->find(Input::get('promoid'));
		//if(!$Promos->data()) die(Responses::response('fail'));
		//if($Promos->data()->idclient != $User->data()->idclient) die(Responses::response('restricted'));

		$Reservations->change_date(Input::get('id'),Input::get('date'));
		$Mailing->update_reservation(Input::get('id'));

		echo Responses::response('ok','Día y horario actualizado. Se envió aviso al usuario. Cuando el usuario confirme el nuevo día y horario, te avisaremos por email.');

		break;


	case 'exclude':
		$Reservations->exclude($User->data()->idclient,Input::get('date'));
		echo Responses::response('ok');
		break;
	case 'unexclude':
		$Reservations->delete(Input::get('id'));
		echo Responses::response('ok');
		break;

	default:
		echo Responses::response('fail');
		break;

}