<?php 

require 'config.php';

$Mailing = new Mailing();
$Reservations = new Reservations();
$Notifications = new Notifications();
$Clients = new Clients();

$today = new DateTime();

$today->modify('+3 hour');
$Reservations->from = $today->format('Y-m-d H:i:s');

$today->modify('+1 hour');
$Reservations->to = $today->format('Y-m-d H:i:s');


$Reservations->status = 1;

if($reservations = $Reservations->get()){
	foreach($reservations as $reservation){

		$Notifications->add(array(
			'name_from'=>$Mailing->get_email_name()->name,
			'email_from'=>$Mailing->get_email_name()->email,
			'name_to'=>$reservation->user_name,
			'email_to'=>$reservation->user_email,
			'subject'=>"No te olvidés de tu reserva en {$reservation->client_name}",
			'body'=>Templates::template('reservations/reminder-user',$reservation),
			'log'=>'Notificación de recordatorio de reserva enviada a '.$reservation->user_name.' ('.$reservation->user_email.') para la promo <a href="'.ROOT.'promo/'.$reservation->permalink.'/'.$reservation->promoid.'-'.Permalink($reservation->title).'">'.$reservation->title.'</a>',
			'type'=>'reminder',
			'added'=>date('Y-m-d H:i:s'),
		));

		if($Clients->find($reservation->clientid)){
			
			$client = $Clients->data();
			$arrMails = str_replace(',', ';', $client->mail);
			$arrMails = explode(';',$arrMails);
			
			$Notifications->add(array(
				'name_from'=>$Mailing->get_email_name()->name,
				'email_from'=>$Mailing->get_email_name()->email,

				'name_to'=>$reservation->user_name,
				'email_to'=>$arrMails[0],

				'subject'=>"Recordatorio de reserva para ".$reservation->title,
				'body'=>Templates::template('reservations/reminder-client',$reservation),
				'log'=>'Notificación de recordatorio de reserva enviada a <a href="'.ROOT.'centros/'.$reservation->permalink.'">'.$reservation->client_name.'</a> para la promo <a href="'.ROOT.'promo/'.$reservation->permalink.'/'.$reservation->promoid.'-'.Permalink($reservation->title).'">'.$reservation->title.'</a>',
				'type'=>'reminder',
				'added'=>date('Y-m-d H:i:s'),
			));
			
		}


	}
}
