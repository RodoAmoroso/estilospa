<?php
////////////// MAILING /////////////////////////


////////////////// GIFT ////////////////////////////
$MailBodyGift = '';
$clientdatagift = '';
$userdatagift = '';
$isgift = false;
if($_SALES->findgift($hash)){
	$isgift = true;
	$giftdata = $giftdata;

	$MailBodyGift = '<h2>¡Hola '.$giftdata->touser.'!</h2>
	<h3>'.$giftdata->fromuser.' te ha regalado la siguiente experiencia EstiloSPA!!!</h3>
	<div style="background-color:#dfdfdf;padding:16px;font-style:italic">'.$giftdata->message.'</div>
	<hr>
	<p>A continuación te detallamos en qué consiste:</p>
	<br />
	<h4>'.$_salesdata->title.'</h4>
	<p>'.$_salesdata->description.'</p>
	<p>'.$_salesdata->includes.'</p>
	<hr>
	<p><b>Nro de Comprobante: '.$collection_id.'</b></p>
	<hr>
	<h4>Canjeable en:</h4>
	<p>
		<a href="'.ROOTPATH.'centros/'.$_salesdata->permalink.'">'.$_salesdata->clientname.'</a><br />
		<small>Email: '.$_salesdata->clientemail.'</small>
	</p>
	<h5>Dirección(es):</h5>
	'.$stores.'
	<br />
	<p>Recuerda comunicarte con el centro para poder confirmar tu compra y reservar el día y el horario del turno.</p>
	<hr>
	<p>
		Gracias.<br />
		El equipo de EstiloSPA.com
	</p>';

	$clientdatagift = '
	<h4>El usuario ha regalado el servicio a:</h4>
	<ul>
		<li>Nombre: '.$giftdata->touser.'</li>
		<li>E-mail: '.$giftdata->mail.'</li>
	</ul>';
	
	$userdatagift = '
	<h4>Le regalaste este servicio a:</h4>
	<p>
		- Nombre: '.$giftdata->touser.'<br />
		- E-mail: '.$giftdata->mail.'
	</p>';
	///die($MailBodyGift);
}
////////////////// USER //////////////////////////////
$MailBodyUser  = '
	<h2>¡Hola '.$_salesdata->username.'!</h2>
	<h3>Gracias por tu compra en EstiloSPA.com!!!</a></h3>
	<p>A continuación te detallamos tu compra:</p>
	<br />
	<h4>'.$_salesdata->title.'</h4>
	<p>'.$_salesdata->description.'</p>
	<p>'.$_salesdata->includes.'</p>
	<hr>
	<p><b>Nro de Operación: '.$collection_id.'</b></p>
	<p>Valor: '.$quantity.' x $ '.$priceformat.'</p>
	<p><b>Total: $ '.number_format($price*$quantity,2,',','.').'</b></p>
	<hr>
	'.$userdatagift.'
	<hr>
	<h4>Datos del Centro:</h4>
	<p>
		<a href="'.ROOTPATH.'centros/'.$_salesdata->permalink.'">'.$_salesdata->clientname.'</a><br />
		<small>Email: '.$_salesdata->clientemail.'</small>
	</p>
	<h5>Dirección(es):</h5>
	'.$stores.'
	<br />
	<p>Recuerda comunicarte con el centro para poder confirmar tu compra y reservar el día y el horario del turno.</p>
	<hr>
	<p>
		Gracias.<br />
		El equipo de EstiloSPA.com
	</p>';
////////////////// CLIENT ////////////////////////////
$MailBodyClient  = '
	<h2>¡Hola '.$_salesdata->clientname.'!</h2>
	<h3>Nueva venta en EstiloSPA.com!!!</a></h3><br />
	<h4>Datos del comprador:</h4>
	<ul>
		<li>Nombre completo: '.$_salesdata->username.'</li>
		<li>E-mail: '.$_salesdata->useremail.'</li>
		<li>Teléfono: '.(empty($_salesdata->phone) ? 'no indicó ninguno' : $_salesdata->userphone).'</li>
	</ul>
	'.$clientdatagift.'
	<hr>
	<h4>Datos de la promo:</h4>
	<p><a href="'.ROOTPATH.'promo/'.$_salesdata->permalink.'/'.$idpromo.'-'.Permalink($_salesdata->title).'">'.$_salesdata->title.'</a></p>
	<p>'.$_salesdata->description.'</p>
	<hr>
	<p><b>Nro de Operación: '.$collection_id.'</b></p>
	<p>Valor: '.$quantity.' x $ '.$priceformat.'</p>
	<p><b>Total: $ '.number_format($price*$quantity,2,',','.').'</b></p>
	<br /><br />
	<hr>
	<p>
		Gracias.<br />
		El equipo de EstiloSPA.com
	</p>';
/////////////////////////////////////////////////////////
$mailer->Subject = 'Detalles de compra de '.$_salesdata->title;	
$mailer->Body = $MailHead.$MailBodyUser.$MailFoot;
$mailer->addAddress($_salesdata->useremail, $_salesdata->username);
//$mailer->addAddress('rodosoft@gmail.com','Rodo');
$mailer->send();
//print_r($mailer);

$mailer->ClearAllRecipients();
$mailer->Subject = 'Nueva venta en EstiloSPA - Nro: '.$collection_id;
$mailer->Body = $MailHead.$MailBodyClient.$MailFoot;
$mailer->addAddress($_salesdata->clientmail, $_salesdata->clientname);
$mailer->addBCC('estilospa.com@gmail.com');
//$mailer->addAddress('rodosoft@hotmail.com', 'Rodo');
$mailer->send();

//echo $mailer->Body;

if($isgift){
	$mailer->ClearAllRecipients();
	$mailer->Subject = $giftdata->fromuser.' te ha regalado esta promo!';
	$mailer->Body = $MailHead.$MailBodyGift.$MailFoot;
	$mailer->addAddress($giftdata->mail, $giftdata->touser);
	//$mailer->addAddress('rodosoft@hotmail.com', 'Rodo');
	$mailer->send();
}