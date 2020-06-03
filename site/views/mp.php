

<section class="gral-section">
	<div class="container">

		<?php if($response->status==200): ?>

		<h1>Vinculación Correcta!!!</h1>
		<hr>
		<p>Tu cuenta de MercadoPago ha sido vinculada con éxito!. Ahora podés habilitar la venta online en todas tus experiencias.</p>

		<?php else: ?>

		<h1>Lo Sentimos :(</h1>
		<hr>
		<p>Hubo un problema al procesar la solicitud. Intenta nuevamente más tarde. Si el problema persiste comunicate con nosotros </p>

		<?php endif; ?>

		<p>&nbsp;</p>
		<a href="<?= ROOT.'panel/mp' ?>" class="btn btn-primary btn-sm"><i class="fa fa-angle-left"></i> Volver</a>



	</div>
</section>