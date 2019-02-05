
<!-- PAGE HEADER -->
<section class="page-header">
	<div class="container">
		<h1>Vinculación con Mercado Pago</h1>
	</div>
</section>


<?php if($_USER->data()->idclient == null): ?>
	<!-- RESTRICT -->
	<section class="admin-box bg-gray-5">
		<div class="container">
			<div class="block-white">
				<h4 class="">No hay ningún centro asociado a esta cuenta. Comunícate con nosotros para poder asociarte tu centro a esta cuenta.</h4>
				<hr>
				<a class="btn btn-primary" href="<?= ROOTPATH.'contacto' ?>">Contacto</a>
			</div>
		</div>
	</section>

<?php 
else:
	$MPConfig = new MPConfig();
	if(!$MPConfig->find($_USER->data()->idclient)):
?>
	<section class="admin-box bg-gray-5 ">
		<div class="container">
			<div class="block-white sz-12">

				<h3>Para poder operar con EstiloSpa necesitas vincular tu cuenta de Mercado Pago.</h3>
				<hr>

				<h4 class="fw-700">No tengo cuenta en Mercado Pago:</h4>

				<p>Si aún no tienes cuenta en Mercado Pago ingresa a <a href="https://www.mercadopago.com" class="cl-fucsia-4">este link</a> y registra tu cuenta (Si tienes cuenta en Mercado Libre ya tienes cuenta en Mercado Pago! Utiliza el mismo usuario y contraseña para ambos sitios).</p>

				<p>El proceso es fácil:</p>
				<ul class="number-list">
					<li>Te pagan</li>
					<li>Tus clientes usan su medio de pago preferido.</li>
					<li>El dinero se acredita</li>
					<li>Lo recibís en tu cuenta de Mercado Pago.</li>
					<li>Lo tenés disponible</li>
					<li>Unos días después, podrás transferirlo gratis a tu cuenta bancaria.</li>
				</ul>

				<hr>

				<h4 class="fw-700">Ya tengo cuenta en Mercado Pago:</h4>
				<p>Si ya tienes cuenta en Mercado Pago debes vincular tu cuenta con la cuenta de EstiloSpa, así cada vez que vendas una promo en nuestro sitio te llegará el dinero en tu cuenta.</p>
				<p>La comisión que te cobra EstiloSpa es de <?= $_USER->data()->fee ?>%</p>
				<p>&nbsp;</p>
				<p>El proceso es simple, tienes que hacer click en el botón de abajo.<br />Si no has ingresado a la plataforma de Mercado Pago te pedirámercadopago
				 que ingreses con tu usuario y contraseña. </p>
				<p>Una vez que llegues a la pantalla de vinculación, sólo tienes que darle permiso a Mercado Pago para que se vincule con la cuenta de EstiloSpa.</p>
				<p>&nbsp;</p>

				<a href="https://auth.mercadopago.com.ar/authorization?client_id=7300466898804487&response_type=code&platform_id=mp&redirect_uri=https%3A%2F%2Fwww.estilospa.com/mp.php" class="btn btn-success btn-lg"><i class="fa fa-handshake-o"></i> VINCULAR CUENTA</a>

			</div>
		</div>
	</section>

	<?php else: ?>
	<section class="admin-box bg-gray-5">
		<div class="container">
			<div class="block-white sz-12">

				<h3>Tu Cuenta de Mercado Pago ya ha sido vinculada con EstiloSPA</h3>
				<hr>
				<p>Ya puedes vender tus promociones a través de nuestra plataforma. Dirígite a la sección <a href="<?= ROOTPATH.'cuenta/promos' ?>">Promos</a> para crear o administrar todas las promociones disponibles de tu centro.</p>

				<hr>

				<p class="sz-9" >Si necesitas desvincular la cuenta debes hacer click en el botón de abajo. Además debes dirigirte a tu cuenta de MercadoPago, ir a la sección <b>Configuración</b>. Luego en la pestaña de <b>Seguridad</b> debes hacer click en el enlace <b>Administrar</b> que figura en la parte de <b>Aplicaciones conectadas</b>. Luego desde ahí elegir la aplicación de EstiloSPA y hacer click en el botón de <b>Desconectar</b>.</p>
				<button id="fd_unlink" class="btn btn-danger btn-xs">Desvincular Cuenta</button>

			</div>
		</div>
	</section>

	<?php endif; ?>

<?php endif; ?>
