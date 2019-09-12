<?php 

require '../config.php';

$Mailing = new Mailing();

$obj = new stdClass();
$obj->gift = new stdClass();

$obj->gift->touser = 'Maria Elena Gonzalez';
$obj->gift->fromuser = 'Jefatura Distrital';
$obj->gift->message = 'Muy feliz día!!! secre';


$obj->title = 'Circuito Masajes 1.30 horas';
$obj->includes = 'Masaje californiano profundo con aceite %100 puro de romero. Masaje reflexologico de pies con crema de menta.  Masaje vibracional de cuencos de cristal de cuarzo y tibetanos.';
$obj->image = 'https://www.estilospa.com/img/promos/masaje-californiano-7693-t.jpg';
$obj->promolink = 'https://www.estilospa.com/promo/vida-masajes/493-circuito-masajes-130-horas';
$obj->permalink = 'https://www.estilospa.com/centros/vida-masajes';
$obj->clientname = 'Vida Masajes';
$obj->clientemail = 'info@vidamasajes.com';

$obj->stores = '<ul style="padding:0 16px">
<li>Honduras 4747, 1º Piso - Palermo Soho</li>
<li>Tel: 4834-6407</li>
</ul>';


$Mailing->_mailer->addAddress('anahi@estilospa.com');
$Mailing->_mailer->Subject = 'Test';

$body = Templates::template('sales/_success-gift',$obj);
if(!$Mailing->send($body)) return false;

