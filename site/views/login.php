
<section class="gral-section registro">
	<div class="container">

		<h1>Ingresar</h1>
		<hr>

		<div class="row">

			<div class="col-xs-12 col-sm-8 ">

				<div class="row">
					<div class="col-xs-12 col-sm-6 bd-right-gray-5">

						<form id="form_login" >
							<div class="mb-3">
								<label for="fd_mail">E-Mail</label>
								<input name="email" id="fd_mail" type="email" class="form-control" required>
							</div>
							<div class="mb-3">
								<label for="fd_password">Password</label>
								<input name="password" id="fd_password" type="password" class="form-control" required>
							</div>

							<div id="status"></div>

							<p class="sz-9"><a href="<?= View::url('recuperar-password') ?>">Olvidé mi contraseña</a></p>
							<div class="mb-3">
								<label for="" class="dp-block">&nbsp;</label>
								<button class="btn btn-fucsia">Ingresar <i class="fa fa-angle-double-right"></i></button>
							</div>
						</form>



					</div>
					<div class="col-xs-12 col-sm-6">
						<div class="text-center">
							<p class="text-muted mb-3">O ingresa con</p>
							<a href="https://accounts.google.com/o/oauth2/auth?client_id=995346835833.apps.googleusercontent.com&redirect_uri=<?= urlencode(ROOT.'oauth2callback.php') ?>&scope=email%20profile&response_type=code" 
							   class="btn btn-outline-secondary btn-lg btn-block google-login-btn mb-2">
								<i class="fab fa-google"></i> Continuar con Google
							</a>
							
						</div>
					</div>

				</div>

			</div>
		</div>


	</div>
</section>
