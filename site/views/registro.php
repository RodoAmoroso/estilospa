
<section class="gral-section registro">
	<div class="container">

		<h1>Registro</h1>
		<p>Regístrate para obtener acceso a todos los beneficios dentro de EstiloSPA.</p>
		<hr>

		<div class="row form-content">

			<div class="col-xs-12 col-sm-8 ">

				<form id="form_register" >
					<div class="row">

						<div class="col-md-6 mb-3">
							<label for="fd_name">Nombre</label>
							<input name="name" type="text" class="form-control" required >
						</div>

						<div class="col-md-6 mb-3">
							<label for="fd_mail">E-Mail</label>
							<input name="email" type="email" class="form-control" required >
						</div>
						<div class="col-md-6 mb-3">
							<label for="fd_password">Password</label>
							<input name="password" type="password" class="form-control" required >
						</div>
						<div class="col-md-6 mb-3">
							<label for="fd_passwordagain">Repetir Password</label>
							<input name="password_repeat" type="password" class="form-control" required >
						</div>

						<div class="col-md-12 mb-3">
							<button class="btn btn-fucsia" data-loading-text="Enviando..."  >
								Registrarse <i class="fal fa-angle-double-right"></i>
							</button>
						</div>

						</div>

					</div>
				</form>

				<hr>
				<div>¿Ya tenés cuenta? <a href="<?= View::url('login') ?>" class="text-fucsia-3">Ingresá aquí</a></div>

			</div>
		</div>


	</div>
</section>

