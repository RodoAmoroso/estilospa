
<section class="gral-section registro">
	<div class="container">

		<h1>Registro</h1>
		<p>Registrate para obtener acceso a todos los beneficios dentro de EstiloSPA.</p>
		<hr>

		<div class="row form-content">

			<div class="col-xs-12 col-sm-8 ">

				<!-- Google Login Button -->
				<div class="row mb-4">
					<div class="col-md-12">
						<div class="text-center">
							<p class="text-muted mb-3">Regstrate con</p>
							<a href="https://accounts.google.com/o/oauth2/auth?client_id=995346835833.apps.googleusercontent.com&redirect_uri=<?= urlencode(ROOT.'oauth2callback.php') ?>&scope=email%20profile&response_type=code" 
							   class="btn btn-outline-secondary btn-lg btn-block google-login-btn">
								<i class="fab fa-google"></i> Continuar con Google
							</a>
						</div>
					</div>
				</div>

				<div class="row">
					<div class="col-md-12">
						<div class="text-center">
							<hr>
							<p class="text-muted">O registrate con email y contraseña</p>
						</div>
					</div>
				</div>

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

