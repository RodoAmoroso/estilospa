
<section class="gral-section registro">
	<div class="container">

		<h1>Ingresar</h1>
		<hr>

		<div class="row align-items-center">

			<div class="col-12 col-lg-5">	
				
				<div class="border rounded p-5">

					<p class="text-muted">Ingresá con tu email y contraseña</p>

					<form id="form_login" >
						<div class="mb-3">
							<div class="form-floating">
								<input name="email" id="fd_mail" type="email" class="form-control" required placeholder="Email">
								<label for="fd_mail">Email</label>
							</div>
						</div>
						<div class="mb-3">
							<div class="form-floating">
								<input name="password" id="fd_password" type="password" class="form-control" required placeholder="Contraseña">
								<label for="fd_password">Contraseña</label>
							</div>
						</div>
	
						<div id="status"></div>
	
						<p class="sz-9"><a href="<?= View::url('recuperar-password') ?>">Olvidé mi contraseña</a></p>
						<div class="mb-3">
							<label for="" class="dp-block">&nbsp;</label>
							<button class="btn btn-aqua-3">Ingresar <i class="fal fa-angle-double-right"></i></button>
						</div>
					</form>
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
