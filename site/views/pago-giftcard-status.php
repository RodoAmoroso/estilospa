<section class="gral-section">
	<div class="container">

		<?php if($_subsection=='success'): ?>
		<h1>Gracias!!!</h1>
		<h3>Hemos procesado la solicitud de pago exitosamente!</h3>

		<p>Tu GiftCard está lista para usar y lista para regalar.</p>
		<hr>

		<a href="<?= View::url('usuario','mis-giftcards') ?>" class="btn btn-aqua-3">
			<i class="fal fa-gift fa-fw"></i>
			<span>Mis GiftCards</span>
		</a>

		<?php endif; ?>

		<?php if($_subsection=='pending'): ?>
		<h1>Pago en Proceso...</h1>
		<h3>Una vez confirmada, te estará llegando un email con los detalles de la compra.</h3>
		<hr>

		<a href="<?= View::url('usuario','mis-giftcards') ?>" class="btn btn-aqua-3">
			<i class="fal fa-gift fa-fw"></i>
			<span>Mis GiftCards</span>
		</a>

		<?php endif; ?>


		<?php if($_subsection=='failure'): ?>
    <h1>Pago cancelado</h1>
		<h3>El proceso de compra ha sido cancelado. Intentalo más tarde.</h3>
		<hr>

		<a href="<?= View::url('usuario','giftcards') ?>" class="btn btn-aqua-3">
			<i class="fal fa-gift fa-fw"></i>
			<span>GiftCards</span>
		</a>

		<?php endif; ?>


	</div>

</section>