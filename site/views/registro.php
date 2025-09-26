
<section class="gral-section registro">
	<div class="container">

		<h1>Registro</h1>
		<p>Registrate para obtener acceso a todos los beneficios dentro de EstiloSPA.</p>
		<hr>

		<div class="row align-items-center">

			<div class="col-12 col-lg-5">

				<div class="border rounded p-5">

					<p class="text-muted">Registrate con email y contraseña</p>
					<form id="form_register" >					
	
						<div class="form-floating mb-3">
							<input id="fd_name" name="name" type="text" class="form-control" required placeholder="Nombre">
							<label for="fd_name">Nombre</label>
						</div>
	
						<div class="form-floating mb-3">
							<input name="email" id="fd_mail" type="email" class="form-control" required placeholder="Email">
							<label for="fd_mail">Email</label>
						</div>
						<div class="form-floating mb-3">
							<input name="password" id="fd_password" type="password" class="form-control" required placeholder="Contraseña">
							<label for="fd_password">Contraseña</label>
						</div>
						<div class="form-floating mb-3">
							<input name="password_repeat" id="fd_passwordagain" type="password" class="form-control" required placeholder="Repetir Contraseña">
							<label for="fd_passwordagain">Repetir Contraseña</label>
						</div>
	
						<div class="col-md-12 mb-3">
							<button class="btn btn-aqua-3" data-loading-text="Enviando..."  >
								Registrarse <i class="fal fa-angle-double-right"></i>
							</button>
						</div>
	
					</form>
	
					<hr>
					<div>¿Ya tenés cuenta? <a href="<?= View::url('login') ?>" class="text-fucsia-3">Ingresá aquí</a></div>
				</div>			

			</div>

			<div class="col-12 col-lg-4 text-center">
						
				<p class="text-muted mb-3">ó Ingresar con</p>
				<a href="https://accounts.google.com/o/oauth2/auth?client_id=890654212350-j6l812ihgmgosf3309fn709g60h6fd78.apps.googleusercontent.com&redirect_uri=<?= urlencode(ROOT.'oauth2callback') ?>&scope=email%20profile&response_type=code" class="btn btn-outline-secondary btn-lg btn-block google-login-btn mb-2">
					<img src="<?= View::assets('icon-google.svg') ?>" alt=""> Continuar con Google
				</a>							
				
			</div>


		</div>


	</div>
</section>

