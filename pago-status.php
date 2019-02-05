<?php

require_once 'config.php';
$hidechat = true;
require 'head.php';

$status = isset($_REQUEST['status']) ? $_REQUEST['status'] : '';

switch($status):
	case 'pending':		
?>
<section class="gral-section">
	<div class="container">
		<h1>Gracias!!!</h1>
		<hr>
		<p>Tu solicitud ha sido procesada. </p>
		<p>Recuerda comunicarte con el centro para poder confirmar tu compra y reservar el día y el horario del turno.</p>
		<a onclick="parent.CloseModalCheckout();" href="#" class="btn btn-primary">Volver</a>
	</div>
</section>

<?php
		break;
	case 'success':
?>
<section class="gral-section">
	<div class="container">
		<h1>Gracias!!!</h1>
		<hr>
		<p>Hemos procesado la solicitud de pago exitosamente!</p>		
		<p>Recuerda comunicarte con el centro para poder confirmar tu compra y reservar el día y el horario del turno.</p>
		<a onclick="parent.CloseModalCheckout();" href="#" class="btn btn-primary">Volver</a>
	</div>
</section>

<?php
		break;	
	default:
?>
<section class="gral-section">
	<div class="container">
		<h1>Lo Sentimos :(</h1>
		<hr>
		<p>Hubo problemas al procesar el pago. Intentalo más tarde.</p>
		<p>Si el problema persiste comunicate con nosotros</p>
		<a onclick="parent.CloseModalCheckout();" href="#" class="btn btn-primary">Volver</a>
	</div>
</section>

<?php
		break;
endswitch;

require 'scripts.php';
?>

</body>
</html>