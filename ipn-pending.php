<?php 

$MailBodyUser  = '
	<h2>¡Hola '.$_salesdata->username.'!</h2>
	<h3>Gracias por utilizar EstiloSPA.com!!!</a></h3>
	<p>Tu pedido está siendo procesado.</p>
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

///echo $mailer->Body;