<?php 

$MailBodyUser  = '
	<h3>¡Hola '.$_salesdata->username.'!</h3>
	<p>Lo sentimos, tu pago no pudo ser procesado. Intenta nuevamente.</p>
	<p>Si el problema persiste comunícate con nosotros.</p>
	<p>
		Gracias.<br />
		El equipo de EstiloSPA.com
	</p>';

$mailer->Subject = 'Compra en EstiloSPA - Nro: '.$collection_id;
$mailer->Body = $MailHead.$MailBodyUser.$MailFoot;
$mailer->addAddress($_salesdata->useremail, $_salesdata->username);
//$mailer->addBCC('estilospa.com@gmail.com');
//$mailer->addAddress('rodosoft@hotmail.com', 'Rodo');
$mailer->send();

//echo $mailer->Body;