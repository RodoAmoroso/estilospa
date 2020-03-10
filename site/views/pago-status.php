<?php
switch($status):
	case 'pending':
?>
<section class="gral-section">
	<div class="container">
		<h1>Pago en Proceso...</h1>
		<h3>Estamos procesando tu compra.</h3>
		<hr>

		<?php if($giftdata): ?>

		<p>Una vez confirmada, le estará llegando un email a <?=$giftdata->to_user->name.' ('.$giftdata->to_user->mail.')'?> con los detalles de la experiencia.</p>

		<?php else: ?>

		<?php if(is_null($sales_data->reservationid)): ?>
		<p>Una vez confirmada, recordá comunicarte con el centro para poder reservar el día y el horario del turno.</p>
		<a href="<?=Input::get('promourl').'/'.Input::get('hash').'#turno' ?>" class="btn btn-fucsia"><i class="fa fa-calendar fa-fw"></i> Reservar turno ahora</a>
		<a href="<?= View::url('mis-compras') ?>" class="btn btn-fucsia"><i class="fa fa-download fa-fw"></i> Descargar Voucher</a>
		<?php endif; endif;?>

		<hr>
		<a href="<?=ROOT?>" class="btn btn-primary btn-sm"><i class="fa fa-home fa-fw"></i> Volver al inicio</a>

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
		<h3>Hemos procesado la solicitud de pago exitosamente!</h3>
		<hr>

		<?php if($giftdata): ?>
		<h4>Tu regalo ha sido enviado con éxito a: <?=$giftdata->to_user->fullname.' ('.$giftdata->to_user->mail.')'?></h4>
		<p>En unos minutos le estará llegando un email con los detalles de la experiencia.</p>
		<?php else: ?>

		<?php if(is_null($sales_data->reservationid)): ?>
		<p>Reservá tu turno ahora con el centro ahora para asegurarte el día y horario de tu experiencia.</p>
		<a href="<?=Input::get('promourl').'/'.Input::get('hash').'#turno' ?>" class="btn btn-fucsia"><i class="fa fa-calendar fa-fw"></i> Reservar turno ahora</a>

		<a href="<?= View::url('mis-compras') ?>" class="btn btn-fucsia"><i class="fa fa-download fa-fw"></i> Descargar Voucher</a>
		<hr>
		<?php endif; endif; ?>

		<a href="<?=ROOT?>" class="btn btn-primary btn-sm"><i class="fa fa-home fa-fw"></i> Volver al inicio</a>

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

?>
