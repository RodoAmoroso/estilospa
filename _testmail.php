<?php 
require 'config.php';
require 'ajax/templates-mail.php';
require 'ajax/phpmailer/PHPMailerAutoload.php';

$mailer->SMTPDebug = 3;
$mailer->setFrom('webmaster@estilospa.com', 'EstiloSPA.com');
$mailer->addReplyTo('consultas@estilospa.com', 'EstiloSPA.com');

$mailer->addAddress('rodosoft@gmail.com', 'Rodo');
$mailer->Subject = 'Registro nuevo usuario en EstiloSPA.com';

$mailer->isHTML(true);


$MailBody  = '<h1>¡Hola Rodo!</h1>
<br /><br />
<p>Te damos la bienvenida al nuevo sitio de EstiloSPA.com!!!</p>
<p>Te enviamos el nombre de usuario y contraseña para poder ingresar al portal.</p>


<br />
<p>Una vez dentro de la plataforma podrás editar la información de tu comercio dirigiéndote a la sección de configuración de tu cuenta que se encuentra en el menú de tu usuario en la parte superior derecha del sitio.</p>
<p>Desde ahí también podrás crear promociones de los servicios que brinda tu comercio para poder venderlas dentro de nuestro portal.</p>
<p>Te recordamos que para poder habilitar la venta online de las promociones deberás contar con una cuenta de MercadoPago  para poder vincularla con nuestra plataforma.</p>
<br /><br />
<p>
	Gracias.<br />
	El equipo de EstiloSPA.com
</p>';
$mailer->Body = $MailHead.$MailBody.$MailFoot;

echo "<pre>";
$mailer->send();
echo "</pre>";