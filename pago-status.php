<?php

require_once 'config.php';
$hidechat = true;
require 'head.php';

$status = isset($_REQUEST['status']) ? $_REQUEST['status'] : '';
$back_url = isset($_REQUEST['promourl']) ? $_REQUEST['promourl'] : ROOTPATH;
$amount = isset($_REQUEST['amount']) ? $_REQUEST['amount'] : 0;

switch($status):
	case 'pending':		
?>
<section class="gral-section">
	<div class="container">
		<h1>Gracias!!!</h1>
		<hr>
		<p>Estamos procesando tu compra.</p>
		<p>Recuerda comunicarte con el centro para poder confirmar tu compra y reservar el día y el horario del turno.</p>
		<a href="<?=ROOTPATH?>" class="btn btn-primary">Volver</a>
	</div>
</section>

<script>
	fbq('track', 'Purchase', {
		value: '<?=$amount?>',
		currency: 'ARS'
	});
</script>

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
		<a href="<?=ROOTPATH?>" class="btn btn-primary">Volver</a>
	</div>
</section>
<script>
	fbq('track', 'Purchase', {
		value: '<?=$amount?>',
		currency: 'ARS'
	});
</script>

<?php
		break;	
	default:
?>
<section class="gral-section">
	<div class="container">
		<h1>Compra cancelada :(</h1>
		<hr>
		<p>El proceso de compra ha sido cancelado. Intentalo más tarde.</p>
		<p>&nbsp;</p>
		<a href="<?=$back_url?>" class="btn btn-primary">Volver</a>
	</div>
</section>


<?php
		break;
endswitch;

require 'scripts.php';
?>

</body>
</html>