<?php if(!$User->check_hash($userid,$hash)): ?>

<section class="gral-section">
	<div class="container">
		<h1>Algo ocurrió mal.</h1>
		<hr>
		<p>Hubo problemas al procesar la solicitud. Intenta nuevamente más tarde.<br /><br /> Si el problema persiste comunícate con nosotros <a href="<?= ROOT ?>contacto"><?= ROOT ?>contacto</a> </p>
	</div>
</section>

<?php else: ?>

<section class="gral-section">
	<div class="container">
		<h1>Nueva Contraseña!</h1>
		<p>Ingresa una nueva contraseña para poder ingresar a EstiloSPA.com.</p>
		<hr>
		<div class="row">
			<div class="col-xs-12 col-sm-4">

				<form id="form_reset">
					<div class="mb-3">
						<label for="fd_pass">Nuevo Password</label>
						<input name="password" id="fd_password" type="password" class="form-control" required>
					</div>
					<input name="hash" value="<?=$hash?>" type="hidden" >
					<input name="userid" value="<?=$userid?>" type="hidden" >
					<div class="mb-3">
						<button class="btn btn-fucsia">Enviar</button>
					</div>
				</form>

			</div>
		</div>
	</div>
</section>


<?php endif; ?>