
<!-- PAGE HEADER -->
<section class="page-header">
	<div class="container">
		<h1>Vinculación con Mercado Pago</h1>
	</div>
</section>


<?php if($User->data()->idclient == null): ?>
	<!-- RESTRICT -->
	<section class="admin-box bg-gray-5">
		<div class="container">
			<div class="block-white">
				<h4 class="">No hay ningún centro asociado a esta cuenta. Comunicate con nosotros para poder asociarte tu centro a esta cuenta.</h4>
				<hr>
				<a class="btn btn-primary" href="<?= ROOT.'contacto' ?>">Contacto</a>
			</div>
		</div>
	</section>

<?php
else:
	$MPConfig = new MPConfig();
	if(!$MPConfig->find($User->data()->idclient)):
?>
	<section class="admin-box bg-gray-5 ">
		<div class="container">
			<div class="block-white sz-12">

				<h3>Para poder operar con EstiloSpa necesitás vincular tu cuenta de Mercado Pago.</h3>
				<hr>

				<h4 class="fw-700">No tengo cuenta en Mercado Pago:</h4>

				<p>Si aún no tenés cuenta en Mercado Pago ingresá a <a href="https://www.mercadopago.com" class="text-fucsia-4">este link</a> y registrá tu cuenta (Si tenés cuenta en Mercado Libre ya tenés cuenta en Mercado Pago! Utiliza el mismo usuario y contraseña para ambos sitios).</p>

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
				<p>Si ya tenés cuenta en Mercado Pago debes vincular tu cuenta con la cuenta de EstiloSpa, así cada vez que vendas una experiencia en nuestro sitio te llegará el dinero en tu cuenta.</p>
				<p>La comisión que te cobra EstiloSpa es de <?= $User->data()->fee ?>%</p>
				<p>&nbsp;</p>
				<p>El proceso es simple, hacé click en el botón de abajo.<br />Si no has ingresado a la plataforma de Mercado Pago te pedirá que ingreses con tu usuario y contraseña. </p>
				<p>Una vez que llegues a la pantalla de vinculación, sólo tenés que darle permiso a Mercado Pago para que se vincule con la cuenta de EstiloSpa.</p>
				<p>&nbsp;</p>

				<a href="https://auth.mercadopago.com.ar/authorization?client_id=<?=$MPConfig->app_id?>&response_type=code&platform_id=mp&state=<?= hash('sha256',date('YmdHis')) ?>&redirect_uri=<?=$MPConfig->redirect_uri?>" class="btn btn-success btn-lg"><i class="fa fa-handshake-o"></i> VINCULAR CUENTA</a>

			</div>
		</div>
	</section>

	<?php else: ?>
	<section class="admin-box bg-gray-5">
		<div class="container">
			<div class="block-white sz-12">

				<h3>Tu Cuenta de Mercado Pago ya ha sido vinculada con EstiloSPA</h3>
				<hr>
				<p>Ya podés vender tus experiencias a través de nuestra plataforma. Dirigite a la sección <a href="<?= ROOT.'panel/promos' ?>">Experiencias</a> para crear o administrar todas las experiencias disponibles de tu centro.</p>

				<hr>

				<p class="sz-9" >Si necesitás desvincular la cuenta hacé click en el botón de abajo. Además tenés que dirigirte a tu cuenta de MercadoPago, ir a la sección <b>Configuración</b>. Luego en la pestaña de <b>Seguridad</b> debes hacer click en el enlace <b>Administrar</b> que figura en la parte de <b>Aplicaciones conectadas</b>. Luego desde ahí elegir la aplicación de EstiloSPA y hacer click en el botón de <b>Desconectar</b>.</p>
				<button id="fd_unlink" class="btn btn-danger btn-xs">Desvincular Cuenta</button>

			</div>
		</div>
	</section>

	<?php endif; ?>

<?php endif; ?>
